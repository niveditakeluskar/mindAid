<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style>
        .button {
            display: inline-block;
            background-color: #007bff; /* Button color */
            color: #fff; /* Text color */
            padding: 10px 20px; /* Padding */
            text-decoration: none; /* Remove underline */
            border-radius: 5px; /* Rounded corners */
        }
    </style>
</head>
<body>
    <h4>{{ $mailData['title'] }}</h4>

    <h5>{{ $mailData['body'] }}</h5>
    <p>{{ $mailData['message']}}</p>
    <a href="{{ $mailData['button_url'] }}" class="button">{{ $mailData['button_text'] }}</a>
    <a>{{ $mailData['link'] }}</a>
</body>
</html>