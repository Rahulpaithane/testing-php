<div class="row">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <h6>Govt. Batch Student Report</h6>
                <hr/>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" cellspacing="0" width="100%" id="govtBatch_server_datatable" >
                        <thead>
                            <tr>
                                <th >#Id</th>
                                <th >Class Name</th>
                                <th >Batch</th>
                                <th >Total Student</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <h6>School Batch Student Report</h6>
                <hr/>
                <div class="table-responsive">
                    <table class="table table-striped table-hover " cellspacing="0" width="100%" id="schoolBatch_server_datatable" >
                        <thead>
                            <tr>
                                <th>#Id</th>
                                <th>Class Name</th>
                                <th>Batch</th>
                                <th>Total Student</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <h6>Last 10 Transaction History</h6>
                <hr/>
                <div class="table-responsive">
                    <table class="table table-striped table-hover " cellspacing="0" width="100%" id="transaction10_server_datatable" >
                        <thead>
                            <tr>
                                <th>#Id</th>
                                <th>Student Name</th>
                                <th>Remarks</th>
                                <th>Amount</th>
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

<script>
    $(function() {
        govtBatch_server_datatable(); // first load
        schoolBatch_server_datatable();
        transaction10_server_datatable();
    });
    function govtBatch_server_datatable(){
        var ajax_url = '{{route($shared['routePath'].".totalStudentInBatch")}}';
        var t= $('#govtBatch_server_datatable').DataTable( {
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
                'prepration' : 'Govt',
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
                { data: 'class.name', name: 'class.name', width:'5%'},
                { data: 'name', name: 'name', width:'10%'},
                { data: 'total_student_count', name: 'total_student_count', width:'40%'},
                ],
        });
    }

    function schoolBatch_server_datatable(){
        var ajax_url = '{{route($shared['routePath'].".totalStudentInBatch")}}';
        var t= $('#schoolBatch_server_datatable').DataTable( {
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
                'prepration' : 'School',
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
                { data: 'class.name', name: 'class.name'},
                { data: 'name', name: 'name'},
                { data: 'total_student_count', name: 'total_student_count'},
                ],
        });
    }

    function transaction10_server_datatable(){
        var ajax_url = '{{route($shared['routePath'].".latestTransactionHistory")}}';
        var t= $('#transaction10_server_datatable').DataTable( {
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
                { data: 'student_basic_detail.name', name: 'student_basic_detail.name'},
                { data: 'remarks', name: 'remarks', width:'5%'},
                { data: 'credit', name: 'credit'},
                ],
        });
    }
</script>