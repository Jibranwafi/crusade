<!DOCTYPE html>
<html>
<head>
    <title>My Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>My Articles</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('articles.create') }}" class="btn btn-primary">Create New Article</a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Created</th>
                                <th>Views</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($articles as $article)
        <tr>
                                <td>
                                    {{ $article->title }}
                                    @if($article->image)
                                        <small class="text-muted"><i class="bi bi-image"></i></small>
                                    @endif
                                </td>
                                <td>
                                    <!-- Add the excerpt here, limit it to avoid making the table too wide -->
                                    <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                        {!! $article->excerpt !!}
                                    </div>
                                </td>
                                <td>{{ $article->category->name }}</td>
                                <td>
                                        @if($article->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($article->featured)
                                            <span class="badge bg-primary">Featured</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $article->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $article->views_count }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('article.show', $article->slug) }}" 
                                               class="btn btn-sm btn-info me-1" 
                                               target="_blank">View</a>
                                            <a href="{{ route('articles.edit', $article) }}" 
                                               class="btn btn-sm btn-primary me-1">Edit</a>
                                            <form action="{{ route('articles.destroy', $article) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this article?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No articles found.</td>  <!-- Update colspan to 8 -->
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>