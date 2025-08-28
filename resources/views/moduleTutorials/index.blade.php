@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">module Tutorials Details</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">module Tutorials Details</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">module Tutorials Details</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{route('module-tutorials.create')}}" class="btn btn-primary add-new mb-2">Add New module Tutorials Details</a>
                <div class="fetch-data table-responsive">
                    <table id="user-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Video URL</th>
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
  		let id;
  		var sliderTable = $('#user-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/module-tutorials') }}",
		        },

		        columns: [
		            {data: 'module_title', name: 'module_title'},
		            {data: 'video_url', name: 'video_url'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-user-update', function(){

	         id = $(this).data('id');
	         var isUserchecked = $(this).prop('checked');
	         var status_val = isUserchecked ? 'approved' : 'pending';
	         $.ajax({

                url: "{{url('/purchase-status-update')}}",

                     type:"POST",
                     data:{'id':id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {

                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },

	        });
       });


       $(document).on('click', '.delete-data', function(e){

           e.preventDefault();

           id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({
                    url: "{{ url('module-tutorials') }}/" + id,

                        type: "DELETE",
                        dataType: "json",
                         success:function(data) {
                        if (data.status === true) {
                            toastr.success(data.message);
                            $('.data-table').DataTable().ajax.reload(null, false);

                        } else {
                            toastr.error(data.message);
                        }

                    },
              });
           }
       });
  	});
  </script>

@endpush
