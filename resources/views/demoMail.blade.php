<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
<body>
    <h4>{{ $mailData['title'] }}</h4> 
    <h5>{{ $mailData['body'] }}</h5>
    <p>{{ $mailData['message']}}</p>
    <a href="{{ $mailData['button_url'] }}" >{{ $mailData['button_text'] }}</a>
    <a>{{ $mailData['link'] }}</a>
</body>
</html>