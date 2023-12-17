@extends('backend.common.baseFile')
@section('content')

@section('title', 'Digit Papers List')
@section('papgeTitle', 'Digital Papers List')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                <div class="btn-group ml-auto" role="group" aria-label="Button group">
                    <a href="{{ route($shared['routePath'].'.newOmrCollectionPaper') }}" class="btn btn-outline-dark"> <i
                            class="fas fa-plus"></i> New Collection OMR</a>
                    <a href="{{ route($shared['routePath'].'.newDigitalPaper') }}" class="btn btn-outline-info"> <i
                            class="fas fa-plus"></i> New Manual OMR</a>
                </div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#collection">Collection OMR</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#manual">Manual OMR</a>
                    </li>

                </ul>

            </div>
            <div class="tab-content">
                <div id="collection" class="tab-pane active"><br>
                    <div class="edu_main_wrapper edu_table_wrapper">
                        <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                            <div class="tableFullWrapper">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dt-responsive" cellspacing="0"
                                        width="100%" id="collection_omr_datatable">
                                        <thead>
                                            <tr>
                                                {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                                <th>#Id</th>
                                                <th>OMR No.</th>
                                                {{-- <th>Exam</th> --}}
                                                {{-- <th>Batch</th> --}}
                                                <th>Paper Name</th>
                                                {{-- <th>Language</th> --}}
                                                {{-- <th>Cat.</th>
                                                <th>SubCa.t</th> --}}
                                                <th>T.Que</th>
                                                <th>Time (min)</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>-ve Marking</th>
                                                <th>No./-Ve</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="manual" class="tab-pane fade"><br>
                    <div class="edu_main_wrapper edu_table_wrapper">
                        <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                            <div class="tableFullWrapper">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dt-responsive" cellspacing="0"
                                        width="100%" id="server_datatable">
                                        <thead>
                                            <tr>
                                                {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                                <th>#Id</th>
                                                <th>OMR No.</th>
                                                <th>Paper Name</th>
                                                <th>Total Question</th>
                                                <th>Total Marks</th>
                                                <th>No./Q.</th>
                                                <th>Time (In Min)</th>
                                                <th>-ve Marking</th>
                                                <th>No./-Ve</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>
</section>

<script>
    $(function() {
        load_data("", "", ""); // first load
        load_collection_omr("", "", "")
    });

    function load_data(initial_date = '', final_date = '', current_date = '') {
        var ajax_url = '{{ route($shared['routePath'].".digitalPaperList") }}';
        var t = $('#server_datatable').DataTable({
            "order": [
                [0, "desc"]
            ],
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 50, 100, 500, -1],
                [10, 50, 100, 500, "All"]
            ],
            buttons: [
                'pageLength', 'excel'
            ],
            processing: true,
            serverSide: true,
            destroy: true,
            // stateSave: true,
            "ajax": {
                "url": ajax_url,
                "dataType": "json",
                "type": "post",
                "data": {
                    "initial_date": initial_date,
                    "final_date": final_date,
                    "current_date": current_date,
                    '_token': '{{ csrf_token() }}'
                },
                // "dataSrc": "records"
            },
            "columns": [
                // { data: 'DT_RowIndex' },
                {
                    "data": 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'omr_code',
                    name: 'omr_code'
                },
                {
                    data: 'paper_name',
                    name: 'paper_name'
                },
                {
                    data: 'total_question',
                    name: 'total_question'
                },
                {
                    data: 'total_marks',
                    name: 'total_marks'
                },
                {
                    data: 'numberPerQuestion',
                    name: 'numberPerQuestion'
                },
                {
                    data: 'examDuration',
                    name: 'examDuration'
                },
                {
                    data: 'isNegative',
                    name: 'isNegative'
                },
                {
                    data: 'numberPerNegative',
                    name: 'numberPerNegative'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ],
        });
    }

    function load_collection_omr(initial_date='', final_date='', current_date='' ){
        var ajax_url = '{{route($shared['routePath'].".collectionOmrList")}}';
        var t= $('#collection_omr_datatable').DataTable({
            "order": [[ 0, "desc" ]],
            dom: 'Bfrtip',
            lengthMenu: [
            [10, 50, 100, 500, -1],
            [10, 50, 100, 500, "All"]
            ],
            buttons: [
            'pageLength', 'excel'
            ],
            processing: true,
            serverSide: true,
            destroy: true,
            // stateSave: true,
            "ajax" : {
                "url" : ajax_url,
                "dataType": "json",
                "type": "post",
                "data" : {
                "initial_date" : initial_date,
                "final_date" : final_date,
                "current_date" : current_date,
                '_token': '{{ csrf_token() }}'
                },
                // "dataSrc": "records"
            },
                "columns":[
                // { data: 'DT_RowIndex' },
                    {
                    "data": 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                // { data: 'batch.class.name', name: 'batch.class.name', searchable: false},
                // { data: 'batch.name', name: 'batch.name'},
                { data: 'omr_code', name: 'omr_code'},
                { data: 'paper_name', name: 'paper_name'},
                // { data: 'language_type', name: 'language_type'},
                // { data: 'exam_category.category_name', name: 'exam_category.category_name'},
                // { data: function ( row, type, set ) {
                //     if ( row.exam_sub_category != null ) {
                //         return row.exam_sub_category.sub_category_name;
                //     } else {
                //         return '';
                //     }

                // } },

                { data: 'total_question', name: 'total_question'},
                { data: 'examDuration', name: 'examDuration'},
                { data: 'exam_date', name: 'exam_date'},
                { data: 'exam_time', name: 'exam_time'},
                {
                    data: 'isNegative',
                    name: 'isNegative'
                },
                {
                    data: 'numberPerNegative',
                    name: 'numberPerNegative'
                },
                { data: 'status', name: 'status'},
                { data: 'action', name: 'action'},
                ],
        });
    }
</script>


@endsection
