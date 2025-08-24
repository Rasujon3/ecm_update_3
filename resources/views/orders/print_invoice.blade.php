<!Doctype html>
<html>
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
 <body>
 	 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="printarea">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Invoice</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Invoice</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> {{$order->domain->shop_name}}
                    <small class="float-right">Date: {{$order->created_at->format('d F Y')}}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From
                  <address>
                    <strong>{{$order->customer_name}}</strong><br>
                    {{$order->customer_address}}<br>
                    Phone: {{$order->customer_phone}}
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Invoice #Order00{{$order->id}}</b><br>
                  <b>Payment Method: {{$order->payment_method}}</b><br>
                  <b>Transaction Hash: {{$order->transaction_hash}}</b><br>
                  <br>
                  <b>Order ID:</b> {{$order->courier?$order->courier->consignment_id:$order->id}}<br>
                  <b>Order Note:</b> {{$order->order_note}}<br>
                  <b>Order Status:</b> {{$order->status}}<br>

                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>SL</th>
                      <th>Product</th>
                      <th>Price</th>
                      <th>Qty</th>
                      <th>Unit Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->orders as $key=>$item)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$item->product->product_name}} @if($item->variant_id != null) ({{$item->variant->variant_name}}: {{$item->variant->variant_value}}) @endif</td>
                      <td>{{$item->product_price}}</td>
                      <td>{{$item->qty}}</td>
                      <td>{{$item->unit_total}} BDT</td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead font-weight-bold">Order Summary</p>

                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>{{$order->sub_total}} BDT</td>
                      </tr>

                      <tr>
                        <th style="width:50%">Delivery Charge:</th>
                        @if($order->delivery_charge != NULL)
                         <td>{{$order->delivery_charge}} %</td>
                        @else
                         <td>Free</td>
                        @endif
                      </tr>

                      <tr>
                        <th style="width:50%">Discount:</th>
                        @if($order->discount != NULL)
                         <td>{{$order->discount}} %</td>
                        @else
                         <td>No Discount</td>
                        @endif
                      </tr>

                      <tr>
                        <th>Total:</th>
                        <td>{{$order->total}} BDT</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 </body>
</html>


<script>
  myFunction();
  window.onafterprint = function(e){
     window.close()
  };
  function myFunction(){
    window.print();
  }
  function closePrintView()
  {
     //window.location.href="all-product_super_show"; 
     window.close()
     
  }
</script>

