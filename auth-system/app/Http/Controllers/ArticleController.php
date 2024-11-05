<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ArticleController extends Controller
{

    public function destroy(Article $article)
    {
        // Check if user is authorized to delete this article
        if ($article->user_id !== Auth::id()) {
            return redirect()
                ->route('articles.index')
                ->with('error', 'You are not authorized to delete this article.');
        }

        try {
            // Delete the image from storage if it exists
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            // Delete the article
            $article->delete();

            return redirect()
                ->route('articles.index')
                ->with('success', 'Article deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('articles.index')
                ->with('error', 'Error deleting article. Please try again.');
        }
    }

    public function show(Article $article)
    {
        // Check if the article belongs to the authenticated user
        if ($article->user_id !== Auth::id()) {
            return redirect()
                ->route('articles.index')
                ->with('error', 'You are not authorized to view this article.');
        }

        return view('articles.show', compact('article'));
    }




    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'featured' => $request->has('featured'),
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $data['image'] = $imagePath;
        }

        Article::create($data);

        return redirect()->route('articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')
                ->with('error', 'Unauthorized action.');
        }
        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }


    public function index()
    {
        $articles = Article::with(['user', 'category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('articles.index', compact('articles'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')
                ->with('error', 'Unauthorized action.');
        }
    
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'content' => $request->content,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'featured' => $request->has('featured'),
        ];
    
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }
    
        $article->update($data);
    
        return redirect()
            ->route('articles.index')
            ->with('success', 'Article updated successfully.');
    }
}