<!DOCTYPE html>
<html lang="en">
@include('admin.layout.head')
<body oncopy="return false;" class="hold-transition text-sm sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

    <!-- Preloader -->
{{--    <div class="preloader flex-column justify-content-center align-items-center">--}}
{{--        <img class="animation__wobble" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">--}}
{{--    </div>--}}

<!-- Navbar -->
@include('admin.layout.nav')
<!-- /.navbar -->

    <!-- Main Sidebar Container -->
@include('admin.layout.sidebar')

<!-- Content Wrapper. Contains page content -->
    @yield('content')
    @yield('code')
    @include('admin.layout.footer')
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    @include('admin.layout.control-sidebar')
<!-- /.control-sidebar -->

    <div class="modal fade imageModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="image-holder">
                    <a target="_blank" class="img-link">
                        <img class="img-show" style="max-height: 100vh;" width="100%">
                    </a>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- ./wrapper -->
@include('admin.layout.script')
</body>
</html>
