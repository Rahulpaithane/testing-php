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
                <h4>Forgot Password ?</h4>
                <h6 class="mb-2 text-bold">Enter your email to get reset password link :</h6>
                <form action="{{ route('admin.password-reset')}}" id="formData" method="POST" accept-charset="UTF-8">
                    @if(Session::get('success'))
                    <div class="alert alert-success text-center">
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    @if(Session::get('fail'))
                    <div class="alert alert-danger text-center">
                        {{ Session::get('fail') }}
                    </div>
                    @endif
                    @csrf
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-transparent"><i class="text-fade ti-email"></i></span>
                            <input type="email" name="email" class="form-control ps-15 bg-transparent" placeholder="Your Email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center mb-3">
                            <button class="edu_btn edu_btn_black text-white w-100 mt-10" type="submit" >Reset</button>
                        </div>
                       <center> <a href="/admin/login"><i class="fas fa-arrow-circle-left mr-1"></i>Remembered? Go Back</a></center>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Login end-->
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/toastr.min.js')}}"></script>
	<script src="{{ asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{ asset('assets/js/login.js?1686643210')}}"></script>
    <script src="{{ asset('assets/js/valid.js?1686643210')}}"></script>
</body>
</html>
