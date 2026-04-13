<?php

namespace App\Http\Controllers\API;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\DeletedCompany;
use App\Models\ImageItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserDeleteCompanyNotificationForAdmin;
use Illuminate\Support\Facades\Notification;


class AuthController extends BaseController
{


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_admin' => 0])) {
            /** @var User $authUser */
            $authUser = Auth::user();
            $accessToken = $authUser->createToken($request->userAgent())->plainTextToken;

            return $this->sendResponse(['token' => $accessToken, 'user' => $authUser]);

        } else {
            throw new AuthenticationException();
        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        return $this->sendResponse(['user' => auth()->user()]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string:min:3',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->user()->id)],
            'phone' => ['required', 'phone:AUTO', Rule::unique('users', 'phone')->ignore($request->user()->id)],
        ]);
        /** @var User $user */
        $user = $request->user();
        $user->update($request->only(['name', 'email', 'phone']));
        return $this->sendResponse($user->fresh());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => ['required', 'confirmed']
        ]);
        /** @var User $user */
        $user = $request->user();
        if (!Hash::check($request->input('old_password'), $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'mismatch'
            ]);
        }
        $user->password = bcrypt($request->input('new_password'));
        $user->save();
        return $this->sendResponse();
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
//        dd(DB::connection()->getDatabaseName());
//        dd($request->all());
        $request->validate([
            'name' => 'required|string:min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|confirmed'
        ]);


        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        /** @var User $user */
        $user = User::query()->create($input);//
//        event(new Registered($user));

        $accessToken = $user->createToken($request->userAgent())->plainTextToken;

        return $this->sendResponse(['user' => $user, 'token' => $accessToken], __('auth.User Registered'));

    }

    public function sendPasswordResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 200);
        } else {
            throw ValidationException::withMessages([
                'email' => __($status)
            ]);
        }
    }

    public function resetPassword(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:password_resets,email',
            'password' => 'required|min:6|confirmed',
        ]);


        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            try {
                return $this->login($request);
            } catch (\Throwable $exception) {
                return $this->sendError(__('messages.failed'), 401, "Login Failed");
            }
        } else {
            return $this->sendError(__('messages.failed'), 411, "Reset Failed");
        }
    }


    public function deleteAccount(Request $request)
    {
        /** @var User $user */
        $user = $request->user('sanctum');

        DB::beginTransaction();
        try {
            foreach ($user->companies as $company) {
                $deletedCompany = DeletedCompany::query()->create($company->toArray());

                $company->images()->each(function ($el) use ($deletedCompany) {
                    $el->update([
                        'related_type' => DeletedCompany::class,
                        'related_id'   => $deletedCompany->id,
                    ]);
                });

                $company->workTimes()->each(function ($el) use ($deletedCompany) {
                    $deletedCompany->workTimes()->create([
                        'day'        => $el->day,
                        'start_time' => $el->start_time,
                        'end_time'   => $el->end_time,
                        'active'     => $el->active,
                    ]);
                    $el->delete();
                });

                $company->updates()->delete();
                $company->rates()->delete();
                $company->favorites()->delete();
                $company->trustRequest()->delete();

                Notification::send(
                    User::query()->where('is_admin', 1)->get(),
                    new UserDeleteCompanyNotificationForAdmin($user, $deletedCompany)
                );

                $company->delete();
            }

            $user->favorites()->delete();
            $user->fcmTokens()->delete();
            $user->tokens()->delete();
            $user->delete();

            DB::commit();
            return $this->sendResponse([], 'Account deleted.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['trace' => $e->getTrace()]);
            return $this->sendError(__('messages.failed'));
        }
    }


    public function saveFcm(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        /** @var User $user */
        $user = $request->user('sanctum');

        if ($user->fcmTokens()->where('token',$request->input('token'))->count() == 0){
            $user->fcmTokens()->create([
                'token' => $request->input('token')
            ]);
        }

        return response()->noContent();
    }


}
