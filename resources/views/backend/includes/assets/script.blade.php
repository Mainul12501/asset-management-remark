<!-- Jquery -->
<script src="{{ asset('/backend/build/assets/libs/jquery/jquery-4.0.0.min.js') }}"></script>

<script>
    const base_url = "{!! url('/') !!}/";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<!-- Popper JS -->
<script src="{{ asset('/') }}backend/build/assets/libs/%40popperjs/core/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('/') }}backend/build/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Node Waves JS-->
<script src="{{ asset('/') }}backend/build/assets/libs/node-waves/waves.min.js"></script>

<!-- Simplebar JS -->
<script src="{{ asset('/') }}backend/build/assets/libs/simplebar/simplebar.min.js"></script>
<link rel="modulepreload" href="{{ asset('/') }}backend/build/assets/simplebar-B35Aj-bA.js" />
<script type="module" src="{{ asset('/') }}backend/build/assets/simplebar-B35Aj-bA.js"></script>
<!-- Color Picker JS -->
<script src="{{ asset('/') }}backend/build/assets/libs/%40simonwep/pickr/pickr.es5.min.js"></script>

<!-- custom scripts -->
<script src="{{ asset('/backend/build/assets/libs/helper/script.js') }}"></script>

@yield('scripts')
@stack('scripts')


<!-- Sticky JS -->
<script src="{{ asset('/') }}backend/build/assets/sticky.js"></script>

<!-- Custom-Switcher JS -->
<link rel="modulepreload" href="{{ asset('/') }}backend/build/assets/custom-switcher-CDFJCGB8.js" />
<script type="module" src="{{ asset('/') }}backend/build/assets/custom-switcher-CDFJCGB8.js"></script>
<!-- APP JS-->
<link rel="modulepreload" href="{{ asset('/') }}backend/build/assets/app-ClKBXEo6.js" />
<script type="module" src="{{ asset('/') }}backend/build/assets/app-ClKBXEo6.js"></script>
<!-- Center horizontal menu -->
<script type="module">
    if (document.documentElement.getAttribute('data-nav-layout') === 'horizontal') {
        const menu = document.querySelector('.main-menu');
        if (menu) {
            const observer = new MutationObserver(() => {
                if (menu.style.marginLeft === '0px' || menu.style.marginRight === '0px') {
                    menu.style.marginLeft = 'auto';
                    menu.style.marginRight = 'auto';
                }
            });
            observer.observe(menu, { attributes: true, attributeFilter: ['style'] });
            menu.style.marginLeft = 'auto';
            menu.style.marginRight = 'auto';
        }
    }
</script>
<!-- END SCRIPTS -->
