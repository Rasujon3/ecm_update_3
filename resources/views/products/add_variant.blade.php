@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Variant</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/products')}}">All Products
                                </a></li>
                        <li class="breadcrumb-item active">Add Variant</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Variant</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="variantSubmit">
                <div class="card-body">
                	<input type="hidden" name="product_id" id="product_id" value="{{$product->id}}">
                    <div class="row">
                        <div class="col-md-12 body-conts">

                          
                        @if(count($product->variants) > 0)

                        @foreach($variantVals as $key=>$row)
                         <div class="card" id="colIndex_{{$row['variant_id']}}">
                            <div class="card-header">
                              <button type="button" class="btn btn-danger float-left delete-full-variant" data-id="{{$row['variant_id']}}">Delete</button>
                              <button type="button" class="btn btn-info text-light float-right add-new-variant" id="add-new-variant">Add New Variant</button>
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <div class="form-group">
                            <label for="variant_name">Variant Name</label>
                            <input type="text" class="form-control" name="variant_name[]" id="variant_name" placeholder="Variant Name" value="{{$row['variant_name']}}">
                             </div>
                             
                            <table class="table table-bordered table-striped">
                              <thead>
                                <th>Variant Value</th>
                                <th>Action</th>
                              </thead>  
                              <tbody id="contents" class="rowIndex_{{$row['variant_id']}}">
                              @foreach($row['variant_values'] as $value)
                               <tr id="{{$value['id']}}"> 
                                 <td>
                                  <input type="text" class="form-control" name="variant_value[]" placeholder="Variant Value" value="{{$value['value']}}"/> 
                                 </td>  

                                  <td>
                                   <button type="button" class="btn btn-danger btn-sm delete-variant" data-id="{{$value['id']}}"><i class="fa fa-trash"></i></button> 
                                 </td>

                                </tr>
                              @endforeach
                              </tbody>  
                            </table>
                            <button type="button" class="btn btn-secondary btn-block add-variant my-2" data-id="{{$row['variant_id']}}"><i class="fa fa-plus"></i> Add More</button>
                          </div>
                            </div>
                          </div>
                        @endforeach
                        @else
                          <div class="card">
                            <div class="card-header">
                              <button type="button" class="btn btn-info text-light float-right add-new-variant" id="add-new-variant">Add New Variant</button>
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <div class="form-group">
                            <label for="variant_name">Variant Name</label>
                            <input type="text" class="form-control" name="variant_name[]" id="variant_name" placeholder="Variant Name">
                             </div>
                             
                            <table class="table table-bordered table-striped">
                              <thead>
                                <th>Variant Value</th>
                                <th>Action</th>
                              </thead>  
                              <tbody id="contents" class="rowIndex_1">
                               <tr> 
                                 <td>
                                  <input type="text" class="form-control" name="variant_value[]" placeholder="Variant Value" /> 
                                 </td>  

                                  <td>
                                   <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button> 
                                 </td>

                                </tr>
                              </tbody>  
                            </table>
                            <button type="button" class="btn btn-secondary btn-block add-variant my-2" data-id="1"><i class="fa fa-plus"></i> Add More</button>
                          </div>
                            </div>
                          </div>
                        @endif



                        </div>

                        
                        <div class="form-group w-100 px-2">
                          
                            <button type="submit" class="btn btn-success btn-block">Submit</button>
                            <button type="button" class="btn btn-warning btn-block text-light go-back-products">Go Back</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')


  <script>
  	$(document).ready(function(){

    let rowIndex = {{count($variantVals)}};


    $(document).on('submit','#variantSubmit',function(e){
        e.preventDefault();

        let variants = {}; // this will hold grouped data
        let redirectURL = "{{url('/products')}}";
        $('.card-body').each(function() {
            let variantName = $(this).find("input[name='variant_name[]']").val().trim();

            if (variantName === '') return; // skip if no name

            variants[variantName] = [];

            $(this).find("input[name='variant_value[]']").each(function() {
                let value = $(this).val().trim();
                if (value !== '') {
                    variants[variantName].push(value);
                }
            });
        });


        $.ajax({
            url: "{{url('/store-variants')}}",
            type: "POST",
            dataType:"json",
            data: {
                product_id: $("input[name='product_id']").val(),
                variants: variants
            },
            success: function(response) {
                console.log(response);
                toastr.success(response.message);
                setTimeout(function() {
                    window.location.href = redirectURL;
                }, 1000);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                // toastr.error('Something went wrong');
            }
        });
    });


    
    $(document).on('click', '.add-new-variant', function(){
      rowIndex++; 
      let colIndex = Date.now();
      colIndex+=rowIndex;                                                      
      let htmlTags = `<div class="card" id="colIndex_${colIndex}">
                            <div class="card-header">
                              <button type="button" class="btn btn-danger float-left remove-full-variant" data-id="${colIndex}">Delete</button>
                              <button type="button" class="btn btn-info text-light float-right add-new-variant" id="add-new-variant">Add New Variant</button>
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <div class="form-group">
                            <label for="variant_name">Variant Name</label>
                            <input type="text" class="form-control" name="variant_name[]" placeholder="Variant Name">
                             </div>
                           
                            <table class="table table-bordered table-striped">
                              <thead>
                                <th>Variant Value</th>
                                <th>Action</th>
                              </thead>  
                              <tbody id="contents" class="rowIndex_${rowIndex}">
                                <tr>
                                  <td>
                                      <input type="text" class="form-control" name="variant_value[]" placeholder="Variant Value" />
                                  </td>
                                  <td>
                                      <button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-trash"></i></button>
                                  </td>
                              </tr>
                              </tbody> 
                            </table>
                            <button type="button" class="btn btn-secondary btn-block add-variant my-2" data-id="${rowIndex}"><i class="fa fa-plus"></i> Add More</button>
                          </div>
                            </div>
                          </div>`;

      $('.body-conts').append(htmlTags);
    });


    $(document).on('click', '.remove-full-variant', function(e){
       e.preventDefault();
       let fVid = $(this).data('id');
       $('#colIndex_'+fVid).remove();
    });


    $(document).on('click','.delete-full-variant', function(e){
       e.preventDefault();
       let dfVal = $(this).data('id');
       let product_id = $('#product_id').val();

       if(confirm('Do you want to delete this?')) {
            $.ajax({
                url: "{{url('/delete-full-variant')}}",
                type:"POST",
                data:{'variant_id':dfVal, 'product_id': product_id}, 
                dataType:"json",
                success:function(data) {
                    $('#colIndex_'+dfVal).remove();
                    toastr.success(data.message);
                },
                error: function(xhr) {
                    toastr.error('Something went wrong.');
                }
            }); 
        }
    });

    // Add new row for variant value
    $(document).on('click','.add-variant',function(e){
        e.preventDefault();
        let dataId = $(this).data('id');
        let html = `<tr>
            <td>
                <input type="text" class="form-control" name="variant_value[]" placeholder="Variant Value" />
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-trash"></i></button>
            </td>
        </tr>`;
        $('.rowIndex_'+dataId).append(html);
        //$('#contents').append(html);
    });

    // Remove a variant row (only frontend)
    $(document).on('click','.remove-variant',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
    });

    // Go back to products list
    $('.go-back-products').click(function(){
        window.history.back();
    });

    // Delete variant from DB
    $(document).on('click','.delete-variant',function(e){
        e.preventDefault();
        let variant_id = $(this).data('id');
        if(confirm('Do you want to delete this?')) {
            $.ajax({
                url: "{{url('/delete-variant')}}",
                type:"POST",
                data:{'variant_id':variant_id, '_token': '{{ csrf_token() }}'},
                dataType:"json",
                success:function(data) {
                    $('#'+variant_id).remove();
                    toastr.success(data.message);
                },
                error: function(xhr) {
                    toastr.error('Something went wrong.');
                }
            }); 
        }
    });

});
  </script>

@endpush