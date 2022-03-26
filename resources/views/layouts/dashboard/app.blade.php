<!DOCTYPE html>
<html dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@lang('site.app.title')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{--<!-- Bootstrap 3.3.7 -->--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/nprogress.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/selectize.default.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/selectize.bootstrap3.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/jodit.min.css') }}" />

    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/font-awesome-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/AdminLTE-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/fonts.css') }}">
        <!--<link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">-->
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/rtl.css') }}">

        <style>
            body, h1, h2, h3, h4, h5, h6 {
                font-family: 'Cairo', sans-serif !important;
            }
        </style>

    @else
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/AdminLTE.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('DataTables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('DataTables/dataTables.bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dashboard_files/css/style.css') }}">

    {{--<!-- jQuery 3 -->--}}
    <script src="{{ asset('dashboard_files/js/jquery.min.js') }}"></script>

    {{--noty--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/noty/noty.css') }}">
    <script src="{{ asset('dashboard_files/plugins/noty/noty.min.js') }}"></script>

    {{--morris--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/morris/morris.css') }}">

    {{--<!-- iCheck -->--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/icheck/all.css') }}">

    {{--html in  ie--}}
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php
$expiration_dt = Session::get('expiration_dt');

if(Session::has('expiration_before_month')){
    ?>
    <div class="expiration_msg alert bg-yellow text-center">
        <p><b><i class="fa fa-exclamation-triangle"></i> عزيزي <span> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}  </span>
                النظام سوف يتوقف بتاريخ <span> {{ Session::get('expiration_date') }} </span> لتجديد الاشتراك يرجى التواصل  على
            </b> <b>info@amnksa.com</b>.</p>
    </div>
<?php
} if(Session::has('expiration_dt')){
    ?>
    <div class="expiration_msg alert alert-danger text-center">
        <p><b><i class="fa fa-exclamation-triangle"></i> عزيزي <span> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}  </span> لقد انتهت صلاحية الاشتراك في البرنامج لتجديد الاشتراك يرجى التواصل  على</b> <b>info@amnksa.com</b>.</p>
    </div>
<?php } ?>
<div class="wrapper">
    <header class="main-header">

        {{--<!-- Logo -->--}}
        <a href="{{ asset('dashboard') }}/index" class="logo">
            {{--<!-- mini logo for sidebar mini 50x50 pixels -->--}}
            <span class="logo-mini"><b><b>@lang('site.app.side_title_lg')</b><b>@lang('site.app.side_title_sm')</span>
            <span class="logo-lg"><b>@lang('site.app.title_lg') </b>@lang('site.app.title_sm')</span>
        </a>

        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">


                    {{--<!-- Notifications: style can be found in dropdown.less -->--}}
                    <!--
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 10 notifications</li>
                            <li>
                                {{--<!-- inner menu: contains the actual data -->--}}
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">View all</a>
                            </li>
                        </ul>
                    </li>
                    -->
                    {{--<!-- Tasks: style can be found in dropdown.less -->--}}
                    <!--
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag-o"></i></a>
                        <ul class="dropdown-menu">
                            <li>
                                {{--<!-- inner menu: contains the actual data -->--}}

                                <ul class="menu">
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <li>
                                            <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                                {{ $properties['native'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                            </li>
                        </ul>
                    </li>

                    -->
                    {{--<!-- User Account: style can be found in dropdown.less -->--}}
                        <ul class="nav navbar-nav navbar-right" style="margin-left: 10px">
                            <li><a href="{{ route('dashboard.index') }}" style="    padding: 10px;
    font-size: 30px;"><i class="fa fa-home"></i></a></li>

                        @if(auth()->user()->can('companies_read'))
                                <li><a href="{{ route('dashboard.companies.show', 1) }}">{{ session('company.company_name_ar') }}</a></li>
                        @endif
                        </ul>

                    @if(auth()->user()->id == 1)
                        <!--<li>
                            <a href="#" onclick="reset('{{ route('dashboard.reset') }}')" class="btn btn-danger btn_reset"><i class="fa fa-refresh"></i> تصفير القاعدة</a>
                        </li>-->
                        @endif
                    @if(auth()->user())
                    <li class="dropdown user user-menu">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('img/avatar/0.jpg') }}" class="user-image" alt="User Image">

                            <span class="hidden-xs">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                        </a>
                        <ul class="dropdown-menu">

                            {{--<!-- User image -->--}}
                            <li class="user-header">
                                <img src="{{ asset('img/avatar/0.jpg') }}" class="img-circle" alt="User Image">

                                <p>
                                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                                    <small> </small>
                                </p>
                            </li>
                            @if(auth()->user()->can('companies_read'))
                            <li>
                                <div class="">
                                    <a href="{{ route('dashboard.companies.show', 1) }}" class="btn btn-info btn-block" style="border-radius: 0"><i class="fa fa-university"></i> بروفايل الشركة</a>
                                </div>
                            </li>
                            @endif
                            {{--<!-- Menu Footer-->--}}
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}" class="btn btn-danger" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> @lang('site.logout')</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                                <div class="pull-left">
                                    @if(auth()->user()->seller_id == 0)
                                    <a href="{{ route('dashboard.users.show', auth()->id()) }}" class="btn btn-warning"><i class="fa fa-user"></i> البروفايل</a>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>

    </header>
    @if(auth()->user())
        @include('layouts.dashboard._aside')
    @endif

    @yield('content')

    @if(auth()->user())
        @include('partials._session')
    @endif

    <footer class="main-footer">

        <strong>Copyright &copy; {{ date('Y') }} - @lang('site.app.copyright') كل الحقوق محفوظة. </strong>

    </footer>
    <div id="overlay"></div>

</div><!-- end of wrapper -->

{{--<!-- Bootstrap 3.3.7 -->--}}
<script src="{{ asset('dashboard_files/js/popper.min.js') }}"></script>
<script src="{{ asset('dashboard_files/js/bootstrap.min.js') }}"></script>

{{--icheck--}}
<script src="{{ asset('dashboard_files/plugins/icheck/icheck.min.js') }}"></script>

{{--<!-- FastClick -->--}}
<script src="{{ asset('dashboard_files/js/fastclick.js') }}"></script>

{{--<!-- AdminLTE App -->--}}
<script src="{{ asset('dashboard_files/js/adminlte.min.js') }}"></script>

{{--ckeditor standard--}}
<script src="{{ asset('dashboard_files/plugins/ckeditor/ckeditor.js') }}"></script>

{{--jquery number--}}
<script src="{{ asset('dashboard_files/js/jquery.number.min.js') }}"></script>

{{--print this--}}
<script src="{{ asset('dashboard_files/js/printThis.js') }}"></script>

{{--morris --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('dashboard_files/plugins/morris/morris.min.js') }}"></script>

{{--custom js--}}
<script src="{{ asset('dashboard_files/js/custom/image_preview.js') }}"></script>
<script src="{{ asset('dashboard_files/js/custom/order.js') }}"></script>
<script type="text/javascript" charset="utf8" src="{{ asset('DataTables/datatables.js') }}"></script>
<script type="text/javascript" charset="utf8" src="{{ asset('DataTables/dataTables.bootstrap.min.js') }}"></script>

<script src="{{ asset('dashboard_files/js/nprogress.js') }}"></script>
<script src="{{ asset('dashboard_files/js/sifter.min.js') }}"></script>
<script src="{{ asset('dashboard_files/js/microplugin.min.js') }}"></script>
<script src="{{ asset('dashboard_files/js/selectize.min.js') }}"></script>
<script src="{{ asset('js/ckeditor/build/ckeditor.js') }}"></script>
<script src="{{ asset('js/jodit.min.js') }}"></script>
<script src="{{ asset('js/functions.js') }}"></script>

<script>
    $(document).ready(function () {

        $('.sidebar-menu').tree();

        //icheck
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        //delete
        $('.delete').click(function (e) {

            var that = $(this)

            e.preventDefault();

            var n = new Noty({
                text: "@lang('site.confirm_delete')",
                type: "warning",
                killer: true,
                buttons: [
                    Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                        that.closest('form').submit();
                    }),

                    Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                        n.close();
                    })
                ]
            });

            n.show();

        });//end of delete

        // // image preview
        // $(".image").change(function () {
        //
        //     if (this.files && this.files[0]) {
        //         var reader = new FileReader();
        //
        //         reader.onload = function (e) {
        //             $('.image-preview').attr('src', e.target.result);
        //         }
        //
        //         reader.readAsDataURL(this.files[0]);
        //     }
        //
        // });

        //CKEDITOR.config.language =  "{{ app()->getLocale() }}";

        /*ClassicEditor
            .create( document.querySelector( '#editor' ))
            .catch( error => {
                console.error( error );
            } );*/
/*
        ClassicEditor
            .create( document.querySelector( '#editor' ), {
                toolbar: {

                    items: [
                        'heading',
                        'link',
                        '|',
                        'bold',
                        'italic',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'outdent',
                        'indent',
                        '|',
                        'undo',
                        'redo',
                        'alignment',
                        'fontSize',
                        'links'
                    ]

                },
                language: 'ar',
                licenseKey: '',


            } )

            .then( editor => {
                window.editor = editor;



            } )
            .catch( error => {
                console.error( 'Oops, something went wrong!' );
                console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                console.warn( 'Build id: ristmkvxph8z-h7dwxdozag64' );
                console.error( error );
            } );
*/


        var editor = new Jodit('#editor');


    });//end of ready

</script>
@stack('scripts')
</body>
</html>
