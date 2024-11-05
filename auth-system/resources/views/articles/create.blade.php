<!DOCTYPE html>
<html>
<head>
    <title>Create Article</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add these lines in the head section -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/custom-editor.css">
    <style>
        /* Existing styles */
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
</head>    
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New Article</div>
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

                        <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Replace the old content textarea with this -->
                            <div class="mb-3">
                                <label>Content</label>
                                <div id="editor-container" class="editor-container"></div>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Featured Image</label>
                                <input type="file" class="form-control" name="image">
                            </div>

                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                                <label class="form-check-label">Featured Article</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Article</button>
                            <a href="{{ route('articles.index') }}" class="btn btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Add these scripts at the bottom of the body -->
    <script src="/js/custom-editor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.editor = new CustomEditor('editor-container');
        });
    </script>
</body>
</html>