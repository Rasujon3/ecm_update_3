@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Expense</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Expense</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Expense</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            	<div class="card w-100">
                  <div class="card-header">
                    <h5>Filter Expense</h5>
                  </div>

                  <div class="card-body">
                     <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="from_date">From Date</label>
                          <input type="date" class="form-control" id="from_date" required=""/>
                        </div>
                        
                      </div> 


                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="to_date">To Date</label>
                          <input type="date" class="form-control" id="to_date" required=""/>
                        </div>
                        
                      </div> 


                      <div class="col-md-12">

                        <button type="button" class="btn btn-primary btn-block filter-expense"><i class="fa fa-search"></i> SEARCH</button>

                       

                     </div>

                     </div>
                  </div>
                </div><br><br>
                <a href="{{route('expenses.create')}}" class="btn btn-primary add-new mb-2">Add New Expense</a>
                <div class="fetch-data table-responsive">
                    <table id="expense-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Amount ( BDT )</th>
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
  		let expense_id;
  		var expenseTable = $('#expense-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{url('/expenses')}}",
		          data: function (d) {

	                  d.from_date = $('#from_date').val(),
	                  d.to_date = $('#to_date').val(),
	                  //d.category_id = $('#selected_category_id').val(),
	                  d.search = $('.dataTables_filter input').val()
	              }
		        },

		        columns: [
		            {data: 'title', name: 'title'},
		            {data: 'date', name: 'date'},
		            {data: 'amount', name: 'amount'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });

  		$('.filter-expense').click(function(e){
          e.preventDefault();
          expenseTable.draw(); 
      });

       $(document).on('click', '.delete-expense', function(e){

           e.preventDefault();

           expense_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/expenses')}}/"+expense_id,

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