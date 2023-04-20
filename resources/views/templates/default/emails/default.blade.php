<!DOCTYPE html>
<html>
<head>
    <title> {{ env('APP_NAME') }}</title>
</head>
<body>



@if(isset($formContent))
    <ul>
    @foreach($formContent as  $label => $value)
        <li><b>{{ $label }}:</b> {{ $value }}</li>
    @endforeach
    </ul>
@endif
<p>Thank you</p>
</body>
</html>
