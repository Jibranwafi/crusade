@extends('layouts.public')

@section('title', $category->name . ' - Our Blog')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4">{{ $category->name }}</h1>

            @if($featured->count() > 0)
            <section class="mb-5">
                <h4 class="mb-4">Featured in {{ $category->name }}</h4>
                <div class="row">
                    @foreach($featured as $article)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($article->image)
                                <img src="{{ Storage::url($article->image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $article->title }}"
                                     style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="card-text">{{ Str::limit($article->excerpt ?? $article->content, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        {{ $article->created_at->diffForHumans() }}
                                    </small>
                                    <a href="{{ route('article.show', $article->slug) }}" class="btn btn-primary btn-sm">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <div class="row">
                @forelse($articles as $article)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        @if($article->image)
                            <img src="{{ Storage::url($article->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $article->title }}"
                                 style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ Str::limit($article->excerpt ?? $article->content, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    By {{ $article->user->name }}
                                </small>
                                <a href="{{ route('article.show', $article->slug) }}" class="btn btn-primary btn-sm">Read More</a>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            <small>{{ $article->created_at->diffForHumans() }} • {{ $article->views_count }} views</small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No articles found in this category.
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $articles->links() }}
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($categories as $cat)
                        <li class="mb-2">
                            <a href="{{ route('category.show', $cat->slug) }}" 
                               class="text-decoration-none {{ $cat->id === $category->id ? 'fw-bold text-primary' : '' }}">
                                {{ $cat->name }} ({{ $cat->articles_count }})
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Optional: Add back to all articles link -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">View All Articles</a>
            </div>
        </div>
    </div>
</div>
@endsection