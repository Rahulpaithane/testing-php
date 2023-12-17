@extends('backend.common.baseFile')
@section('content')

@section('title', 'Manage Student Transaction History')
@section('papgeTitle', 'Manage Student Transaction History')

<section>
    <div class="row">
        <div class="col-md-12">
            {{-- <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
      
            </div> --}}

            <div class="edu_main_wrapper edu_table_wrapper">
                <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                    <div class="tableFullWrapper">

                        <table class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%" id="server_datatable" >
                            <thead>
                                <tr>
                                    {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Purchase Product</th>
                                    <th>Transaction id</th>
                                    <th>Amount</th>
                                    <th>Transaction Date</th>
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
            var ajax_url = '{{route($shared['routePath'].".studentTransactionController")}}';
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
                    { data: 'student_basic_detail.name', name: 'studentBasicDetail.name'},
                    { data: 'remarks', name: 'remarks'},
                    { data: function ( row, type, set ) {
                    
                        let modeDetails = row.payment_mode_details;
                        let decodedString = modeDetails.replace(/&quot;/g, '"');
                        let modee = JSON.parse(decodedString);
                        return modee.razorpay_payment_id;

                }, searchable: false },
                    // { data: 'payment_mode_details.razorpay_payment_id', name: 'payment_mode_details.razorpay_payment_id'},
                    { data: 'credit', name: 'credit'},
                    { data: 'created_at', name: 'created_at'},

                 ],
            });
        }
    </script>

@endsection
