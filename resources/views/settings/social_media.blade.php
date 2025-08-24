@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Social Media Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/app-settings')}}">Social Media Settings
                                </a></li>
                        <li class="breadcrumb-item active">Social Media Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Social Media Settings</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{url('settings-app')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="facebook_url">Facebook <span class="required">*</span></label>
                                <input type="text" name="facebook_url" class="form-control" id="facebook_url"
                                    placeholder="Facebook URL"  value="{{old('facebook_url',$setting?$setting->facebook_url:"")}}">
                                @error('facebook_url')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div> 
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="twitter_url">Tiktok <span class="required">*</span></label>
                                <input type="text" name="twitter_url" class="form-control" id="twitter_url"
                                    placeholder="Tiktok"  value="{{old('twitter_url',$setting?$setting->twitter_url:"")}}">
                                @error('twitter_url')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div> 
                        </div>

                        
                         <div class="col-md-12">
                            <div class="form-group">
                                <label for="instagram_url">Instragram <span class="required">*</span></label>
                                <input type="text" name="instagram_url" class="form-control" id="instagram_url"
                                    placeholder="Instragram"  value="{{old('instagram_url',$setting?$setting->instagram_url:"")}}">
                                @error('instagram_url')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div> 
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="youtube_url">Youtube <span class="required">*</span></label>
                                <input type="text" name="youtube_url" class="form-control" id="youtube_url"
                                    placeholder="Youtube"  value="{{old('youtube_url',$setting?$setting->youtube_url:"")}}">
                                @error('youtube_url')
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