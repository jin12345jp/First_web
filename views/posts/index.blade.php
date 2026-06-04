<!DOCTYPE html>
<html>
<head>
    <title>投稿一覧</title>
</head>
<body>
    <h1>投稿一覧</h1>
    <ul>
        @foreach ($posts as $post)
            <li>{{ $post->title }}</li>
        @endforeach
    </ul>
</body>
</html>