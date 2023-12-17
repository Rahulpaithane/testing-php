<!DOCTYPE html>
<html lang="en" dir="ltr" >
<head>
    <title>{{ $shared['title'] }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/magnific-popup.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend-style.css?1686643210')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend-style.css?1686643210')}}"/>
    <!------ Include the above in your HEAD tag ---------->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/'.$shared['logo'])}}" />

</head>
<body class="">
<!----- Preloader Box ----->
<div class="edu_preloader">
	<div class="edu_status">
		<img src="{{ asset('assets/images/preloader.gif')}}" alt="loader">
	</div>
</div>
<!----- Preloader Box ----->
<div class="pxn_login_main">
    <div class="pxn_login_box">
        <div class="pxn_login_box_inner">
            <div class="pxn_logo">
                <img src="{{ asset('assets/images/'.$shared['logo'])}}" height="20%" width="20%" class="img-fluid">
            </div>
            <div class="pxn_login_data">
                <div class="content-top-agile pb-0">
                    <h4 class="mb-1 fw-600 text-dark">Reset Password</h4>
                </div>
                <div class="p-40">
                    <form action="{{ route('admin.institutePasswordReset', ['key'=>$data->reset_token, 'id'=>$domainId]) }}" id="formData" method="POST" accept-charset="UTF-8">
                        @if(Session::get('success'))
                        <div class="alert alert-success text-center">
                            {{ Session::get('success') }}
                        </div>
                        @endif
                        @csrf
                        <input type="hidden" name="email" value="{{ $data->email }}">
                        <div class="form-group">
                            <div class="input-group mb-3">
                            <span toggle="#password-field" class="input-group-text toggle-password1"><i class="text-fade ti-lock"></i></span>
                                <input name="password" type="password" value="" class="form-control" id="password" placeholder="New Password" value="{{ old('password') }}" autofocus>
                            </div>
                            @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                            <span toggle="#password-field" class="input-group-text toggle-password"><i class="text-fade ti-lock"></i></span>
                                <input name="password_confirm" type="password" value="" class="form-control" id="password_confirm" placeholder="Confirm password" value="{{ old('password_confirm') }}">
                            </div>
                            @error('password_confirm')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <button class="edu_btn edu_btn_black text-white w-100 mt-10" type="submit" >Reset</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    <div>
                        <ul class="fw-light text-info mb-2">
                            <span class="text-danger font-weight-bold">Rules*</span>
                            <li>must be at least 8 characters in length.</li>
                            <li>must contain at least one lowercase letter.</li>
                            <li>must contain at least one uppercase letter.</li>
                            <li>must contain at least one digit.</li>
                            <li>must contain a special character.</li>
                        </ul>
                    </div>
            </div>
        </div>
    </div>
</div>
<!-- Vendor JS -->
<script src="{{asset('front-end/src/js/vendors.min.js')}}"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    $("body").on('click', '.toggle-password', function() {
        $(this).toggleClass("");
        var input = $("#password_confirm");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $("body").on('click', '.toggle-password1', function() {
        $(this).toggleClass("");
        var input = $("#password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
<!-- Login end-->
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/toastr.min.js')}}"></script>
	<script src="{{ asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{ asset('assets/js/login.js?1686643210')}}"></script>
    <script src="{{ asset('assets/js/valid.js?1686643210')}}"></script>
</body>
</html>


