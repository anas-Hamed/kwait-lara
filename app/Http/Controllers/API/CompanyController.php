<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\CompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\CompanyTrustRequest;
use App\Models\CompanyUpdate;
use App\Models\DeletedCompany;
use App\Models\ImageItem;
use App\Models\User;
use App\Notifications\UserAddNewCompanyNotificationForAdmin;

use App\Notifications\UserDeleteCompanyNotificationForAdmin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class CompanyController extends BaseController
{
    public function index(Request $request)
    {
        $offset = $request->input("offset", 0);
        $limit = $request->input("limit", 30);
        $category_id = $request->input("category_id");
        $keyword = $request->input('keyword');


        $query = Company::query();
        if (!is_null($category_id))
            $query->where('category_id', $category_id);

        if (!is_null($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('ar_name', 'like', '%' . $keyword . '%')
                    ->orWhere('en_name', 'like', '%' . $keyword . '%')
                    ->orWhere('about', 'like', '%' . $keyword . '%')
                    ->orWhere('tags', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        $query->orderByDesc('is_featured')->orderBy('order');


        try {
            $companies = $query->withDomainImage(['id', 'ar_name', 'en_name', 'slug', 'category_id', 'about', 'phone', 'whatsapp', 'average_rate', 'is_active', 'has_paid', 'is_trusted'])
                ->withFavoriteStatus()->canAppear()
                ->with(['category:id,name'])->take($limit)->skip($offset)->get();

            return $this->sendResponse($companies, 'Companies fetched.');
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('');
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {

            $company = Company::query()
                ->withDomainImage()
                ->withFavoriteStatus()
                ->canAppear()
                ->with([
                    'images' => function ($q) {
                        $q->withDomainPath();
                    },
                    'workTimes',
                    'category' => function ($q) {
                        $q->withDomainImage();
                    }])
                ->findOrFail($id);
            return $this->sendResponse($company, 'Company fetched.');
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('', 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function slugShow($slug)
    {
        try {

            $company = Company::query()
                ->withDomainImage()
                ->withFavoriteStatus()
                ->canAppear()
                ->with([
                    'images' => function ($q) {
                        $q->withDomainPath();
                    },
                    'workTimes',
                    'category' => function ($q) {
                        $q->withDomainImage();
                    }])
                ->where('slug', $slug)->firstOrFail();
            return $this->sendResponse($company, 'Company fetched.');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError($exception->getMessage(), 404);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('', $exception->getCode() ?? 500);
        }
    }


    public function store(CompanyRequest $request)
    {
        $this->middleware('auth:sanctum');

        $data = $request->validated();
        $data['slug'] = Str::slug($data['ar_name']);
        if (!isset($data['image'])) {
            $data['image'] = Image::make(database_path('factories/faker.PNG'));
        }
        /** @var User $user */
        $user = $request->user('sanctum');
        DB::beginTransaction();
        try {
            /** @var Company $company */
            $company = $user->companies()->create($data);

            if (!$company)
                throw new \Exception('Failed');
            $company->workTimes()->createMany($data['work_times']);

            $images = (array)$request->files->get('images');

            foreach ($images as $image) {
                $img = ImageItem::withDestination('company/' . $company->id, 10 / 4);
                $img->related_id = $company->id;
                $img->related_type = Company::class;
                $img->path = $image;
                $img->save();
            }
            DB::commit();
            Notification::send(User::query()->where('is_admin', true)->get(), new UserAddNewCompanyNotificationForAdmin($user, $company));
            return $this->sendResponse($company, 'Company created.');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), ['trace' => $exception->getTrace()]);
            return $this->sendError(__('messages.failed'));
        }


    }


    public function update(UpdateCompanyRequest $request, Company $company)
    {

        $this->middleware('auth:sanctum');
        $ar_name = $request->input('ar_name');
        $en_name = $request->input('en_name');
        $phone = $request->input('phone');
        $whatsapp = $request->input('whatsapp');
        $data = $request->validated();
        Arr::forget($data, ['ar_name', 'en_name', 'phone', 'whatsapp']);

        DB::beginTransaction();
        try {
            $company->update($data);
            foreach ($data['work_times'] as $time) {
                $company->workTimes()->where('id', $time['id'])->update($time);
            }

            $images = (array)$request->files->get('images');
            $images_to_delete = (array)$request->input('images_to_delete');

            ImageItem::query()->whereIn('id', $images_to_delete)->delete();

            foreach ($images as $image) {
                $img = ImageItem::withDestination('company/' . $company->id, 10 / 4);
                $img->related_id = $company->id;
                $img->related_type = Company::class;
                $img->path = $image;
                $img->save();
            }

            $new_values = [];
            $old_values = [];

            if ($ar_name != $company->ar_name) {
                $new_values['ar_name'] = $ar_name;
                $old_values['ar_name'] = $company->ar_name;
            }
            if ($en_name != $company->en_name) {
                $new_values['en_name'] = $en_name;
                $old_values['en_name'] = $company->en_name;
            }
            if ($phone != $company->phone) {
                $new_values['phone'] = $phone;
                $old_values['phone'] = $company->phone;
            }
            if ($whatsapp != $company->whatsapp) {
                $new_values['whatsapp'] = $whatsapp;
                $old_values['whatsapp'] = $company->whatsapp;
            }

            if (count($new_values)) {
                if ($company->updates()->count() > 0) {
                    return $this->sendError('You can not update name,phone,whatsapp : There are updates must approved before', 409);
                }
                CompanyUpdate::query()->create([
                    'company_id' => $company->id,
                    'new_values' => $new_values,
                    'old_values' => $old_values
                ]);
            }


            DB::commit();
            return $this->show($company->id);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return $this->sendError(__('messages.failed'), 500);

        }

    }


    /**
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Company $company)
    {
        if (auth('sanctum')->user()->id != $company->user_id) {
            return $this->sendError(__('messages.failed'), 403);
        }
        try {
            $deletedCompany = DeletedCompany::query()->create($company->toArray());

            $company->images()->each(function ($el) use ($deletedCompany) {
                /** @var \App\Models\ImageItem $el */
                $el->update([
                    'related_type' => DeletedCompany::class,
                    'related_id' => $deletedCompany->id
                ]);
            });

            $company->workTimes()->each(function ($el) use ($deletedCompany) {
                $deletedCompany->workTimes()->create([
                    'day' => $el->day,
                    'start_time' => $el->start_time,
                    'end_time' => $el->end_time,
                    'active' => $el->active,
                ]);
                $el->delete();
            });
            $company->updates()->delete();
            $company->rates()->delete();
            $company->favorites()->delete();
            $company->trustRequest()->delete();

            $company->delete();
            Notification::send(User::query()->where('is_admin', 1)->get(), new UserDeleteCompanyNotificationForAdmin(auth('sanctum')->user(), $deletedCompany));
            return $this->sendResponse();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500, __('messages.failed'));
        }
    }


    public function toggleFavorite(Request $request, Company $company)
    {
        $this->middleware('auth:sanctum');
        /** @var User $user */
        $user = $request->user('sanctum');
        $user_has_favorite_company = $user->favoriteCompanies()->where('company_id', $company->id)->count() > 0;
        $method = $user_has_favorite_company ? 'detach' : 'attach';
        $user->favoriteCompanies()->$method($company->id);

        return $this->sendResponse(!$user_has_favorite_company);
    }

    public function rate(Request $request)
    {
        $this->middleware('auth:sanctum');
        $request->validate([
            'company_id' => ['required', Rule::exists('companies', 'id')->where('is_active', true)],
            'rate' => 'required|numeric|min:0|max:5',
        ]);

        DB::beginTransaction();
        try {
            /** @var Company $company */
            $company = Company::query()->find($request->input('company_id'));

            /** @var User $user */
            $user = $request->user('sanctum');
            $user->ratedCompanies()->sync([$request->input('company_id') => ['rate' => $request->input('rate')]]);

            $new_rate = $company->updateAverageRate();
            DB::commit();
            return $this->sendResponse($new_rate);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return $this->sendError('', 500);
        }
    }

    public function userFavorite(Request $request)
    {
        $offset = $request->input("offset", 0);
        $limit = $request->input("limit", 30);
        $orderBy = $request->input("orderBy", "id");
        $orderDirection = $request->input("orderDirection", 'Desc');
        $keyword = $request->input('keyword');


        /** @var User $user */
        $user = $request->user('sanctum');
        /** @var Company $query */
        $query = Company::query()->whereIsActive(true)->whereIn('id', $user->favoriteCompanies()->pluck('id'));

        if (!is_null($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('ar_name', 'like', '%' . $keyword . '%')
                    ->orWhere('en_name', 'like', '%' . $keyword . '%')
                    ->orWhere('about', 'like', '%' . $keyword . '%')
                    ->orWhere('tags', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }
        if (!is_null($orderBy))
            $query->orderBy($orderBy, $orderDirection);

        try {
            $companies = $query->withDomainImage()->addSelect(DB::raw('1 as has_favorite'))->with(['category:id,name'])->take($limit)->skip($offset)->get();

            return $this->sendResponse($companies, 'Companies fetched.');
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('');
        }
    }

    public function getMyCompany(Request $request)
    {
        $this->middleware('auth:sanctum');
        /** @var User $user */
        $user = $request->user('sanctum');
        /** @var Company $query */
        $query = $user->companies()->when($request->input('not_trusted'), function ($q) {
            $q->where('is_trusted', false)->whereDoesntHave('trustRequest');
        });
        $companies = $query->withDomainImage()
            ->withFavoriteStatus()->with(['category:id,name'])->orderByDesc('id')->get();
        return $this->sendResponse($companies, 'Companies fetched.');
    }


    public function trust(Company $company)
    {
        if ($company->user_id != auth('sanctum')->user()->id) {
            return $this->sendError('Permission Denied', 403);
        } elseif (!$company->has_paid) {
            return $this->sendError('Pay Must Done First', 403);
        } elseif ($company->trustRequest != null) {
            return $this->sendError('Trust Request Send Before', 403);
        }
        CompanyTrustRequest::create([
            'company_id' => $company->id,
            'user_id' => auth('sanctum')->user()->id
        ]);
        return $this->sendResponse();
    }
}
