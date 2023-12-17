
<!DOCTYPE html>
<html lang="en" dir="ltr" >
<head>
    <title>{{ $shared['title'] }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/toastr.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/magnific-popup.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/frontend-style.css?1686643210')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/font.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/frontend-style.css?1686643210')}}"/>
    <!------ Include the above in your HEAD tag ---------->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/images/'.$shared['logo'])}}" />

</head>
<body class="">
<!----- Preloader Box ----->
<div class="edu_preloader">
	<div class="edu_status">
		<img src="{{ url('assets/images/preloader.gif')}}" alt="loader">
	</div>
</div>
<!----- Preloader Box ----->
<div class="pxn_login_main">
    <div class="pxn_login_box">
        <div class="pxn_login_box_inner">
            <div class="pxn_logo">
                <img src="{{ url('assets/images/'.$shared['logo'])}}" height="20%" width="20%" class="img-fluid">
            </div>
            <div class="pxn_login_data">
                <h4>Please Login to Continue</h4>
                <form class="form" method="post" action="{{$route}}"  accept-charset="UTF-8">
                    @if(Session::get('success'))
                    <div class="alert alert-success text-center">
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    @if(Session::get('failed'))
                    <div class="alert alert-danger text-center">
                        {{ Session::get('failed') }}
                    </div>
                    @endif
                    @if(Session::get('fail'))
                    <div class="alert alert-danger text-center">
                        {{ Session::get('fail') }}
                    </div>
                    @endif
                    @csrf
                    <div class="edu_field_holder">
                        <input type="text" class="edu_form_field require" name="email" placeholder="Email Id" autocomplete="off" value="" required>
                    </div>
                    <div class="edu_field_holder">
                        <input type="password" name="password" class="require edu_form_field" placeholder="Password" value="" required>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="loginLinks checkbox_holder">
                                    <input type="checkbox" id="auth_remember" name="remember_me" >
                                    <label for="auth_remember">Remember me</label>
                                </div>
                            </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-md-right">
                            <div class="loginLinks">
                                <a class="form_link" href="{{route('admin.forgotPassword')}}">Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                    <div class="login_btn_wrapper">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="login_submit_btn">
                                    <div class="backToHome"><a class="edu_btn" href="{{url('/')}}">Back to Home</a></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-md-right">
                                <button class="edu_btn edu_btn_black"  type="submit" >Login</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- Student Login start-->
<div id="studentLogin" class="edu_popup_container mfp-hide">
    <div class="edu_popup_wrapper">
        <div class="edu_popup_inner text-center">
            <h4 class="edu_admin_title edu_logt_title"></h4>
            <h6 class="edu_admin_sub_title edu_logt_title">You are already logged in from another device.</h6>
            <input type="hidden" value="https://suresuccessias.in/bipl/c-panel/" id="base_url">
             <button type="button" class="edu_btn changeStudentLogin mb-2" data-id="">Yes</button>
             <button type="button" class="edu_btn edu_btn_black PopupCancelBtn ml-2 mb-2">Cancel</button>
        </div>
    </div>
</div>
<!-- Login end-->
    <script src="{{ url('assets/js/jquery.min.js')}}"></script>
    <script src="{{ url('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ url('assets/js/toastr.min.js')}}"></script>
	<script src="{{ url('assets/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{ url('assets/js/login.js?1686643210')}}"></script>
    <script src="{{ url('assets/js/valid.js?1686643210')}}"></script>
</body>
</html>
