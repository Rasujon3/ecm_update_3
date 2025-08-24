@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Payment Info</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Payment Info</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Payment Info</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{route('payment-info.create')}}" class="btn btn-primary add-new mb-2">Add New Payment Info</a>
                <div class="fetch-data table-responsive">
                    <table id="payment-info-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Acc No</th>
                                <th>Payment Type</th>
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
  		let service_id;
  		var serviceTable = $('#payment-info-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ route('payment-info.index') }}",
		        },

		        columns: [
		            {data: 'payment_method', name: 'payment_method'},
		            {data: 'account_number', name: 'account_number'},
		            {data: 'payment_type', name: 'payment_type'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-service-update', function(){

	         service_id = $(this).data('id');
	         var isUnitchecked = $(this).prop('checked');
	         var status_val = isUnitchecked ? 'Active' : 'Inactive';
	         $.ajax({

                url: "{{url('/service-status-update')}}",

                     type:"POST",
                     data:{'service_id':service_id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {

                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },

	        });
       });


       $(document).on('click', '.delete-payment-info', function(e){

           e.preventDefault();

           service_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/payment-info')}}/"+service_id,

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
