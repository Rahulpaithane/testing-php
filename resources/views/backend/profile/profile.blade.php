@extends('backend.common.baseFile')
@section('content')

@section('title', 'Profile')
@section('papgeTitle', 'Profile')

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
                                <div class="col-auto profile-image">
                                    @if($data->profile_image)
                                    <a href="#">
                                        <img class="rounded-circle" alt="User Image" width="50px" height="50px" src="{{url($data->profile_image)}}">
                                    </a>
                                    @endif
                                </div>
                                <div class="col ml-md-n2 profile-user-info">
                                    <h4 class="user-name mb-0">Name : {{ $data->name }} </h4>
                                    <h6 class="text-muted">Email Id : {{ $data->email }} </h6>
                                </div>
                            </div>
                        </div>
                        <div class="profile-menu">
                            <ul class="nav nav-tabs nav-tabs-solid">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#per_details_tab">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#password_tab">Password</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content profile-tab-cont">

                            <!-- Personal Details Tab -->
                            <div class="tab-pane fade show active" id="per_details_tab">

                                <!-- Personal Details -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">

                                            <div class="card-body">
                                                <h5 class="card-title d-flex justify-content-between">
                                                    <span>Personal Details</span>
                                                    <a class="edit-link" data-toggle="modal"
                                                    href="#edit_personal_details"><i
                                                    class="fa fa-edit mr-1"></i>Edit</a>
                                                </h5>
                                                <div class="box-body mb-0" style="border: 1px solid rgb(206, 205, 205)">
                                                
                                                <div class="row">
                                                    <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">
                                                        Name</p>
                                                    <p class="col-sm-10">{{ Auth::guard('admin')->user()->name }} </p>
                                                </div>

                                                <div class="row">
                                                    <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">
                                                        Email ID</p>
                                                    <p class="col-sm-10">{{ Auth::guard('admin')->user()->email }} </p>
                                                </div>
                                                <div class="row">
                                                    <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">
                                                        Mobile</p>
                                                    <p class="col-sm-10">{{ Auth::guard('admin')->user()->mobile }} </p>
                                                </div>
                                               
                                                <div class="row">
                                                    <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">
                                                        Aadhar</p>
                                                    <p class="col-sm-10">{{ Auth::guard('admin')->user()->aadhar_no }} </p>
                                                </div>
                                                <div class="row">
                                                    <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">
                                                        Gender</p>
                                                    <p class="col-sm-10">{{ Auth::guard('admin')->user()->gender }} </p>
                                                </div>
                                                <div class="row">
                                                    <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">
                                                        Education</p>
                                                    <p class="col-sm-10">{{ Auth::guard('admin')->user()->education }} </p>
                                                </div>
                                                <div class="row">
                                                    @php
                                                        $created = date_create(Auth::guard('admin')->user()->created_at);
                                                        $created_at = date_format($created, 'd-m-Y h:i A');
                                                    @endphp
                                                    <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">
                                                        Creation Date</p>
                                                    <p class="col-sm-10">{{ $created_at }} </p>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Details Modal -->
                                        <div class="modal fade" id="edit_personal_details" aria-hidden="true"
                                            role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Personal Details
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
                                                        <form data="{{ route($shared['routePath'].'.updateProfile') }}" method="POST" enctype="multipart/form-data"
                                                            id="insertFormData">
                                                            @csrf
                                                            <input type="hidden" value="{{ $data->id }}"
                                                                name="id" id="id">
                                                            <div class="row form-row">
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Name</label>
                                                                        <input type="text" class="form-control"
                                                                            name="name" value="{{ $data->name }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Mobile</label>
                                                                        <input type="text" value="{{ $data->mobile }}"
                                                                            name="mobile" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-8">
                                                                    <div class="form-group">
                                                                        <label>Email ID</label>
                                                                        <input type="email" class="form-control"
                                                                            name="email" readonly
                                                                            value="{{ $data->email }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Gender<span class="text-danger fw-bold">*</span></label>
                                                                        <select name="gender" class="form-control single-select" id="gender" >
                                                                            <option value="" hidden>--select--</option>
                                                                            <option {{ $data->gender == "Male" ? 'selected' : '' }} value="Male">Male</option>
                                                                            <option {{ $data->gender == "Female" ? 'selected' : '' }} value="Female">Female</option>
                                                                            <option {{ $data->gender == "Others" ? 'selected' : '' }} value="Others">Others</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Education</label>
                                                                        <input type="text"
                                                                            value="{{ $data->education }}"
                                                                            name="education" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Aadhaar </label>
                                                                        <input type="text"
                                                                            value="{{ $data->aadhar_no }}"
                                                                            name="aadhar_no" class="form-control">
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 col-sm-12">
                                                                    <div class="form-group">
                                                                        <div id="dropArea">
                                                                            <p>Choose/Drag and drop an image here <span class="text-danger fw-bold"> </span></p>
                                                                            <img id="previewImage" src="{{asset($data->profile_image)}}" alt="Preview">
                                                                          </div>
                                                                          <input type="file" name="profile_image" class="form-control"  id="uploadImage"  accept="image/*" style="display: none;">
                                                                          <input type="hidden" name="existing_teacher_image" id="existing_teacher_image" value="{{ $data->profile_image }}" />
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <textarea rows="2" class="form-control"  name="address" id="address" placeholder="Enter Address:">{{ $data->address }}</textarea>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <button type="submit" id="submit"
                                                                class="btn btn-primary btn-block">Save
                                                                Changes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Edit Details Modal -->

                                    </div>


                                </div>
                                <!-- /Personal Details -->

                            </div>
                            <!-- /Personal Details Tab -->

                            <!-- Change Password Tab -->
                            <div id="password_tab" class="tab-pane fade">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Change Password</h5>
                                        <div class="box-body mb-0" style="border: 1px solid rgb(206, 205, 205)">
                                            <form id="insertFormData" data="{{ route($shared['routePath'].'.updatePassword') }}" method="POST" enctype="multipart/form-data" >
                                            @csrf
                                            <input type="hidden" name="email" id="email1" value="{{ $data->email }}">
                                            <input type="hidden" name="id" id="id" value="{{ $data->id }}">
                                            <table class="table">
                                                <tbody>
                                                    <tr class="info">
                                                        <th colspan="2" style="background-color:#d9edf7;">
                                                            Change Password </th>
                                                    </tr>
                                                    <tr>
                                                        <td>Old Password</td>

                                                        <td>
                                                            <input type="password" class="input-lg form-control"
                                                                name="old_password" id="old_password"
                                                                placeholder="Old Password" autocomplete="off" required>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>New Password </td>
                                                        <td>
                                                            <input type="password" class="input-lg form-control"
                                                                name="new_password" id="password1"
                                                                placeholder="New Password" autocomplete="off" required>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <span id="8char" class="fa fa-times"
                                                                        style="color:#FF0004;"></span>
                                                                    8 Characters Long<br>
                                                                    <span id="ucase" class="fa fa-times"
                                                                        style="color:#FF0004;"></span>
                                                                    One Uppercase Letter
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <span id="lcase" class="fa fa-times"
                                                                        style="color:#FF0004;"></span>
                                                                    One Lowercase Letter<br>
                                                                    <span id="num" class="fa fa-times"
                                                                        style="color:#FF0004;"></span>
                                                                    One Number
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>New Password ( Retype) </td>
                                                        <td>
                                                            <input type="password" class="input-lg form-control"
                                                                name="confirm_password" id="password2"
                                                                placeholder="Repeat Password" autocomplete="off" required>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <span id="pwmatch" class="fa fa-times"
                                                                        style="color:#FF0004;"></span>
                                                                    Passwords Match
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                            <button class="btn btn-primary btn-block " type="submit"
                                                                id="submit" name="password_submit"> Update
                                                                Password</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Change Password Tab -->
                        </div>
                    </div>
                </div>
			</section>
        </div>
    </div>
   
    <script type="text/javascript">
        $("input[type=password]").keyup(function() {
            var ucase = new RegExp("[A-Z]+");
            var lcase = new RegExp("[a-z]+");
            var num = new RegExp("[0-9]+");

            if ($("#password1").val().length >= 8) {
                $("#8char").removeClass("fa-times");
                $("#8char").addClass("fa-check");
                $("#8char").css("color", "#00A41E");
            } else {
                $("#8char").removeClass("fa-check");
                $("#8char").addClass("fa-times");
                $("#8char").css("color", "#FF0004");
            }

            if (ucase.test($("#password1").val())) {
                $("#ucase").removeClass("fa-times");
                $("#ucase").addClass("fa-check");
                $("#ucase").css("color", "#00A41E");
            } else {
                $("#ucase").removeClass("fa-check");
                $("#ucase").addClass("fa-times");
                $("#ucase").css("color", "#FF0004");
            }

            if (lcase.test($("#password1").val())) {
                $("#lcase").removeClass("fa-times");
                $("#lcase").addClass("fa-check");
                $("#lcase").css("color", "#00A41E");
            } else {
                $("#lcase").removeClass("fa-check");
                $("#lcase").addClass("fa-times");
                $("#lcase").css("color", "#FF0004");
            }

            if (num.test($("#password1").val())) {
                $("#num").removeClass("fa-times");
                $("#num").addClass("fa-check");
                $("#num").css("color", "#00A41E");
            } else {
                $("#num").removeClass("fa-check");
                $("#num").addClass("fa-times");
                $("#num").css("color", "#FF0004");
            }

            if ($("#password1").val() == $("#password2").val()) {
                $("#pwmatch").removeClass("fa-times");
                $("#pwmatch").addClass("fa-check");
                $("#pwmatch").css("color", "#00A41E");
            } else {
                $("#pwmatch").removeClass("fa-check");
                $("#pwmatch").addClass("fa-times");
                $("#pwmatch").css("color", "#FF0004");
            }
        });
    </script>

<script src="{{ url('assets/js/img-uploader.js')}}"></script>
@endsection
