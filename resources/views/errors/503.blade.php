<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>This site is currently not available</title>
    <style>
        .maintenance{
            display: flex;
            justify-content: center;
            justify-items: center;
            width: 100%;
        }
        .maintenance .msg{
            color: red;
        }

        .maintenance .link{
            color: blue;
        }
    </style>
</head>
<body>
    <div class="maintenance">
        <h1 class="msg">This site is currently not available. Please <a class="link" href="{{ config('app.url') }}">try again</a> later.</h1>
    </div>
</body>
</html>
