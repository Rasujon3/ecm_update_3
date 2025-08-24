@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">User Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">User Products</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Products</h3> 
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            	<div class="card w-100">
                  <div class="card-header">
                    <h5>Filter Products</h5>
                  </div>

                  <div class="card-body">
                     <div class="row">
 


                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Select Status</label>
                          <select class="form-control select2bs4" id="selected_status">
                           <option value="" selected="" disabled="">Select Status</option>
                           <option value="Active">Active</option>
                           <option value="Inactive">Inactive</option>
                          </select>
                        </div>
                        
                      </div>

                      <div class="col-md-12">

                        <button type="button" class="btn btn-primary btn-block filter-user-products"><i class="fa fa-search"></i> SEARCH</button>

                       

                     </div>

                     </div>
                  </div>
                </div>
                <div class="fetch-data table-responsive">
                    <table id="user-product-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Unit</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="conts"> 
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
  
  <script>
  	$(document).ready(function(){
  		let product_id;
  		var userProductTable = $('#user-product-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{url('/user-products')}}", 
		          data: function (d) {

	                  // d.from_date = $('#from_date').val(),
	                  // d.to_date = $('#to_date').val(),
	                  d.status = $('#selected_status').val()
	                  //d.category_id = $('#selected_category_id').val(),
	                  //d.search = $('.dataTables_filter input').val()
	              }
		        },

		        columns: [
		            {data: 'product_name', name: 'product_name'},
	                {data: 'unit', name: 'unit'},
	                {data: 'stock_qty', name: 'stock_qty'},
		            {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });

        $('.filter-user-products').click(function(e){
          e.preventDefault();
          userProductTable.draw(); 
      });



       $(document).on('click', '#status-product-update', function(){

	         product_id = $(this).data('id');
	         var isProductchecked = $(this).prop('checked');
	         var status_val = isProductchecked ? 'Active' : 'Inactive'; 
	         $.ajax({

                url: "{{url('/product-status-update')}}",

                     type:"POST",
                     data:{'product_id':product_id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {

                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },
	                            
	        });
       }); 


       $(document).on('click', '.delete-product', function(e){

           e.preventDefault();

           product_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/products')}}/"+product_id,

                         type:"DELETE",
                         dataType:"json",
                         success:function(data) {

                            toastr.success(data.message);

                            $('.data-table').DataTable().ajax.reload(null, false);

                    },
                                
              });
           }

       });

  	});
  </script>

@endpush