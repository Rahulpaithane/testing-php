
 <!DOCTYPE html>
<html lang="en" dir="ltr" >
<!--<![endif]-->
<!-- Header Start -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>@yield('title')</title>
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta name="description"  content="Description about Sure Success"/>
	<meta name="keywords" content="Sure Success, academy,education academy"/>
	<meta name="author"  content="Aditya Raj Singh"/>
	<meta name="MobileOptimized" content="320"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<!-- main css section start-->
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/fontawesome.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/jquery-ui.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/img-uploader.css')}}"/>
	{{-- <link rel="stylesheet" type="text/css" href="{{ url('assets/css/jquery-ui.css')}}"/> --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{ url('assets/js/datatable/dataTables.bootstrap.css')}}"> --}}
	
    
	{{-- <link rel="stylesheet" type="text/css" href="{{ url('assets/css/select2.min.css')}}"/> --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{ url('assets/css/bootstrap.min.css')}}"/>	 --}}
	<link rel="stylesheet" type="text/css" href="{{ url('assets/js/timepicker/bootstrap-clockpicker.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/magnific-popup.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/icofont.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/summernote.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/admin-fonts.css')}}"/>
    {{-- <link rel="stylesheet" type="text/css" href="{{ url('assets/css/backend-rtl.css?1686640687')}}"/> --}}
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/backend.css?1686640687')}}"/>
	<!-- favicon links -->
	<link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/images/'.$shared['logo'])}}" />

   {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> --}}

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">



  {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script> --}}
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!--Datatable  -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jqc-1.12.4/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.css"/>

       {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> --}}
       <link rel="stylesheet" type="text/css" href="{{ url('assets/js/inputTag/tagsinput.css')}}"/>
    {{-- <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> --}}
    <script src="{{ url('assets/updationController.js')}}"></script>
 <link rel="stylesheet" type="text/css" href="{{ url('assets/css/toastr.min.css')}}"/>
 

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.min.css" integrity="sha384-3UiQGuEI4TTMaFmGIZumfRPtfKQ3trwQE2JgosJxCnGmQpL/lJdjpcHkaaFwHlcI" crossorigin="anonymous">


{{-- <link href="
https://cdn.jsdelivr.net/npm/mathquill@0.10.1-a/build/mathquill.min.css
" rel="stylesheet"> --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        renderMathInElement(document.body, {
          // customised options
          // • auto-render specific keys, e.g.:
          delimiters: [
              {left: '$$', right: '$$', display: true},
              {left: '$', right: '$', display: false},
              {left: '\\(', right: '\\)', display: false},
              {left: '\\[', right: '\\]', display: true}
          ],
          // • rendering keys, e.g.:
          throwOnError : false
        });
    });
</script>
<style>
    @media print {
	body {
		display: none;
	}
}
</style>
</head>
<body class="">
<!----- Preloader Box ----->
<div class="edu_preloader">
	<div class="edu_status">
		{{-- <img src="{{ url('uploads/site_data/e-academy.gif')}}" alt="loader" /> --}}
	</div>
</div>
<!----- Preloader Box ----->

@php 
// $host = request()->getHost();
// $hostParts = explode('.', $host);

// if (count($hostParts) ==3) {
// //   $subdomain = $hostParts[0];
// //   $domain = $hostParts[1];
//   $routePath='tenantAdmin';
//   $domainRoute=false;
  
// } else if(count($hostParts) ==2){
// 	if($hostParts[1] == 'com' || $hostParts[1] == 'in'){
// 			$routePath='admin';
//             $domainRoute=true;
//     } else {
// 		$routePath='tenantAdmin';
//         $domainRoute=false;
//     }
// } else {
// 	$routePath='admin';
//     $domainRoute=true;
// }

// echo $subdomain;
@endphp

<div class="edu_header_sidebar">
	<header class="edu_left_header">
		<div class="edu_admin_logo">
			<a href="{{ route('admin.dashboard')}}">
                <img src="{{ url('assets/images/'.$shared['logo'])}}" style="width: 70px !important; height:70px !important; " class="logoRelativeCls main_logo" alt="Logo">
            </a>
			<a href="#"><img src="{{url('assets/images/'.$shared['logo'])}}" class="mini_logo" alt="Minilogo"></a>
		    <div class="edu_header_close responsive_btn">
        		<span></span>
                <span></span>
                <span></span>
        	</div>
		</div>
		<div class="edu_admin_header_right">
			 {{--sideBar include  --}}
			 @include('backend.common.sideBar')
		</div>
	</header>
