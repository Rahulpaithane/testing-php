@extends('backend.common.baseFile')
@section('content')

@section('title', 'Manage Paper')
@section('papgeTitle', 'Manage Paper')

<section>
    <div class="row">
        <div class="col-md-12">
          
                <div class="edu_main_wrapper edu_table_wrapper">
                <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                    <div class="tableFullWrapper">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%" id="server_datatable" >
                                <thead>
                                    <tr>
                                        {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                        <th>#Id</th>
                                        <th>Exam</th>
                                        <th>Batch</th>
                                        <th>Paper Name</th>
                                        <th>Language</th>
                                        <th>Cat</th>
                                        <th>SubCat</th>
                                        <th>T.Que</th>
                                        <th>Time (min)</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <th>PDF/PPT</th>
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
        var ajax_url = '{{route($shared['routePath'].".managePaper")}}';
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
                // { data: 'batch.class.name', name: 'batch.class.name'},
                { data: 'batch.class.name', name: 'batch.class.name', defaultContent: '', searchable: true, data: 'batch.class.name' },
                { data: 'batch.name', name: 'batch.name', searchable: false,},
                { data: 'paper_name', name: 'paper_name'},
                { data: 'language_type', name: 'language_type'},
                { data: 'exam_category.category_name', name: 'examCategory.category_name', searchable: true,},
                { data: function ( row, type, set ) {
                    if ( row.exam_sub_category != null ) {
                        return row.exam_sub_category.sub_category_name;
                    } else {
                        return '';
                    }

                }, name: 'examSubCategory.sub_category_name', searchable: true, },

                { data: 'total_question', name: 'total_question'},
                { data: 'total_duration', name: 'total_duration'},
                { data: 'exam_date', name: 'exam_date'},
                { data: 'exam_time', name: 'exam_time'},
                { data: 'status', name: 'status'},
                { data: 'action', name: 'action'},
                { data: 'action2', name: 'action2'},
                ],
        });
    }
</script>
@endsection
