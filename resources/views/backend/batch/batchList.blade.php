@extends('backend.common.baseFile')
@section('content')

@section('title', 'Manage Batch')
@section('papgeTitle', 'Manage Batch')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                {{-- <a href="#input_feilds_teacher" class="edu_admin_btn openPopupLink ml-2 addTeacherPop"><i class="icofont-plus"></i>Add Book</a> --}}
                <button type="button" class="btn btn-primary" onclick="openBatchModal()" >
                    <i class="fa fa-plus" aria-hidden="true"></i>New Batch
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
                                        <th style="min-width: 300px">Batch Name</th>
                                        <th>Batch Image</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Offer Price</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th style="min-width: 400px">Features</th>
                                        <th style="min-width: 100px">Type</th>
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
@include('backend.batch.newBatch')
 <script>
    $(function() {
        load_data("","",""); // first load

    });

    function load_data(initial_date='', final_date='', current_date='' ){
            var ajax_url = '{{route($shared['routePath'].".batchList")}}';
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
                    { data: 'name', name: 'name'},
                    { data: 'batch_image', name: 'batch_image'},
                    { data: 'batch_type', name: 'batch_type'},
                    { data: 'batch_price', name: 'batch_price'},
                    { data: 'batch_offer_price', name: 'batch_offer_price'},
                    { data: 'start_date', name: 'start_date'},
                    { data: 'end_date', name: 'end_date'},
                    { data: 'profile'},
                    { data : 'batchType'},
                    { data: 'status'},
                    { data: 'action'},
                 ],
            });
        }
    </script>

@endsection
