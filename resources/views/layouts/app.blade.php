@php
 $user_id = user()->id;
 $domain_id = getDomain()->id;
 $data = DB::selectOne("
    SELECT
        (SELECT COUNT(*) FROM sliders WHERE user_id=$user_id) AS totalSliders,
        (SELECT COUNT(*) FROM units WHERE user_id=$user_id) AS totalUnits,
        (SELECT COUNT(*) FROM products WHERE user_id=$user_id) AS totalProducts,
        (SELECT COUNT(*) FROM reviews WHERE user_id=$user_id) AS totalReviews,
        (SELECT COUNT(*) FROM orderdetails WHERE domain_id=$domain_id) AS totalOrders,
        (SELECT COUNT(*) FROM orderdetails WHERE DATE(created_at) = CURDATE() AND domain_id=$domain_id) AS todayOrders,
        (SELECT COUNT(*) FROM orderdetails WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND domain_id=$domain_id) AS monthlyOrders,
        (SELECT COUNT(*) FROM orderdetails WHERE YEAR(created_at) = YEAR(CURDATE()) AND domain_id=$domain_id) AS yearlyOrders
");
@endphp
@extends('admin_master')
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

     <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$data->totalSliders}}</h3>

                <p>Total Sliders</p>
              </div>


            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$data->totalProducts}}</h3>

                <p>Total Products</p>
              </div>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$data->totalUnits}}</h3>

                <p>Total Product Units</p>
              </div>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$data->totalReviews}}</h3>

                <p>Total Reviews</p>
              </div>
 
            </div>
          </div>
          <!-- ./col -->



          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$data->totalOrders}}</h3>

                <p>Total Orders</p>
              </div>


            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$data->todayOrders}}</h3>

                <p>Today Orders</p>
              </div>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$data->monthlyOrders}}</h3>

                <p>This month Orders</p>
              </div>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$data->yearlyOrders}}</h3>

                <p>This year orders</p>
              </div>
 
            </div>
          </div>
          <!-- ./col -->


        </div>
        <!-- /.row -->
       
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection