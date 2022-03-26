<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@lang('site.app.title')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/print_style.css') }}">


</head>
<body>

    @yield('content')
    <!--<script src="{{ asset('dashboard_files/js/jquery.min.js') }}"></script>

<script>
    function toggleBtnPrint(checkbox){
        if($(checkbox).is(':checked')){
            $('.sinature').css('opacity', 1);
        } else {
            $('.sinature').css('opacity', 0);
        }
    }
</script>
->
</body>
</html>


