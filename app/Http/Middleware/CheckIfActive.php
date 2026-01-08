<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfActive
{
    /**
     * Checked if active user .
     *
     * --------------
     * VERY IMPORTANT
     * --------------
     * If you have both regular users and admins inside the same table,
     * change the contents of this method to check that the logged in user
     * is an admin, and not a regular user.
     *
     * @param [type] $user [description]
     *
     * @return bool [description]
     */
    private function checkIfUserIsActive($user)
    {
         return  $user->is_active == 1;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (! $this->checkIfUserIsActive($request->user('sanctum'))) {
            return response()->json([
                'success' => false,
                'message' => trans('messages.disabled_account'),
            ], 409);
        }
        return $next($request);
    }
}