</div>

<div class="edu_admin_header edu_top_header">
	@include('backend.common.topBar')
    {{-- Top Bar Include --}}
</div>
{{-- <input type="hidden" id="base_url" value="{{ url('')}}"> --}}


<section class="edu_admin_content">
    <div class="sectionHolder edu_admin_right edu_dashboard_wrap">
        <div class="edu_dashboard_widgets">
	        @yield('content')
        </div>
    </div>

</section>  <!-- Pop view characters Start  -->


<div id="charactersViewPopup" class="edu_popup_container mfp-hide">
    <div class="edu_popup_wrapper">
        <div class="edu_popup_inner">
           <h4 class="edu_sub_title" id="charaTitele"></h4>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="form-group">
                        <div class="charactersViewResult"></div>
                    </div>
				</div>
			</div>
        </div>
    </div>
</div>

 <div class="edu_admin_footer hide">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p>Copyright © 2021 Sure Success. All Right Reserved.</p>
    </div>
</div>

<!-- Logout start-->
<div id="logoutPopup" class="edu_popup_container mfp-hide">
    <div class="edu_popup_wrapper">
        <div class="edu_popup_inner text-center">
            <h4 class="edu_title edu_logt_title padderBottom20">Are you sure, You want to logout?</h4>
            <button type="button" class="edu_admin_btn edu_admin_btn_black edu_btn_black logoutBtnCncl mb-2">Cancel</button>
            <button type="button" class="edu_admin_btn logOutBtn ml-2 mb-2">Yes</button>
        </div>
    </div>
</div>
<!-- Logout end-->

</div>

{{-- <script src="{{ url('assets/js/bootstrap.min.js')}}"></script> --}}
{{-- <script src="{{ url('assets/js/jquery-ui.js')}}"></script> --}}
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="{{ url('assets/js/toastr.min.js')}}"></script>


<script src="{{ url('assets/js/timepicker/bootstrap-clockpicker.min.js')}}"></script>
{{-- <script src="{{ url('assets/js/datatable/jquery.dataTables.min.js')}}"></script> --}}
<script src="{{ url('assets/js/jquery.magnific-popup.min.js')}}"></script>
{{-- <script src="{{ url('assets/js/select2.min.js')}}"></script> --}}
<script src="{{ url('assets/js/summernote.js')}}"></script>

{{-- <script src="{{ url('assets/js/Chart.min.js')}}"></script> --}}
{{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
{{-- <script src="https://cdn.ckeditor.com/4.15.1/standard-all/ckeditor.js"></script> --}}
{{-- <script src="{{ url('assets/js/backend.js')}}"></script> --}}
<script src="{{ url('assets/js/custom.js')}}"></script>


  {{-- <script type="text/javascript" --}}
        {{-- src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML"></script> --}}
{{-- <script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script> --}}


{{-- <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> --}}
<script src="{{ url('assets/js/inputTag/tagsinput.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.js"></script>
<script src="{{ url('assets/formController.js')}}"></script>
<script src="{{ url('assets/customeController.js')}}"></script>



<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.min.js" integrity="sha384-G0zcxDFp5LWZtDuRMnBkk3EphCK1lhEf4UEyEM693ka574TZGwo4IWwS6QLzM/2t" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/contrib/auto-render.min.js" integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous"></script>

<script src="{{ url('assets/js/summernote-math.js')}}"></script>

{{-- <script src="
https://cdn.jsdelivr.net/npm/mathquill@0.10.1-a/build/mathquill.min.js
"></script> --}}



<script type="text/javascript">
    // $(document).ready(function() {
        $('.summernote').summernote({
        placeholder: 'Hello Bootstrap 4',
        tabsize: 2,
        height: 100
      });
// });

</script>

<script>
    $( function() {
        $(".chooseDate" ).datepicker({
		changeMonth: true, 
		changeYear: true,        
		dateFormat: 'yy-mm-dd',
		yearRange: "c-30:c+15",
	
	 });
    } );
    </script>

<script>
    $(document).ready(function() {
  var currentUrl = window.location.href;

  // Select all anchor tags on the page
  $('a').each(function() {
    var anchorUrl = $(this).attr('href');

    // Check if the anchor's href matches the current URL
    if (currentUrl.indexOf(anchorUrl) !== -1) {
        $(this).closest('.sub-menu').addClass('active');
        $(this).closest('.has_sub_menu').addClass('active');
        $(this).closest('li').addClass('active');
    }
  });
});
</script>
@livewireScripts
</body>
</html>

