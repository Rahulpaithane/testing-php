@extends('backend.common.baseFile')
@section('content')

@section('title', 'Manage Notification')
@section('papgeTitle', 'Manage Notification')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                {{-- <a href="#input_feilds_teacher" class="edu_admin_btn openPopupLink ml-2 addTeacherPop"><i class="icofont-plus"></i>Add Book</a> --}}
                <button type="button" class="btn btn-primary" onclick="openNotificationModal()" >
                    <i class="fa fa-plus" aria-hidden="true"></i>New Notification
                    </button>
            </div>

            <div class="edu_main_wrapper1 edu_table_wrapper1">
                <div class="edu_admin_informationdiv1 sectionHolder1 dropdown_height">
                    <div class="tableFullWrapper1">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover " cellspacing="0" width="100%" id="server_datatable" >
                                <thead>
                                    <tr>
                                        {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                        <th>#</th>
                                        
                                        <th>Taget Student</th>
                                        <th>Class</th>
                                        <th style="min-width: 200px">Title</th>
                                        <th style="min-width: 400px">Message</th>
                                        <th>Date</th>
                                        <th style="min-width: 100px">Status</th>
                                        <th style="min-width: 100px">Action</th>
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
</section>
@include('backend.notification.newNotification')
 <script>
    $(function() {
        load_data("","",""); // first load

    });

    function load_data(initial_date='', final_date='', current_date='' ){
        var ajax_url = '{{route($shared['routePath'].".notificationList")}}';
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
                { data: 'title', name: 'title'},
                
                { data: 'class.name', name: 'class.name',
                
                defaultContent: '', // Display an empty string as default content
                render: function(data, type, full, meta) {
                    // Check if 'class' relation exists and has 'name' property
                 
                    return full.class && full.class.name ? full.class.name : '';
                }},
                { data: 'targetStudent', name: 'targetStudent'},
                { data: 'message', name: 'message'},
                { data: 'created_at', name: 'created_at'},
                { data: 'status'},
                { data: 'action'},
                ],
        });
    }
    </script>

@endsection
