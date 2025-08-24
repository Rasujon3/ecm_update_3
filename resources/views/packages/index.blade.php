@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Package</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Package</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Package</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{route('packages.create')}}" class="btn btn-primary add-new mb-2">Add New Package</a>
                <div class="fetch-data table-responsive">
                    <table id="package-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Package Name</th>
                                <th>Package Price ( BDT )</th>
                                <th>Max Product</th>
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
  		let package_id;
  		var packageTable = $('#package-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{url('/packages')}}",
		        },

		        columns: [
		            {data: 'package_name', name: 'package_name'},
		            {data: 'price', name: 'price'},
		            {data: 'max_product', name: 'max_product'},
		            {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-package-update', function(){

	         package_id = $(this).data('id');
	         var isPackagechecked = $(this).prop('checked');
	         var status_val = isPackagechecked ? 'Active' : 'Inactive'; 
	         $.ajax({

                url: "{{url('/package-status-update')}}",

                     type:"POST",
                     data:{'package_id':package_id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {

                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },
	                            
	        });
       }); 


       $(document).on('click', '.delete-package', function(e){

           e.preventDefault();

           package_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/packages')}}/"+package_id,

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