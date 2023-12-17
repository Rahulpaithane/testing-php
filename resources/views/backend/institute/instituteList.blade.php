@extends('backend.common.baseFile')
@section('content')

@section('title', 'Institute List')
@section('papgeTitle', 'Institute List')
 
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                <a  class="btn btn-primary" href="{{route('admin.newInstitute')}}" >
                    <i class="fa fa-plus" aria-hidden="true"></i>New Institute
                </a>
            </div>
                <div class="edu_main_wrapper edu_table_wrapper">
                <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                    <div class="tableFullWrapper">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%" id="server_datatable" >
                                <thead>
                                    <tr>
                                        {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                        <th>#Id</th>
                                        <th>Domain</th>
                                        <th>Institute Name</th>
                                        <th>Owner Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Profile</th>
              
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

<script>
    $(function() {
        load_data("","",""); // first load
    });
    function load_data(initial_date='', final_date='', current_date='' ){
        var ajax_url = '{{route($shared['routePath'].".instituteList")}}';
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
                { data: 'userInterFace', name: 'userInterFace'},
                { data: 'institute_name', name: 'institute_name'},
                { data: 'ownerName', name: 'ownerName'},
                { data: 'ownerMobileNo', name: 'ownerMobileNo'},
                { data: 'ownerEmailId', name: 'ownerEmailId'},
                { data: 'profile', name: 'profile'},
                ],
        });
    }
</script>

@endsection