@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Finance Reportt</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Finance Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Finance Report</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="card w-100">
                  <div class="card-header">
                    <h5>Filter Sales Report</h5>
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

                        <button type="button" class="btn btn-primary btn-block filter-finance"><i class="fa fa-search"></i> SEARCH</button>

                       

                     </div>

                     </div>
                  </div>
                </div>
                <div class="fetch-data table-responsive">
                    <table id="finance-report" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Total Sales (BDT)</th>
                                <th>Total Expense (BDT)</th>
                                <th>Total Revenue (BDT)</th>
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
      var financeTable = $('#finance-report').DataTable({
            searching: true,
            processing: true,
            serverSide: true,
            ordering: false,
            responsive: true,
            stateSave: true,
            ajax: {
              url: "{{url('/finance-report')}}",
              data: function (d) {

                  d.from_date = $('#from_date').val(),
                  d.to_date = $('#to_date').val(),
                  d.status = $('#selected_status').val()
                  //d.category_id = $('#selected_category_id').val(),
                  d.search = $('.dataTables_filter input').val()
              }
            },

            columns: [
                {data: 'order_id', name: 'order_id'},
                {data: 'order_date', name: 'order_date'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'customer_phone', name: 'customer_phone'},
                {data: 'total', name: 'total'},
                {data: 'total_expense', name: 'total_expense'},
                {data: 'total_revenue', name: 'total_revenue'},
            ]
        });

       $('.filter-finance').click(function(e){
          e.preventDefault();
          financeTable.draw(); 
      });

    });
  </script>

@endpush