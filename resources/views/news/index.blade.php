<!DOCTYPE html>
<html>
<head>
    <title>Noticias de IA</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Noticias de IA</h1>
        @foreach($news as $article)
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="card-title">{{ $article['title'] }}</h2>
                    <p class="card-text">{{ $article['summary'] }}</p>
                    <a href="{{ $article['url'] }}" class="btn btn-primary">Ver artículo original</a>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
