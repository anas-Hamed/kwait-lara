<?php

namespace App\Http\Controllers\API;

use App\Models\Ad;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Category;

class CategoryController extends BaseController
{

    public function index(Request $request)
    {

        $all_requested = $request->input('all', false);
        $parent_id = $request->input('parent_id', null);
        $categories = Category::query()->orderBy('order');

        if ($all_requested == false)
            $categories->whereParentId($parent_id);


        $categories = $categories->withDomainImage()->whereIsActive(true)->with('parent')->get();
        return $this->sendResponse($categories);
    }

    public function show($id)
    {
        $category = Category::query()->withDomainImage()->whereIsActive(true)->with(['children' => function($q){
            $q->orderBy('order')->withDomainImage();
    }])->findOrFail($id);
        return $this->sendResponse($category);
    }


    public function main(Request $request)
    {
        $limit = $request->input('limit', 5);

        $categories = Category::query()->orderBy('order')
            ->withDomainImage()
            ->whereIsActive(true)
            ->whereNull('parent_id')
            ->orderBy('order', 'desc')->limit($limit)->get();

        $ads = Ad::query()->withDomainImage()->whereIsActive(true)->get();
        $blogs = Blog::query()->orderByDesc('id')->limit(3)->withDomainImage(['id','title','image','created_at','slug'])->get();

        return $this->sendResponse([
            'categories' => $categories,
            'ads' => $ads,
            'blogs' => $blogs
        ]);
    }
}
