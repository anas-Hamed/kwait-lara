<?php

namespace App\Http\Controllers\API;


use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends BaseController
{
    public function index(Request $request)
    {
        $offset = $request->input("offset", 0);
        $limit = $request->input("limit", 30);

        return $this->sendResponse(Blog::query()->withDomainImage(['id','title','image','slug','created_at'])->take($limit)->skip($offset)->get());
    }

    public function show($slug){
        return $this->sendResponse(Blog::query()->withDomainImage()->where('slug',$slug)->firstOrFail());
    }
}
