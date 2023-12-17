@extends('backend.common.baseFile')
@section('content')

@section('title', 'Manage Student')
@section('papgeTitle', 'Manage Student')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                {{-- <a href="#input_feilds_teacher" class="edu_admin_btn openPopupLink ml-2 addTeacherPop"><i class="icofont-plus"></i>Add Book</a> --}}
                {{-- <button type="button" class="btn btn-primary" onclick="openBatchModal()" >
                    <i class="fa fa-plus" aria-hidden="true"></i>New Batch
                    </button> --}}
            </div>

            <div class="edu_main_wrapper edu_table_wrapper">
                <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                    <div class="tableFullWrapper">

                        <table class="table table-striped table-hover " cellspacing="0" width="100%" id="server_datatable" >
                            <thead>
                                <tr>
                                    {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Gender</th>
                                    <th>D.O.B</th>
                                    <th>AadharNo</th>
                                    <th>Address</th>
                                    <th>Prepration</th>
                                    <th>Device_id</th>
                                    <th>App_Version</th>
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
</section>

 <script>
    $(function() {
        load_data("","",""); // first load

    });

    function load_data(initial_date='', final_date='', current_date='' ){
            var ajax_url = '{{route($shared['routePath'].".manageStudent")}}';
            // alert(ajax_url);
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
                        searchable: false
                    },
                    { data: 'profile_image'},
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},
                    { data: 'mobile', name: 'mobile'},
                    { data: 'gender', name: 'gender'},
                    { data: 'dob', name: 'dob'},
                    { data: 'aadhar_no', name: 'aadhar_no'},
                    { data: 'address'},
                    { data: 'prepration', name: 'prepration'},
                    { data: 'device_id'},
                    { data: 'app_version'},
                    { data: 'status'},
                    { data: 'action'},
                 ],
            });
        }
    </script>

@endsection
