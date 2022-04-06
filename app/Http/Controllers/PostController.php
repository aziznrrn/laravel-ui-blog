<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->category_id ?? null;
        $articles = Article::with(['category', 'user'])
            ->when($category_id, function ($query, $category_id) {
                $query->where('category_id', $category_id);
            })->latest()->paginate(10);
        
        $category = $category_id ? Category::where('id', $category_id)->first() : null;
        
        return view('frontpage.index', [
            'category' => $category,
            'articles' => $articles
        ]);
    }

    public function show($id)
    {
        $article = Article::with(['category', 'user'])->findOrFail($id);
        return view('frontpage.show', [
            'article' => $article
        ]);
    }
}
