<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="ml-auto">
                <a href="{{ route('articles.index') }}" class="btn btn-primary me-2">View Articles</a>
                <a href="{{ route('articles.create') }}" class="btn btn-success me-2">Create New Article</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Sign Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Welcome to Your Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <div class="user-info mb-4">
                            <h5 class="card-title">User Information</h5>
                            <div class="mb-3">
                                <strong>Name:</strong> {{ Auth::user()->name }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong> {{ Auth::user()->email }}
                            </div>
                            <div class="mb-3">
                                <strong>Member Since:</strong> {{ Auth::user()->created_at->format('F d, Y') }}
                            </div>
                        </div>

                        <div class="article-actions text-center">
                            <h5 class="mb-4">Quick Actions</h5>
                            <div class="d-grid gap-3 col-md-8 mx-auto">
                                <a href="{{ route('articles.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus-circle"></i> Create New Article
                                </a>
                                <a href="{{ route('articles.index') }}" class="btn btn-info btn-lg">
                                    <i class="fas fa-list"></i> View All Articles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>