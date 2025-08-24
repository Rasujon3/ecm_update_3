@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">User Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/products')}}">All Slider
                                </a></li>
                        <li class="breadcrumb-item active">User Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">User Product</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Name <span class="required">*</span></label>
                                <input type="text" name="product_name" class="form-control" id="product_name"
                                    placeholder="Product Name" required="" value="{{old('product_name',$product->product_name)}}" readonly="">
                                @error('product_name')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_price">Product Price (BDT) <span class="required">*</span></label>
                                <input type="text" name="product_price" class="form-control numericInput" id="product_price"
                                    placeholder="Product Price" required="" value="{{old('product_price',$product->product_price)}}" readonly="">
                                @error('product_price')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount">Discount (%)</label>
                                <input type="text" name="discount" class="form-control numericInput" id="discount"
                                    placeholder="Discount" value="{{old('discount',$product->discount)}}" readonly="">
                                @error('discount')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> 


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_qty">Stock Quantity <span class="required">*</span></label>
                                <input type="text" name="stock_qty" class="form-control numericInput" id="stock_qty"
                                    placeholder="Stock Quantity" value="{{old('stock_qty',$product->stock_qty)}}" required="" readonly="">
                                @error('stock_qty')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit_id">Product Unit<span class="required">*</span></label>
                                
                                  <input type="text" class="form-control" name="status" id="status" readonly="" placeholder="Product Unit" value="{{$product->unit->title}}" required="">
                          
                                @error('unit_id')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                         


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Select Status <span class="required">*</span></label>
                                <input type="text" class="form-control" name="status" id="status" readonly="" placeholder="Status" value="{{$product->status}}" required=""> 
                                @error('status')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="description">Description <span class="required">*</span></label>  
                            <textarea class="form-control description" readonly="" name="description">{!!old('description',$product->description)!!}</textarea>
                            @error('description')
                                <span class="alert alert-danger">{{ $message }}</span>
                            @enderror
                          </div>  
                        </div>


                        
                          <div class="col-md-12">
                          	<div class="form-group">
	                           <label>Images</label>
	                           <div class="row">
	                           	@foreach($product->images as $row)
		                           <div class="col-md-4">
		                          	<div class="card">
			                          	<div class="card-body">
			                          	 <a href="{{URL::to($row->image)}}" target="__blank">
			                              <img style="height: 200px;" src="{{URL::to($row->image)}}" class="img-fluid"/>	
			                             </a> 	
			                          	</div>
		                             </div>
		                          </div>	
		                          @endforeach
	                         </div>
	                        </div>
                          </div>
                                 
                        <div class="form-group w-100 px-2">
                            <button type="button" class="btn btn-warning text-light btn-block back-button">GO BACK</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
    
        </div>
    </section>
</div>
@endsection

@push('scripts')
 <script>
 	$(document).ready(function(){
 		$(document).on('click', '.back-button', function(){
 			window.history.back();
 		});
 	});
 </script>
@endpush