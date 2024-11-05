<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class PublicController extends Controller
{
    public function index()
    {
        $featured = Article::where('status', 'published')
            ->where('featured', true)
            ->latest()
            ->take(3)
            ->get();
            
        $articles = Article::where('status', 'published')
            ->latest()
            ->paginate(9);
            
        $categories = Category::withCount('articles')->get();
        
        return view('public.index', compact('featured', 'articles', 'categories'));
    }

    public function show(Article $article)
    {
        $article->increment('views_count');
        return view('public.show', compact('article'));
    }
    
    public function category(Category $category)
    {
        $articles = Article::where('status', 'published')
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(9);

        $categories = Category::withCount(['articles' => function($query) {
            $query->where('status', 'published');
        }])->get();

        $featured = Article::where('status', 'published')
            ->where('category_id', $category->id)
            ->where('featured', true)
            ->latest()
            ->take(3)
            ->get();

        return view('public.category', compact('category', 'articles', 'categories', 'featured'));
    }
}