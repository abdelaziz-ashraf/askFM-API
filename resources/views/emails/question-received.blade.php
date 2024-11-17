<!DOCTYPE html>
<html>
<head>
    <title>New Question Notification</title>
</head>
<body>
    <h2>Hello!</h2>
    <p>You have received a new question from {{ $question->user->name }}:</p>
    <p>{{ $question->body }}</p>
</body>
</html>
