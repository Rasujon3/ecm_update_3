@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Expense</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/expenses')}}">All Expense
                                </a></li>
                        <li class="breadcrumb-item active">Edit Expense</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Edit Expense</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('expenses.update',$expense->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row">

                    	<div class="col-md-12">
                            <div class="form-group">
                                <label for="date">Date <span class="required">*</span></label>
                                <input type="date" name="date" class="form-control" id="date"
                                    placeholder="Title" required="" value="{{old('date',$expense->date)}}" readonly="">
                                @error('date')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <input type="text" name="title" class="form-control" id="title"
                                    placeholder="Title" required="" value="{{old('title',$expense->title)}}">
                                @error('title')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> 

                        

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="amount">Amount <span class="required">*</span></label>
                                <input type="text" name="amount" class="form-control numericInput" id="amount"
                                    placeholder="Amount" required="" value="{{old('amount',$expense->amount)}}">
                                @error('amount')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div> 
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="remarks">Remarks <span class="required">*</span></label>
                                <textarea class="form-control" name="remarks" id="remarks" required="" placeholder="Remarks">{!!old('remarks',$expense->remarks)!!}</textarea>
                                @error('remarks')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </section>
</div>
@endsection