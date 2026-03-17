<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\QaItem;

class QaController extends BaseController
{
    /**
     * GET /api/qa/categories
     * Returns categories that have at least one QA item.
     */
    public function categories()
    {
        $categories = Category::query()
            ->whereIsActive(true)
            ->whereNull('parent_id')
            ->whereHas('qaItems')
            ->orderBy('order')
            ->get(['id', 'name']);

        return $this->sendResponse($categories);
    }

    /**
     * GET /api/qa?category_id=X
     * Returns questions (without answer) for a given category.
     */
    public function index()
    {
        $categoryId = request('category_id');

        $items = QaItem::query()
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->get(['id', 'category_id', 'question']);

        return $this->sendResponse($items);
    }

    /**
     * GET /api/qa/category/{categoryId}
     * Returns questions (without answer) for a specific category.
     */
    public function byCategory($categoryId)
    {
        $items = QaItem::query()
            ->where('category_id', $categoryId)
            ->get(['id', 'category_id', 'question']);

        return $this->sendResponse($items);
    }

    /**
     * GET /api/qa/{id}
     * Returns a single QA item with its answer.
     */
    public function show($id)
    {
        $item = QaItem::findOrFail($id);
        return $this->sendResponse($item);
    }
}
