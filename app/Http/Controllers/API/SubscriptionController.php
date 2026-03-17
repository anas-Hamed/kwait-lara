<?php

namespace App\Http\Controllers\API;

use App\Models\Subscription;

class SubscriptionController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(Subscription::with(['plan', 'company'])->get());
    }
}
