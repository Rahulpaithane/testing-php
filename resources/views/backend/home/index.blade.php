@extends('backend.common.baseFile')
@section('content')
@section('title', 'Disha Testboard | Dashboard')
@section('papgeTitle', 'Dashboard')
<section>
    @include('backend.home.dashbordWidgets')
</section>
<hr/>
<section>
@include('backend.home.totalStudentInBatch')
</section>
@endsection
