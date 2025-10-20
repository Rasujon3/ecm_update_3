@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Pixel</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Pixel</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        @if(!empty($url))
            <button type="button" class="btn btn-success mb-3" id="watchTutorialBtn">
                Watch Tutorial
            </button>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Pixel</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if($count < 1)
                    <a href="{{route('pixels.create')}}" class="btn btn-primary add-new mb-2">Add New Pixel</a>
                @endif
                <div class="fetch-data table-responsive">
                    <table id="product-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Pixel ID</th>
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
            @include('components.youtubeVideoSection')
    </section>
</div>
@endsection

@push('scripts')
    @if(!empty($url))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btn = document.getElementById("watchTutorialBtn");
            const videoSection = document.getElementById("tutorialVideoSection");
            const iframe = document.getElementById("tutorialIframe");

            btn?.addEventListener("click", function () {
                // Set YouTube embed URL
                iframe.src = "{{ $url }}";

                // Show section
                videoSection.style.display = "block";

                // Smooth scroll to video
                videoSection.scrollIntoView({ behavior: "smooth" });
            });
        });
    </script>
    @endif

  <script>
  	$(document).ready(function(){
  		let product_id;
  		var productTable = $('#product-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{url('/pixels')}}",
		        },

		        columns: [
		            {data: 'pixel_id', name: 'pixel_id'},
		            {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-update', function(){

	         product_id = $(this).data('id');
	         var isProductchecked = $(this).prop('checked');
	         var status_val = isProductchecked ? 'Active' : 'Inactive';
	         $.ajax({
                url: "{{url('/pixel-status-update')}}",
                     type: "POST",
                     data: {'product_id':product_id, 'status':status_val},
                     dataType: "json",
                     success:function(data) {
                        if (data.status == true) {
                            toastr.success(data.message);
                            $('.data-table').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(data.message);
                        }
                },
	        });
       });


       $(document).on('click', '.delete-data', function(e){

           e.preventDefault();

           product_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({
                    url: "{{url('/pixels')}}/"+product_id,
                         type:"DELETE",
                         dataType:"json",
                         success:function(data) {
                             if (data.status == true) {
                                 toastr.success(data.message);
                                 // $('.data-table').DataTable().ajax.reload(null, false);
                                 window.location.reload();
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
