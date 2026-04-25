<?php

namespace App\Http\Controllers\API;



use App\Models\Setting;

class SettingController extends BaseController
{
    public function __invoke()
    {
        return $this->sendResponse(Setting::all()->pluck('value', 'key'));
    }

    public function terms()
    {
        $entry = Setting::where('key', 'terms')->first();
        return $this->sendResponse($entry ? $entry->getTranslations('value') : null);
    }

    public function privacy()
    {
        $entry = Setting::where('key', 'privacy')->first();
        return $this->sendResponse($entry ? $entry->getTranslations('value') : null);
    }
}
