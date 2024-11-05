@extends('layouts.admin')

@section('title', 'Edit Article')


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .toolbar-separator {
        display: inline-block;
        width: 1px;
        height: 24px;
        background: #dee2e6;
        vertical-align: middle;
    }
    
    .editor-container {
        margin-bottom: 1rem;
    }

    .editor-content {
        min-height: 300px;
        max-height: 600px;
        overflow-y: auto;
    }

    .form-control-color {
        width: 50px;
    }

    /* Header styles */
    .editor-content h1 { font-size: 2.5em; font-weight: bold; margin-top: 1em; }
    .editor-content h2 { font-size: 2em; font-weight: bold; margin-top: 0.8em; }
    .editor-content h3 { font-size: 1.75em; font-weight: bold; margin-top: 0.6em; }
    .editor-content h4 { font-size: 1.5em; font-weight: bold; margin-top: 0.4em; }
    .editor-content h5 { font-size: 1.25em; font-weight: bold; margin-top: 0.3em; }
    .editor-content h6 { font-size: 1.1em; font-weight: bold; margin-top: 0.2em; }
</style>
@endpush


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" 
                                   name="title" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $article->title) }}" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Content</label>
                            <div id="editor-container" class="editor-container">
                            </div>
                            <textarea id="original-content" style="display: none;">{{ $article->content }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label>Current Image</label>
                            @if($article->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($article->image) }}" 
                                         alt="Current Image" 
                                         style="max-width: 200px" 
                                         class="img-thumbnail">
                                </div>
                            @endif
                            <input type="file" 
                                   name="image" 
                                   class="form-control @error('image') is-invalid @enderror">
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>
                                <option value="published" {{ $article->status == 'published' ? 'selected' : '' }}>
                                    Published
                                </option>
                            </select>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   name="featured" 
                                   value="1" 
                                   {{ $article->featured ? 'checked' : '' }}>
                            <label class="form-check-label">Featured Article</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Article</button>
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/custom-editor.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize editor
        window.editor = new CustomEditor('editor-container');
        
        // Get the original content
        const originalContent = document.getElementById('original-content').value;
        
        // Set the content in the editor
        if (originalContent) {
            window.editor.setContent(originalContent);
        }
        
        // Update hidden textarea when form is submitted
        document.querySelector('form').addEventListener('submit', function() {
            const content = window.editor.getContent();
            document.getElementById('original-content').value = content;
        });
    });
</script>
@endpush