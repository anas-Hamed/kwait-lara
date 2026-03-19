<?php

namespace App\Http\Controllers\API;

use App\Models\ContactUs;
use Illuminate\Http\Request;


class ContactUsController extends BaseController
{
    public function __invoke(Request $request)
    {
        $request->validate([
            "name" => "required|min:3|max:75",
            "email" => "nullable|email",
            "phone" => "required|numeric|max:12",
            "message" => "required|min:10"
        ]);
        ContactUs::create([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "phone" => $request->input('phone'),
            "message" => $request->input('message')
        ]);
        return $this->sendResponse([],'');
    }
}
