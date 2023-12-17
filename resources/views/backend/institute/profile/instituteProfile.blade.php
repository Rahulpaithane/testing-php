@extends('backend.common.baseFile')
@section('content')

@section('title', 'Institute Profile')
@section('papgeTitle', 'Institute Profile')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
       
        <div class="container-full">
             
            <!-- Main content -->
            <section class="content">
                <div class="row">

                    <div class="col-md-12 ml-auto">
                        <div class="profile-header">
                            {{-- <h5 class="header-title">Manage Profile</h5> --}}
                            <div class="row align-items-center">
                               
                                <div class="col ml-md-n2 profile-user-info">
                                    <h4 class="user-name mb-0">Name : {{ $data->institute_name }} </h4>
                                    <h6 class="text-muted">Domain : {{ $data->tenant_id }} </h6>
                                </div>
                            </div>
                        </div>
                        <hr/>
                
                   
                    </div>
                </div>
                <div class="row">
                    @include('backend.institute.profile.instituteProfileWidget')
                </div>
			</section>
            <hr/>
        </div>
    </div>
   

@endsection
