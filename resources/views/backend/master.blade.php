<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-nav-style="menu-hover" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close" loader="disable">


<!-- Mirrored from laravelui.spruko.com/valex/index by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 31 Jan 2026 01:10:49 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Laravel Bootstrap Responsive Admin Web Dashboard Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="laravel, framework laravel, laravel template, admin, laravel dashboard, template dashboard, admin dashboard ui, bootstrap dashboard, laravel framework, vite laravel, bootstrap 5 templates, laravel admin panel, laravel tailwind, admin panel, template admin, bootstrap admin panel.">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- TITLE -->
    <title> Valex - @yield('title') </title>

    @include('backend.includes.assets.style')
</head>

<body class="">

<!-- Switcher -->
@include('backend.includes.switcher')
<!-- End switcher -->

<!-- Loader -->
<div id="loader" >
    <img src="{{ asset('/') }}backend/build/assets/images/media/loader.svg" alt="">
</div>
<!-- Loader -->

<div class="page">

    <!-- Main-Header -->
    @include('backend.includes.header')
    <!-- End Main-Header -->

    <!-- Country-selector modal -->
    <!-- Start::Off-canvas sidebar-->
{{--    @include('backend.includes.notification-right-side')--}}
    <!-- End::Off-canvas sidebar-->

    <!-- End Country-selector modal -->

    <!--Main-Sidebar-->
    @include('backend.includes.menu')

    <!-- End Main-Sidebar-->

    <!-- Start::app-content -->
    <div class="main-content app-content">
        @yield('body')
    </div>
    <!-- End::content  -->

    <!-- Footer opened -->
    @include('backend.includes.footer')
    <!-- End Footer -->



</div>

<!-- Modals -->
@yield('modal')

<!-- SCRIPTS -->
<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow"><i class="las la-angle-double-up"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->

@include('backend.includes.assets.script')
</body>


</html>
