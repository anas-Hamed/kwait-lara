<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetWebLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale', 'ar'));

        if (in_array($locale, ['ar', 'en'])) {
            App::setLocale($locale);
            $direction = $locale === 'ar' ? 'rtl' : 'ltr';
            config(['backpack.base.html_direction' => $direction]);
            config(['backpack.ui.html_direction' => $direction]);
        }

        return $next($request);
    }
}
