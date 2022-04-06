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
        $category = $category_id ? Category::where('id', $category_id)->first() : null;

        $articles = Article::with('category')
            ->when($category_id, function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            })
            // trim each article content to 400 characters using eloquent raw query
            ->selectRaw('articles.*, SUBSTRING(articles.content, 1, 400) as content')
            ->latest()
            ->paginate(10);

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
