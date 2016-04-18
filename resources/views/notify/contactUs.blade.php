<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LMS</title>
</head>
<body>
<p>{{$body}}</p>
<p>User info:</p>
<p>First name: {{$firstName}}</p>
<p>Last name: {{$lastName}}</p>
<p>Email: {{$email}}</p>
@if ($haveMobilePhone)
<p>Mobile phone: {{$mobilePhone}}</p>
@endif
</body>
</html>
