@extends('admin_master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{URL::to('/orders')}}">My Orders</a></li>
                            <li class="breadcrumb-item active">Products</li>
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
