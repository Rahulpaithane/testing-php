@extends('backend.common.baseFile')
@section('content')
@section('title', 'Manage Book/PDF')
@section('papgeTitle', 'Manage Book/PDF')
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                <button type="button" class="btn btn-primary" onclick="openBookModal()" >
                    <i class="fa fa-plus" aria-hidden="true"></i>New Book
                    </button>
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
                                        <th>Book Type</th>
                                        <th>Book Name</th>
                                        <th>Book Author</th>
                                        <th>Book Publication</th>
                                        <th>Book For Class</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Image</th>
                                        <th>Attachment</th>
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
</section>
@include('backend.books.newBooks')
<script>
    $(function() {
        load_data("","",""); // first load
    });
    function load_data(initial_date='', final_date='', current_date='' ){
        var ajax_url = '{{route($shared['routePath'].".booksList")}}';
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
                { data: 'book_type', name: 'book_type'},
                { data: 'book_name', name: 'book_name'},
                { data: 'author', name: 'author'},
                { data: 'publication', name: 'publication'},
                { data: 'class', name: 'class'},
                { data: 'price', name: 'price'},
                { data: 'stock', name: 'stock'},
                { data: 'imgFile', name: 'imgFile'},
                { data: 'pdfFile', name: 'pdfFile'},
                { data: 'status', name: 'status'},
                { data: 'action', name: 'action'},
                ],
        });
    }
</script>
@endsection
