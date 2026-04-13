<?php

namespace App\Http\Controllers\API;



use App\Models\Setting;

class SettingController extends BaseController
{
    public function __invoke()
    {
        return $this->sendResponse(Setting::query()->pluck('value','key'));
    }

    public function terms()
    {
        return $this->sendResponse(Setting::get('terms'));
    }
}
