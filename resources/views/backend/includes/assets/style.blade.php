<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('/') }}backend/build/assets/images/brand-logos/favicon.ico">

<!-- Main Theme Js -->
<script src="{{ asset('/') }}backend/build/assets/main.js"></script>

<!-- ICONS CSS -->
<link href="{{ asset('/') }}backend/build/assets/icon-fonts/icons.css" rel="stylesheet">

<!-- Choices JS -->
<script src="{{ asset('/') }}backend/build/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- Bootstrap Css -->
<link id="style" href="{{ asset('/') }}backend/build/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

<!-- Node Waves Css -->
<link href="{{ asset('/') }}backend/build/assets/libs/node-waves/waves.min.css" rel="stylesheet" >

<!-- Simplebar Css -->
<link href="{{ asset('/') }}backend/build/assets/libs/simplebar/simplebar.min.css" rel="stylesheet" >

<!-- Color Picker Css -->
<link rel="stylesheet" href="{{ asset('/') }}backend/build/assets/libs/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('/') }}backend/build/assets/libs/%40simonwep/pickr/themes/nano.min.css">

<!-- Choices Css -->
<link rel="stylesheet" href="{{ asset('/') }}backend/build/assets/libs/choices.js/public/assets/styles/choices.min.css">
<!-- APP CSS & APP SCSS -->
<link rel="preload" as="style" href="{{ asset('/') }}backend/build/assets/app-DBELQW1b.css" />
<link rel="stylesheet" href="{{ asset('/') }}backend/build/assets/app-DBELQW1b.css" />

<!-- Helper css -->
<link rel="stylesheet" href="{{ asset('/backend/build/assets/libs/helper/helper.min.css') }}">

<style>
    [data-nav-layout="horizontal"] .main-sidebar {
        display: flex !important;
        justify-content: center;
    }
    [data-nav-layout="horizontal"] .main-menu-container {
        display: inline-flex;
    }
    [data-nav-layout="horizontal"] .main-menu {
        justify-content: center;
        margin-left: auto !important;
        margin-right: auto !important;
    }
</style>

@yield('styles')
@stack('styles')
