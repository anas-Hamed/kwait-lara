<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    public function __invoke(Request $request)
    {
        $term = $request->input('search_term');
        $limit = $request->input('limit',10);
        $offset= $request->input('offset',0);

        if (!$term){
            return $this->sendResponse([]);
        }
        $companies = Company::query()->orderByDesc('is_featured')->orderBy('order')
            ->withDomainImage(['id','slug','tags', 'ar_name', 'en_name','about','is_trusted','average_rate','phone','whatsapp','category_id'])
            ->CanAppear()
            ->where(function (Builder $q) use ($term) {
                $q->where('ar_name', 'like', "%$term%")
                ->orWhere('tags', 'like', "%$term%")
                    ->orWhere('en_name', 'like', "%$term%")
                    ->orWhere('about', 'like', "%$term%");
            })->with('category')->limit($limit)->offset($offset)->get();

        $categories = Category::query()->withDomainImage(['id', 'name', 'image', 'parent_id'])->CanAppear()
            ->where('name', 'like', "%$term%")
            ->limit($limit)->offset($offset)->get();

        foreach ($companies as $item) {
            $item->type = 'company';
        }

        foreach ($categories as $item) {
            $item->type = 'category';
        }

        $collection = array_merge($companies->toArray(), $categories->toArray());

        return $this->sendResponse($collection);
    }
}
