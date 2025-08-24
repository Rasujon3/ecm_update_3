<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('back/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('back/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('back/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('back/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('back/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('back/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('back/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('back/plugins/summernote/summernote-bs4.min.css')}}">

  <link rel="stylesheet" href="{{asset('custom/style.css')}}">

  <link rel="stylesheet" href="{{asset('custom/toastr.css')}}">

    <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('back/plugins/select2/css/select2.min.css')}}">

  <link rel="stylesheet" href="{{asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

     <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{asset('back/datatable/css/dataTables.bootstrap4.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('back/datatable/css/buttons.dataTables.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('back/datatable/css/responsive.bootstrap4.min.css')}}">



    <link rel="stylesheet" href="{{asset('dropify/dist/css/dropify.min.css')}}">


</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


@php
 $userInfo = \App\Models\User::with('domain', 'package')
                ->where('id',Auth::user()->id)
                ->firstOrFail();
@endphp

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        @if(Auth::user()->status === 'Inactive')
                    <a
                        href="{{ route('redirect-demo-url') }}"
                        class="btn btn-warning add-new mb-2 ml-2"
                        target="_blank"
                    >
                        See Demo
                    </a>

                    <a
                        href="https://hosstify.com/confirmation?domain={{ $userInfo->domain?->domain }}&package_id={{$userInfo->domain?->package_id}}&user_id={{$userInfo->id}}"
                        class="btn btn-warning add-new mb-2 ml-2 d-none"
                        target="_blank"
                    >
                        Purchase
                    </a>

                @endif
        <a href="{{url('/logout')}}" class="btn btn-primary font-weight-bold">LOGOUT</a>

      </li>


    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

   @if(user()->role_id == 1)
    <a href="{{URL::to('/dashboard')}}" class="brand-link">
      <img src="{{asset('back/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Ecommerce Solution</span>
    </a>
    @else
    <!-- Brand Logo -->
    <a href="{{URL::to('/dashboard')}}" class="brand-link">
      <img src="{{URL::to(getDomain()->logo)}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{getDomain()->shop_name}}</span>
    </a>
   @endif

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{URL::to(user()->image)}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="{{URL::to('/dashboard')}}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard

              </p>
            </a>

          </li>


      @if(Auth::user()->status == 'Inactive')
      <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Slider
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('sliders.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Slider</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('sliders.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Slider</p>
                </a>
              </li>

            </ul>
          </li>
       <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Product Unit
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('units.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Unit</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('units.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Unit</p>
                </a>
              </li>

            </ul>
          </li>


          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Product
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('products.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('products.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Product</p>
                </a>
              </li>

            </ul>
          </li>



        <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>
                Reviews
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('reviews.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Review</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('reviews.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Review</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="{{url('/add-video')}}" class="nav-link">
              <i class="nav-icon fas fa-video"></i>
              <p>
                Video
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('why_choose_us.index') }}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Why Choose Us
              </p>
            </a>
          </li>

      @else
       @if(user()->role_id == 1)

      <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
            <p>
              Services
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('services.create')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Service</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('services.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>All Service</p>
              </a>
            </li>

          </ul>
        </li>

         <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Packages
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('packages.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Package</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('packages.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Package</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="{{url('/user-products')}}" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
               User Products
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('/users')}}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
               Users
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('/purchase-history') }}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                  Purchase history
              </p>
            </a>
          </li>
      @endif
        @if(user()->role_id == 2)
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Slider
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('sliders.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Slider</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('sliders.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Slider</p>
                </a>
              </li>

            </ul>
          </li>

           <li class="nav-item">
               <a href="{{ route('banner-text') }}" class="nav-link">
                   <i class="nav-icon fas fa-text-height"></i>
                   <p>
                       Banner Text
                   </p>
               </a>
           </li>

           <li class="nav-item">
               <a href="{{ route('conversions') }}" class="nav-link">
                   <i class="nav-icon fas fa-comments"></i>
                   <p>
                       Conversation
                   </p>
               </a>
           </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Product Unit
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('units.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Unit</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('units.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Unit</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Product
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('products.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('products.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Product</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
               <a href="#" class="nav-link">
                   <i class="nav-icon fas fa-list"></i>
                   <p>
                       Product Characteristics
                       <i class="fas fa-angle-left right"></i>
                   </p>
               </a>
               <ul class="nav nav-treeview">
                   <li class="nav-item">
                       <a href="{{ route('product-characteristics-title') }}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Title</p>
                       </a>
                   </li>
                   <li class="nav-item">
                       <a href="{{route('product_characteristics_details.index')}}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Details</p>
                       </a>
                   </li>

               </ul>
          </li>

          <li class="nav-item">
                   <a href="#" class="nav-link">
                       <i class="nav-icon fas fa-list"></i>
                       <p>
                           Product Narrative
                           <i class="fas fa-angle-left right"></i>
                       </p>
                   </a>
                   <ul class="nav nav-treeview">
                       <li class="nav-item">
                           <a href="{{ route('product-narrative-title') }}" class="nav-link">
                               <i class="far fa-circle nav-icon"></i>
                               <p>Title</p>
                           </a>
                       </li>
                       <li class="nav-item">
                           <a href="{{route('product_narrative_details.index')}}" class="nav-link">
                               <i class="far fa-circle nav-icon"></i>
                               <p>Details</p>
                           </a>
                       </li>

                   </ul>
               </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-map"></i>
              <p>
                Delivery Charge Area
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              {{--<li class="nav-item">
                <a href="{{route('ariadhakas.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Area</p>
                </a>
              </li>--}}
              <li class="nav-item">
                <a href="{{route('ariadhakas.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Area</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Expense
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('expenses.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Expense</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('expenses.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Expense</p>
                </a>
              </li>

            </ul>
          </li>


          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/sales-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/finance-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Finance Report</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>
                Reviews
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('reviews.create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Review</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('reviews.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Review</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="{{url('/add-video')}}" class="nav-link">
              <i class="nav-icon fas fa-video"></i>
              <p>
                Video
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('/orders')}}" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
               My Orders
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('/search-courier-order')}}" class="nav-link">
              <i class="nav-icon fas fa-search"></i>
              <p>
               Search Courier Order
              </p>
            </a>
          </li>
        @endif
          <li class="nav-header">Settings</li>


          @if(user()->role_id == 2)
          <li class="nav-item">
            <a href="{{url('/app-settings')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Delivery Partners
              </p>
            </a>
          </li>

          @endif


          @if(user()->role_id == 2)
          <li class="nav-item">
            <a href="{{url('/meta-pixel-settings')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Set Meta Pixel
              </p>
            </a>
          </li>

          @endif


          @if(user()->role_id == 2)
          <li class="nav-item d-none">
            <a href="{{url('/set-delivery-charge')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Set Delivery Charge
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('why_choose_us.index') }}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Why Choose Us
              </p>
            </a>
          </li>

          @endif

          @if(user()->role_id == 1)
          <li class="nav-item">
            <a href="{{url('/refer-settings')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Refer Setting
              </p>
            </a>
          </li>




          @endif


          <li class="nav-item">
            <a href="{{url('/social-media-settings')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Social Media Settings
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{url('/terms-conditions')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Terms & Conditions
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{url('/refund-policy')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Refund Policy
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{url('/info-settings')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Info Setting
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('/payment-info')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Payment Info
              </p>
            </a>
          </li>



          @if(user()->role_id == 1)
              <li class="nav-item">
                <a href="{{ route('banner.index') }}" class="nav-link">
                  <i class="nav-icon fas fa-cog"></i>
                  <p>
                    Banner
                  </p>
                </a>
              </li>
          @endif

          <li class="nav-item">
            <a href="{{url('/password-change')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Password Change
              </p>
            </a>
          </li>
      @endif

          {{-- <li class="nav-item">
            <a href="{{url('/profile-settings')}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Profile Settings
              </p>
            </a>
          </li> --}}

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

   @yield('content')


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('custom/custom_js.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('back/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('back/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>


<script src="{{asset('custom/custom.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('back/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('back/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('back/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('back/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('back/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('back/dist/js/adminlte.js')}}"></script>

<!-- Select2 -->
<script src="{{asset('back/plugins/select2/js/select2.full.min.js')}}"></script>

<!-- data-table js -->
<script src="{{asset('back/datatable/js/jquery.dataTables.min.js')}}"></script>

<script src="{{asset('back/datatable/js/dataTables.buttons.min.js')}}"></script>



<script src="{{asset('back/datatable/js/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{asset('back/datatable/js/dataTables.responsive.min.js')}}"></script>

<script src="{{asset('back/datatable/js/responsive.bootstrap4.min.js')}}"></script>

<script src="{{asset('back/datatable/js/data-table-custom.js')}}"></script>

<script src="{{asset('dropify/dist/js/dropify.min.js')}}"></script>


<script src="{{asset('custom/toastr.js')}}"></script>

  @if(Session::has('messege'))
    @toastr("{{ Session::get('messege') }}")
  @endif

@stack('scripts')
</body>
</html>
