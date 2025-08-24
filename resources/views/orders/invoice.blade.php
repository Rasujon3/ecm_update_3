@extends('admin_master')
@section('content')
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
                  <b>Payment Status: {!! !empty($order->transaction_hash) ? 'Paid' : 'Due' !!}</b><br>
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
                    <td>
                        {{ $item->product->product_name }}
                        @if($item->variantIds && count($item->variantIds) > 0)
                            <br/>
                            <small>(
                                {{ $item->variantIds->pluck('variant.variant_value')->implode(', ') }}
                                )</small>
                        @endif
                    </td>

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
                         <td>{{$order->delivery_charge}} BDT</td>
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

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="{{url('/print-invoice/'.$order->id)}}" rel="noopener" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Print</a>

                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection
