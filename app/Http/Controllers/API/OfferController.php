<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\OfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Models\Company;
use App\Models\Offer;
use App\Models\User;
use App\Notifications\CompanyNewOfferNotificationForFavorites;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class OfferController extends BaseController
{
    /**
     * Public list of running offers (offers belonging to visible companies).
     */
    public function index(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 30);
        $company_id = $request->input('company_id');
        $category_id = $request->input('category_id');
        $keyword = $request->input('keyword');

        $query = Offer::query()->running()
            ->whereHas('company', function ($q) use ($company_id, $category_id) {
                $q->canAppear();
                if (!is_null($company_id)) {
                    $q->where('id', $company_id);
                }
                if (!is_null($category_id)) {
                    $q->where('category_id', $category_id);
                }
            });

        if (!is_null($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('ar_title', 'like', '%' . $keyword . '%')
                    ->orWhere('en_title', 'like', '%' . $keyword . '%')
                    ->orWhere('ar_description', 'like', '%' . $keyword . '%')
                    ->orWhere('en_description', 'like', '%' . $keyword . '%');
            });
        }

        try {
            $offers = $query->withDomainImage()
                ->with(['company:id,ar_name,en_name,slug,category_id'])
                ->orderByDesc('id')
                ->take($limit)->skip($offset)->get();

            return $this->sendResponse($offers, 'Offers fetched.');
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('');
        }
    }

    /**
     * Public single offer (must be running + company visible).
     */
    public function show($id)
    {
        try {
            $offer = Offer::query()->running()
                ->whereHas('company', function ($q) {
                    $q->canAppear();
                })
                ->withDomainImage()
                ->with(['company:id,ar_name,en_name,slug,category_id'])
                ->findOrFail($id);

            return $this->sendResponse($offer, 'Offer fetched.');
        } catch (ModelNotFoundException $exception) {
            return $this->sendError($exception->getMessage(), 404);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('', 500);
        }
    }

    /**
     * Public list of a company's running offers.
     */
    public function companyOffers(Company $company)
    {
        try {
            $offers = $company->offers()->running()
                ->withDomainImage()
                ->orderByDesc('id')
                ->get();

            return $this->sendResponse($offers, 'Offers fetched.');
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError('');
        }
    }

    public function store(OfferRequest $request)
    {
        $this->middleware('auth:sanctum');

        $data = $request->validated();

        /** @var Company $company */
        $company = Company::query()->find($data['company_id']);
        if (!$company || $company->user_id != auth('sanctum')->id()) {
            return $this->sendError(__('messages.failed'), 403);
        }

        DB::beginTransaction();
        try {
            /** @var Offer $offer */
            $offer = $company->offers()->create($data);
            DB::commit();

            try {
                $favoriteUsers = User::query()
                    ->whereIn('id', DB::table('user_favorite_companies')
                        ->where('company_id', $company->id)->pluck('user_id'))
                    ->get();
                if ($favoriteUsers->isNotEmpty()) {
                    Notification::send($favoriteUsers, new CompanyNewOfferNotificationForFavorites($company, $offer));
                }
            } catch (\Throwable $e) {
                Log::error('Offer notification failed: ' . $e->getMessage());
            }

            return $this->sendResponse($offer->fresh(), 'Offer created.');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.failed'));
        }
    }

    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        $this->middleware('auth:sanctum');

        if ($offer->company->user_id != auth('sanctum')->id()) {
            return $this->sendError(__('messages.failed'), 403);
        }

        try {
            $offer->update($request->validated());
            return $this->sendResponse($offer->fresh(), 'Offer updated.');
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.failed'), 500);
        }
    }

    public function destroy(Offer $offer)
    {
        $this->middleware('auth:sanctum');

        if ($offer->company->user_id != auth('sanctum')->id()) {
            return $this->sendError(__('messages.failed'), 403);
        }

        try {
            // triggers HasImage cleanup of the stored file + thumbs
            $offer->image = null;
            $offer->delete();
            return $this->sendResponse();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.failed'), 500);
        }
    }

    public function toggleActive(Offer $offer)
    {
        $this->middleware('auth:sanctum');

        if ($offer->company->user_id != auth('sanctum')->id()) {
            return $this->sendError(__('messages.failed'), 403);
        }

        $offer->is_active = !$offer->is_active;
        $offer->save();

        return $this->sendResponse($offer->is_active);
    }
}
