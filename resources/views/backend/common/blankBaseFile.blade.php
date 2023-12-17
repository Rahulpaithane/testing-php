
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
	{{-- <link rel="stylesheet" type="text/css" href="{{ url('assets/css/img-uploader.css')}}"/> --}}

	<link rel="stylesheet" type="text/css" href="{{ url('assets/js/timepicker/bootstrap-clockpicker.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/magnific-popup.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/icofont.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/summernote.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{ url('assets/css/admin-fonts.css')}}"/>
    {{-- <link rel="stylesheet" type="text/css" href="{{ url('assets/css/backend-rtl.css?1686640687')}}"/> --}}
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/backend.css?1686640687')}}"/>
	<!-- favicon links -->
	<link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/images/disa_testboard_logo.png')}}" />

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!--Datatable  -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jqc-1.12.4/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.css"/>

       <link rel="stylesheet" type="text/css" href="{{ url('assets/js/inputTag/tagsinput.css')}}"/>

    <script src="{{ url('assets/updationController.js')}}"></script>
 <link rel="stylesheet" type="text/css" href="{{ url('assets/css/toastr.min.css')}}"/>
 

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.min.css" integrity="sha384-3UiQGuEI4TTMaFmGIZumfRPtfKQ3trwQE2JgosJxCnGmQpL/lJdjpcHkaaFwHlcI" crossorigin="anonymous">


</head>
<body class="">


<section class="edu_admin_content">
    <div class="sectionHolder edu_admin_right edu_dashboard_wrap">
        <div class="edu_dashboard_widgets">
	        @yield('content')
        </div>
    </div>

</section>  <!-- Pop view characters Start  -->


</div>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="{{ url('assets/js/toastr.min.js')}}"></script>


<script src="{{ url('assets/js/timepicker/bootstrap-clockpicker.min.js')}}"></script>

<script src="{{ url('assets/js/jquery.magnific-popup.min.js')}}"></script>

<script src="{{ url('assets/js/summernote.js')}}"></script>


<script src="{{ url('assets/js/custom.js')}}"></script>

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



@livewireScripts
</body>
</html>

