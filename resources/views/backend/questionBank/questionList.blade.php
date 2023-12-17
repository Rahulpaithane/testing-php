@extends('backend.common.baseFile')
@section('content')

@section('title', 'Question List')
@section('papgeTitle', 'Question List')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
               <a href="{{route($shared['routePath'].'.questionManage')}}" class="btn btn-primary" > <i class="fas fa-plus"></i> New Question</a>
               <a href="{{route($shared['routePath'].'.bulkUploadQuestion')}}" class="btn btn-primary" > <i class="fas fa-plus"></i> Bulk Upload Question</a>
            </div>

              <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home">Normal Question</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#menu1">Paragraph Question</a>
                    </li>

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div id="home" class="tab-pane active"><br>


                        <div class="edu_main_wrapper1 edu_table_wrapper1">
                            <div class="edu_admin_informationdiv1 sectionHolder1 dropdown_height1">
                                <div class="tableFullWrapper1 table-normler">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Select Subject <span class="text-danger">*</span></label>
                                                <select name="subject" id="subject" class="form-control" onChange="fetchChapter()" url="{{route($shared['routePath'].'.fetchChapter')}}" required >
                                                    <option selected="selected" value="" >All</option>
                                                    @foreach ($subject as $item)

                                                        <option value="{{$item->id}}">{{$item->name}} ( {{$item->class->name}} )</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="error subjectError text-danger" ></div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Select Chapter <span class="text-danger"></span></label>
                                                <select name="chapter" id="chapter" class="form-control"  >
                                                    <option selected="selected" value="" >All</option>

                                                </select>
                                            </div>
                                            <div class="error chapterError text-danger" ></div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="from-group">
                                                <label>Language Type <span class="text-danger"></span></label>
                                                <select name="languageType" id="languageType" class="form-control"  >
                                                    <option selected="selected"  value="" >All</option>
                                                    <option  value="English" >English</option>
                                                    <option  value="Hindi" >Hindi</option>
                                                    <option  value="Both" >Both</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Question Level</label>
                                                    <select name="questionLevel" class="form-control" id="questionLevel"  required >
                                                        <option selected="selected" value="" >All</option>
                                                        <option  value="Low" >Low</option>
                                                        <option  value="Medium" >Medium</option>
                                                        <option  value="High" >High</option>
                                                        <option  value="Advance" >Advance</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-4">
                                            <div class="from-group mt-2">
                                                <button type="button" class="btn btn-success w-100" onClick="manageQuestionFormatNormal()" ><i class="fas fa-filter"></i>Filter</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="for-group">
                                                <label>Question Id</label>
                                                <input type="text" class="form-control" id="uniqeQuestionId" placeholder="Enter Question Id" required  />
                                            </div>
                                        </div>

                                        <div class="col-md-2 mt-4">
                                            <div class="from-group mt-2">
                                                <button type="button" class="btn btn-success w-100" onClick="manageQuestionFormatQuestionId()" ><i class="fas fa-filter"></i>Filter</button>
                                            </div>
                                        </div>

                                    </div>
                                    <hr/>


                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover " cellspacing="0" width="100%" id="server_datatable" >
                                            <thead>
                                                <tr>
                                                    {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                                    <th>#Id</th>
                                                    <th>Type</th>
                                                    <th>Lang</th>
                                                    <th style="min-width: 500px">Question</th>
                                                    <th style="min-width: 200px">Option A</th>
                                                    <th style="min-width: 200px">Option B</th>
                                                    <th style="min-width: 200px">Option C</th>
                                                    <th style="min-width: 200px">Option D</th>
                                                    <th style="min-width: 200px">Option E</th>
                                                    <th>Answer</th>
                                                    <th>Lvevel</th>
                                                    <th>Subject</th>
                                                    <th>Chapter</th>
                                                    <th>Status</th>
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
                    <div id="menu1" class="tab-pane fade"><br>

                        <div class="edu_main_wrapper edu_table_wrapper">
                            <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                                <div class="tableFullWrapper table-grouper">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Select Subject <span class="text-danger">*</span></label>
                                                <select name="subject" id="subject" class="form-control" onChange="fetchChapter()" url="{{route($shared['routePath'].'.fetchChapter')}}" required >
                                                    <option selected="selected" value="" >All</option>
                                                    @foreach ($subject as $item)

                                                        <option value="{{$item->id}}">{{$item->name}} ( {{$item->class->name}} )</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="error subjectError text-danger" ></div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Select Chapter <span class="text-danger"></span></label>
                                                <select name="chapter" id="chapter" class="form-control"  >
                                                    <option selected="selected" value="" >All</option>

                                                </select>
                                            </div>
                                            <div class="error chapterError text-danger" ></div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="from-group">
                                                <label>Language Type <span class="text-danger"></span></label>
                                                <select name="languageType" id="languageType" class="form-control"  >
                                                    <option selected="selected"  value="" >All</option>
                                                    <option  value="English" >English</option>
                                                    <option  value="Hindi" >Hindi</option>
                                                    <option  value="Both" >Both</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Question Level</label>
                                                    <select name="questionLevel" class="form-control" id="questionLevel"  required >
                                                        <option selected="selected" value="" >All</option>
                                                        <option  value="Low" >Low</option>
                                                        <option  value="Medium" >Medium</option>
                                                        <option  value="High" >High</option>
                                                        <option  value="Advance" >Advance</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-4">
                                            <div class="from-group mt-2">
                                                <button type="button" class="btn btn-success w-100" onClick="manageQuestionFormatParagraph()" ><i class="fas fa-filter"></i>Filter</button>
                                            </div>
                                        </div>

                                    </div>
                                    <hr/>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%" id="group_server_datatable" >
                                            <thead>
                                                <tr>
                                                    {{-- <th><input type="checkbox" class="checkAllAttendance"></th> --}}
                                                    <th>#Id</th>
                                                    <th>Type</th>
                                                    <th>Lang</th>
                                                    <th>Paragraph</th>
                                                    <th>Answer</th>
                                                    <th>Level</th>
                                                    <th>Subject</th>
                                                    <th>Chapter</th>
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
    </div>
</section>

<script>
    $(function() {
        load_data("","","","Normal","",""); // first load
        load_group_data("","","Group","","");
    });
    function load_data(questionId="",subject='', chapter='', questionType='', languageType='', questionLevel='' ){
        var ajax_url = '{{route($shared['routePath'].".questionBankList")}}';
        var t= $('#server_datatable').DataTable( {
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
                "questionId" : questionId,
                "subject" : subject,
                "chapter" : chapter,
                "questionType" : questionType,
                "languageType" : languageType,
                "questionLevel" : questionLevel,
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
                { data: 'question_type', name: 'question_type'},
                { data: 'lang', name: 'lang'},
                { data: 'paragraphInfo', name: 'paragraphInfo', },
                { data: 'optionA', name: 'optionA',},
                { data: 'optionB', name: 'optionB', },
                { data: 'optionC', name: 'optionC', },
                { data: 'optionD', name: 'optionD', },
                { data: 'optionE', name: 'optionE', },
                { data: 'question_bank_info.0.answer', name: 'question_bank_info.0.answer', searchable: false},
                { data: 'level', name: 'level'},
                { data: 'subject.name', name: 'subject.name'},
                { data: 'chapter.name', name: 'chapter.name'},
                { data: 'status', name: 'status'},
                { data: 'action', name: 'action'},
                ],
        //         "columnDefs": [
        //             { 
        //                 "targets": [3, 4, 5, 6, 7, 8],
        //                 // "width": "100px",
        //                 "max-width": "100px",
        //                 "word-wrap": "break-word"
        //             },  // Columns 4-9 with a width of 100px
        //     { "targets": [9], "width": "150px" }, // Column 10 with a width of 150px
        //     // Add more columnDefs as needed
        // ]
        });
    }

    function load_group_data(subject='', chapter='', questionType='', languageType='', questionLevel='' ){
        var ajax_url = '{{route($shared['routePath'].".paragraphQuestionBankList")}}';
        var t= $('#group_server_datatable').DataTable( {
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
                "subject" : subject,
                "chapter" : chapter,
                "questionType" : questionType,
                "languageType" : languageType,
                "questionLevel" : questionLevel,
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
                { data: 'question_type', name: 'question_type'},
                { data: 'lang', name: 'lang'},
                { data: 'paragraphInfo', name: 'paragraphInfo'},
                { data: 'question_bank_info.0.answer', name: 'question_bank_info.0.answer', searchable: false},
                { data: 'level', name: 'level'},
                { data: 'subject.name', name: 'subject.name'},
                { data: 'chapter.name', name: 'chapter.name'},
                { data: 'status', name: 'status'},
                { data: 'action', name: 'action'},
                ],
        });
    }

    function manageQuestionFormatNormal(){
        var subject = $(".table-normler #subject").val();
        var chapter = $(".table-normler #chapter").val();
        var questionType = 'Normal'; //Normal, Group
        var languageType = $(".table-normler #languageType").val(); //English, Hindi, Both
        var questionLevel = $(".table-normler #questionLevel").val(); //English, Hindi, Both

        load_data("",subject,chapter,questionType, languageType, questionLevel);
    }

    function manageQuestionFormatQuestionId() {
        var questionId = $(".table-normler #uniqeQuestionId").val();
       if(questionId  !=''){
        load_data(questionId, "", "", "", "", "");
       } else {
        alert("Enter Question Id");
       }
        
    }

    function manageQuestionFormatParagraph(){
        var subject = $(".table-grouper #subject").val();
        var chapter = $(".table-grouper #chapter").val();
        var questionType = 'Group'; //Normal, Group
        var languageType = $(".table-grouper #languageType").val(); //English, Hindi, Both
        var questionLevel = $(".table-grouper #questionLevel").val(); //English, Hindi, Both

        load_group_data(subject,chapter,questionType, languageType, questionLevel);
    }
</script>

<!-- The Modal -->
<div class="modal fade" id="editQuestionModal">
    <div class="modal-dialog modal-xl" style="min-width:80%">
        <div class="modal-content">


            <form data="{{ route($shared['routePath'].'.questionManage') }}" id="questionInsertFormData"  method="POST" enctype="multipart/form-data">
            {{-- <form action="{{ route($shared['routePath'].'.questionManage') }}"   method="POST" enctype="multipart/form-data"> --}}
                    @csrf
                <div class="formContentData"></div>
            </form>

        </div>
    </div>
</div>

<script>
function editQuestion(data=''){
    alert(JSON.stringify(data));
    var subject = data.subject_id;
    var chapter = data.chapter_id;
    var questionType = data.question_type; //Normal, Group
    var languageType = data.language_type; //English, Hindi, Both
    $(".error").html('');
    $(".formContentData").html(`

        @include('backend.questionBank.bothLang')
    `);

    $(".questionLevelOption").html(`

        @include('backend.questionBank.component.questionLevelOption')

    `);
    $("#questionLevel").val(data.level);

    $("#editQuestionModal").modal('show');
}
</script>


@endsection
