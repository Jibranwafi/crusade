@extends('layouts.public')

@section('title', $article->title)

@push('styles')
<style>
    /* Article Typography and Spacing */
    .article-content {
        font-family: var(--bs-body-font-family);
        line-height: 1.6;
        color: var(--bs-body-color);
    }

    /* Headers and Subheaders */
    .article-content h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 2rem 0 1rem;
        color: #2c3e50;
        line-height: 1.2;
    }

    .article-content h2 {
        font-size: 2rem;
        font-weight: 600;
        margin: 1.8rem 0 1rem;
        color: #34495e;
        line-height: 1.3;
    }

    .article-content h3 {
        font-size: 1.75rem;
        font-weight: 600;
        margin: 1.6rem 0 1rem;
        color: #2c3e50;
        line-height: 1.4;
    }

    /* Subheaders */
    .article-content h4 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 1.4rem 0 0.8rem;
        color: #455a64;
        line-height: 1.4;
    }

    .article-content h5 {
        font-size: 1.25rem;
        font-weight: 500;
        margin: 1.2rem 0 0.8rem;
        color: #546e7a;
        line-height: 1.4;
    }

    .article-content h6 {
        font-size: 1.1rem;
        font-weight: 500;
        margin: 1rem 0 0.8rem;
        color: #607d8b;
        line-height: 1.4;
    }

    /* Paragraphs */
    .article-content p {
        font-size: 1.1rem;
        margin-bottom: 1.2rem;
        color: #444;
    }

    /* Lists */
    .article-content ul,
    .article-content ol {
        margin: 1rem 0;
        padding-left: 2rem;
    }

    .article-content li {
        margin-bottom: 0.5rem;
    }

    /* Images */
    .article-content img {
        max-width: 100%;
        height: auto;
        margin: 1.5rem 0;
        border-radius: 0.375rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }



    
    /* Tables */
    .article-content table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
        background-color: white;
    }

    .article-content table th,
    .article-content table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        min-width: 100px;
    }

    .article-content table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-align: left;
        border-bottom: 2px solid #dee2e6;
    }

    .article-content table tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .article-content table tr:hover {
        background-color: #f2f2f2;
    }

    /* Make tables responsive */
    @media (max-width: 768px) {
        .article-content table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }

    
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <article>
                <h1 class="mb-4">{{ $article->title }}</h1>
                
                <div class="article-meta text-muted mb-4">
                    <div class="d-flex gap-3">
                        <span>
                            <i class="bi bi-person"></i> {{ $article->user->name }}
                        </span>
                        <span>
                            <i class="bi bi-calendar"></i> {{ $article->created_at->format('F d, Y') }}
                        </span>
                        <span>
                            <i class="bi bi-folder"></i> {{ $article->category->name }}
                        </span>
                        <span>
                            <i class="bi bi-eye"></i> {{ $article->views_count }} views
                        </span>
                    </div>
                </div>

                @if($article->image)
                    <img src="{{ Storage::url($article->image) }}" 
                         alt="{{ $article->title }}" 
                         class="img-fluid rounded mb-4 featured-image">
                @endif

                <div class="article-content">
                    {!! $article->clean_content !!}
                </div>
            </article>

            <div class="mt-5">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Articles
                </a>
            </div>
        </div>
    </div>
</div>
@endsection