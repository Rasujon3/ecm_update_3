@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/products')}}">All Slider
                                </a></li>
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Product</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Name <span class="required">*</span></label>
                                <input type="text" name="product_name" class="form-control" id="product_name"
                                    placeholder="Product Name" required="" value="{{old('product_name')}}">
                                @error('product_name')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_price">Product Price (BDT) <span class="required">*</span></label>
                                <input type="text" name="product_price" class="form-control numericInput" id="product_price"
                                    placeholder="Product Price" required="" value="{{old('product_price')}}">
                                @error('product_price')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount">Discount (%)</label>
                                <input type="text" name="discount" class="form-control numericInput" id="discount"
                                    placeholder="Discount" value="{{old('discount')}}">
                                @error('discount')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_qty">Stock Quantity</label>
                                <input type="text" name="stock_qty" class="form-control numericInput" id="stock_qty"
                                    placeholder="Stock Quantity" value="{{old('stock_qty')}}">
                                @error('stock_qty')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit_id">Select Product Unit <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="unit_id" id="unit_id" required="">
                                    <option value="" selected="" disabled="">Select Product Unit</option>
                                    @foreach(units() as $unit)
                                     <option value="{{$unit->id}}">{{$unit->title}}</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                                <button type="button" class="btn btn-success add-unit my-2"><i class="fa fa-plus"></i> Add New Unit</button>
                            </div>

                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Select Status <span class="required">*</span></label>
                                <input type="text" class="form-control" name="status" id="status" readonly="" placeholder="Status" value="Inactive" required="">
                                @error('status')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_price">Discount Price (BDT) <span class="required">*</span></label>
                                <input type="text" name="discount_price" class="form-control numericInput" id="discount_price"
                                       placeholder="Discount Price" required="" value="{{old('discount_price')}}">
                                @error('discount_price')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fake_stocks">Fake Stocks for customer view </label>
                                <input type="text" name="fake_stocks" class="form-control numericInput" id="fake_stocks"
                                       placeholder="Fake Stocks for customer view" value="{{old('fake_stocks')}}">
                                @error('fake_stocks')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="description">Description <span class="required">*</span></label>
                            <textarea class="form-control description" name="description">{!!old('description')!!}</textarea>
                            @error('description')
                                <span class="alert alert-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Gallery Images <span class="required">*</span></label>
                                <div class="drop-container">
                                    <label for="file-input" class="upload-button">Upload Images</label>
                                    <div class="preview-images" id="preview-container"></div>
                                    <input type="file" class="form-control" name="gallery_images[]" id="file-input" multiple>
                                </div>
                                @error('gallery_images')
                                 <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-primary">Next Step</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </section>
</div>


<div class="modal" id="addUnitModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Unit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="unitModalForm">
          <div class="form-group">
            <label for="unit_title">Title <span class="required">*</span></label>
            <input type="text" class="form-control" id="unit_title" placeholder="Title" required="">
          </div>
          <input type="hidden" id="unit_status" value="Active"/>
          <div class="form-group">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

  <script src="{{asset('custom/multiple_files.js')}}"></script>

  <script>
    $(document).ready(function(){
        $(document).on('click', '.add-unit', function(e){
            e.preventDefault();
            $('#addUnitModal').modal('show');
        });

        $(document).on('submit', '#unitModalForm', function(e){
            e.preventDefault();
            $('#unit_id').html('');
            let user_id = "{{user()->id}}";
            let domain_id = "{{getDomain()->id}}";
            let title = $('#unit_title').val();
            let status = $('#unit_status').val();
            $.ajax({

                url: "{{url('/store-unit')}}",

                     type:"POST",
                     data:{'user_id':user_id, 'domain_id':domain_id, 'title':title, 'status':status},
                     dataType:"json",
                     success:function(data) {
                        console.log(data);
                        let html = `<option value="" selected="" disabled="">Select Unit</option>`;
                        $(data.units).each(function(idx,val){
                            let selectedUnit = val.id == data.unit_id?"selected":'';
                            html+=`<option value="${val.id}" ${selectedUnit}>${val.title}</option>`
                        });
                        $('#unit_id').append(html);
                        toastr.success(data.message);

                        $('#addUnitModal').modal('hide');

                },

            });
        });
    });
  </script>

@endpush
