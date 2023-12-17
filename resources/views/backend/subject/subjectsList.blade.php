@extends('backend.common.baseFile')
@section('content')

@section('title', 'Manage Subject & Chapter')
@section('papgeTitle', 'Manage Subject & Chapter')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                <button type="button" class="btn btn-primary" onclick="openSubjectModal()" >
                    <i class="fa fa-plus" aria-hidden="true"></i>Add New
                    </button>
            </div>
                <div class="edu_main_wrapper edu_table_wrapper">
                <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                    <div class="tableFullWrapper">

                        <table class="table table-striped table-hover" cellspacing="0" width="100%" id="server_datatable" >
                            <thead>
                                <tr>
                                    {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                    <th style="width:2%; min-width:2%; max-width:2%">#Si.No</th>
                                    <th style="width:15%; min-width:15%; max-width:15%">Class Name</th>
                                    <th style="width:15%%; min-width:15%%; max-width:15%%">Subject Name</th>
                                    <th style="width:25%; min-width:25%; max-width:25%">Chapters</th>
                                    <th style="width:10%; min-width:10%; max-width:10%">Status</th>
                                    <th style="width:10%; min-width:10%; max-width:10%">Action</th>
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
</section>
@include('backend.subject.addSubjects')
 <script>
    $(function() {
        load_data("","",""); // first load
    });

    function load_data(initial_date='', final_date='', current_date='' ){
            var ajax_url = '{{route($shared['routePath'].".subjectChpatersList")}}';
           var t= $('#server_datatable').DataTable( {
                "order": [[ 0, "desc" ]],
                dom: 'Bfrtip',
                lengthMenu: [
                [10, 50, 100, 500, -1],
                [10, 50, 100, 500, "All"]
                ],
                buttons: [
                'pageLength', 'copy', 'pdf', 'print','excel'
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
                        searchable: false,
                        width:'2%'
                    },
                    { data: 'class.name', name: 'class.name'},
                    { data: 'name', name: 'name'},
                    { data: 'chaptersName', name: 'chaptersName', width:'5%'},
                    { data: 'status'},
                    { data: 'action'},
                 ],
            });
        }
    </script>
@endsection
