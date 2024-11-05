@extends('layouts.public')

@section('title', 'Welcome to Our Blog')

@section('content')
<div class="container py-5">
    <!-- Featured Articles Section -->
    @if($featured->count() > 0)
    <section class="mb-5">
        <h2 class="mb-4">Featured Articles</h2>
        <div class="row">
            @foreach($featured as $article)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($article->image)
                        <img src="{{ Storage::url($article->image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                            {{ $article->excerpt }}
                        </div>
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

    <!-- All Articles Section -->
    <section>
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-4">Latest Articles</h2>
                <div class="row">
                    @foreach($articles as $article)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            @if($article->image)
                                <img src="{{ Storage::url($article->image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge bg-primary">{{ $article->category->name }}</span>
                                </div>
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="card-text">
                                    {!! Str::limit(strip_tags($article->content), 150) !!}
                                </p>

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
                    @endforeach
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
                            @foreach($categories as $category)
                            <li class="mb-2">
                                <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none">
                                    {{ $category->name }} ({{ $category->articles_count }})
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
