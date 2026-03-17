<?php

namespace App\Http\Controllers\API;

use App\Models\Plan;

class PlanController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(Plan::all());
    }
}
