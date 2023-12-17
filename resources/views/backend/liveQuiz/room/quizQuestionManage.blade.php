@extends('backend.common.baseFile')
@section('content')

@section('title', 'Manage Quiz Question')
@section('papgeTitle', 'Manage Quiz Question')

<section>
    <div class="row">
        <div class="col-md-12">
            {{-- <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
               <a href="{{route($shared['routePath'].'.questionManage')}}" class="btn btn-info" >New Question</a>
            </div> --}}
                <div class="edu_main_wrapper edu_table_wrapper">
                <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                    <div class="tableFullWrapper">
                        <div class="row">
                            <div class="col-md-2">
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
                            <div class="col-md-2">
                                <div class="from-group">
                                    <label>Select Chapter <span class="text-danger"></span></label>
                                    <select name="chapter" id="chapter" class="form-control"  >
                                        <option selected="selected" value="" >All</option>

                                    </select>
                                </div>
                                <div class="error chapterError text-danger" ></div>
                            </div>

                            {{-- <div class="col-md-2">
                                <div class="from-group">
                                    <label>Question Type <span class="text-danger"></span></label>
                                    <select name="questionType" id="questionType" class="form-control" required >
                                        <option selected="selected"  value="" >All</option>
                                        <option   value="Normal" >Normal</option>
                                        <option  value="Group" >Group</option>
                                    </select>
                                </div>
                            </div> --}}
                            <input type="hidden" name="questionType" id="questionType" value="Normal" />
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

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Selection Type</label>
                                        <select name="selectionType" class="form-control" id="selectionType"   >
                                            <option selected="selected" value="" >All</option>
                                            <option  value="Selected" >Selected</option>
                                            <option  value="Not Selected" >Not Selected</option>

                                        </select>
                                </div>
                            </div>
                            <div class="col-md-2 mt-4">
                                <div class="from-group mt-2">
                                    <button type="button" class="btn btn-success w-100" onClick="manageQuestionFormat()" ><i class="fas fa-filter"></i>Filter</button>
                                </div>
                            </div>

                        </div>
                        <hr/>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%" id="server_datatable" >
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checkAllQuizTableRow"></th>
                                        <th>#Id</th>
                                        <th>Type</th>
                                        <th>Lang</th>
                                        <th>Paragraph/Q</th>
                                        <th>Answer</th>
                                        <th>Level</th>
                                        <th>Subject</th>
                                        <th>Chapter</th>
                                        {{-- <th>Status</th> --}}
                                        {{-- <th>Action</th> --}}
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
@php
$qu=$roomInfo->question_id;
// echo $qu[0];
@endphp
<script>
    $(function(){
       
        let as = '{{$roomInfo->question_id}}';
        let jsonString = as.replace(/&quot;/g, '"');
        AllQuizQuesArray = JSON.parse(jsonString);

        $('.selectedQuestionCount').html(AllQuizQuesArray.length);
        $('#quetionIdsArr').val(JSON.stringify(AllQuizQuesArray));
        $('.totalQuestions').val(AllQuizQuesArray.length);
        // console.log(originalArray);
        // alert(originalArray.length);
    });
</script>

<script>
    $(function() {
        load_data("","","", "", "", ""); // first load
    });
    function load_data(subject='', chapter='', questionType='', languageType='', questionLevel='', selectionType='' ){
        var ajax_url = '{{route($shared['routePath'].".quizQuestionList")}}';
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
                "roomId" : '{{$roomInfo->id}}',
                "subject" : subject,
                "chapter" : chapter,
                "questionType" : questionType,
                "languageType" : languageType,
                "questionLevel" : questionLevel,
                "selectionType" : selectionType,
                '_token': '{{ csrf_token() }}'
                },
                // "dataSrc": "records"
            },

                "columns":[
                // { data: 'DT_RowIndex' },
                { data: 'inputCheck', name: 'inputCheck', orderable: false,
                    searchable: false},
                    {
                    "data": 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'question_type', name: 'question_type'},
                { data: 'lang', name: 'lang'},
                { data: 'paragraphInfo', name: 'paragraphInfo'},
                { data: 'question_bank_info.0.answer', name: 'question_bank_info.0.answer'},
                { data: 'level', name: 'level'},
                { data: 'subject.name', name: 'subject.name'},
                { data: 'chapter.name', name: 'chapter.name'},
                // { data: 'status', name: 'status'},
                // { data: 'action', name: 'action'},
                ],
                // "initComplete": function () {
                //     // Call your other function here
                //     // otherFunction();
                //     resetQuizSelectedData();
                //     // alert('hi');
                // },
        });
    }

    function manageQuestionFormat(){
        var subject = $("#subject").val();
        var chapter = $("#chapter").val();
        var questionType = $("#questionType").val(); //Normal, Group
        var languageType = $("#languageType").val(); //English, Hindi, Both
        var questionLevel = $("#questionLevel").val(); //English, Hindi, Both
        var selectionType = $("#selectionType").val(); // Selected , Not Selected , All
        load_data(subject,chapter,questionType, languageType, questionLevel, selectionType);
    }
</script>

<div class="createDivWrapper edu_add_question create_ppr_popup ">
    <div class="edu_admin_informationdiv sectionHolder">
        <div class="ppr_popup_inner">
            <p class="edu_sub_title nomarginbtm">Quiz Room : {{$roomInfo->name}}</p>
                {{-- <hr style="margin:0px; border:1px; border-top: 2px solid rgba(0,0,0,.1);" /> --}}
            <div class="footer_popup_inner" style="border-top:1px solid white">
                
                <div class="fp_btn_wrpa">
                    
                    <p class="edu_sub_title nomarginbtm"><span class="selectedQuestionCount">0</span> Question Selected</p>
                </div>
                <div class="fp_btn_wrpa">
                    <div class="edu_addPaper_wrap">
                        <form data="{{ route($shared['routePath'].'.quizQuestionManage',['id'=>$roomInfo->id]) }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" class="form-control" name="quetionIdsArr" id="quetionIdsArr" value="" />
                            <input type="hidden" class="totalQuestions" name="total_question" value="0">
                            <button class="btn btn-primary addQuestionLocalStorage"><i class="icofont-plus"></i>Update</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





@endsection
