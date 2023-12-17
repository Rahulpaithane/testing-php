@extends('backend.common.baseFile')
@section('content')

@section('title', 'Edit Question')
@section('papgeTitle', 'Edit Question')

<section>
    <div class="row">
        <div class="col-md-12">
            <a href="{{route($shared['routePath'].'.questionBankList')}}" class="btn btn-primary" > <<< Back To Question List</a>
        </div>
    </div>
    <div class="row">
        @if($questionBank->question_type =='Normal')
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <u><h6>Subject : {{$questionBank->subject->name}}</h6></u>
                            <u><h6>Chapter : {{$questionBank->chapter->name}}</h6></u>
                        </div>
                        <div class="col-md-6">
                            <u><h6>Type : {{$questionBank->question_type}}</h6></u>
                            <u><h6>Lang : {{$questionBank->language_type}}</h6></u>
                        </div>
                    </div>
                    <hr/>
                </div>
            </div>
        </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form data="{{ route($shared['routePath'].'.questionManage') }}" id="questionInsertFormData"  method="POST" enctype="multipart/form-data">
                            {{-- <form action="{{ route($shared['routePath'].'.questionManage') }}"   method="POST" enctype="multipart/form-data"> --}}
                                    @csrf
                                <div class="formContentData"></div>
                        </form>
                    </div>
                </div>
            </div>
        @else
        @php 
            $paragraph =json_decode($questionBank->pragraph);
            
            
            $lang=$questionBank->language_type; // Paper Language English, Hindi, Both
            // dd($lang);
        @endphp
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <u><h6>Subject : {{$questionBank->subject->name}}</h6></u>
                            <u><h6>Chapter : {{$questionBank->chapter->name}}</h6></u>
                        </div>
                        <div class="col-md-6">
                            <u><h6>Type : {{$questionBank->question_type}}</h6></u>
                            <u><h6>Lang : {{$questionBank->language_type}}</h6></u>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                       <div class="col-md-12">
                            @if($questionBank->language_type !='Both')
                                {!! $paragraph->$lang !!} 
                            @else            
                                {!! $paragraph->English !!} 
                            @endif
                       </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <h5 ><button type="button" class="btn btn-info " onclick="reloadPage()" >View & Edit Paragraph</button></h5>
                        </div>
                    </div>
                    <hr />
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Qestion</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            
                            @foreach ($questionBank->questionBankInfo as $key=>$info)
                            @php
                           
                                $question =json_decode($info->question);
                                $option =json_decode($info->option);
                            @endphp
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        @if($lang !='Both')
                                            {!! $question->$lang !!} 
                                        @else            
                                            {!! $question->English !!} 
                                        @endif
                                    </td>
                                    <td>
                                        <center>
                                            <a href="javascript:void(0);" onClick="questionEditable({{$info}})" ><i class="fas fa-edit bg-light"  style="font-size:20px; color:green;"></i></a>
                                            &#160;&#160;
                                            <a href="javascript:void(0);" class="delete2023" url="{{route($shared['routePath'].'.deleteQuestion')}}" id="{{$info->id}}" ><i class="fas fa-trash-alt bg-light" style="font-size:20px;color:red;"></i></a>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form data="{{ route($shared['routePath'].'.questionManage') }}" id="questionInsertFormData"  method="POST" enctype="multipart/form-data">
                        {{-- <form action="{{ route($shared['routePath'].'.questionManage') }}"   method="POST" enctype="multipart/form-data"> --}}
                                @csrf
                            <div class="formContentData">

                            </div>
                        </form>
                    
                </div>
            </div>
        </div>

        @endif
    </div>
</section>


<script>
    $(function() {
        editQuestion(); // first load
    });
    function editQuestion(){
      
        var subject = "{{$questionBank->subject->name}}";
        var chapter = "{{$questionBank->chapter->name}}";
        var questionType = "{{$questionBank->question_type}}"; //Normal, Group
        var languageType = "{{$questionBank->language_type}}"; //English, Hindi, Both
        var level = "{{$questionBank->level}}";
        $(".error").html('');
        var id='{{$questionBank->id}}';

        if(questionType == 'Normal'){
            var question=JSON.parse({!! json_encode($questionBank->questionBankInfo[0]->question) !!});
            // var question=JSON.parse(info.question);
            var option = JSON.parse({!! json_encode($questionBank->questionBankInfo[0]->option) !!});
            var description = JSON.parse({!! json_encode($questionBank->questionBankInfo[0]->ans_desc) !!});
            var answer='{{$questionBank->questionBankInfo[0]->answer}}';
            var questionId='{{$questionBank->questionBankInfo[0]->id}}';
            // alert(option.English);
            if(languageType == 'Both'){
                $(".formContentData").html(`
                <input type="hidden" name="updationType" id="updationType" value="paragraphQuestion"  />
                <input type="hidden" name="paragraphIdd" id="paragraphIdd" value="${id}" />
                <input type="hidden" name="questionId" id="questionId" value="${questionId}" />
                    @include('backend.questionBank.bothLang')
                `);

                
            } else {

                $(".formContentData").html(`
                    <input type="hidden" name="updationType" id="updationType" value="paragraphQuestion"  />
                    <input type="hidden" name="paragraphIdd" id="paragraphIdd" value="${id}" />
                    <input type="hidden" name="questionId" id="questionId" value="${questionId}" />
                    @include('backend.questionBank.singleLang')
                `);

            }

            $(".questionLevelOption").html(`
    
                @include('backend.questionBank.component.questionLevelOption')
                
            `);

            $("#questionLevel").val(level);
            $(".modal-title").html('Edit Question');
            if (languageType == "Both") {
                $("#englishQuestion").html(question.English);
                $("#optionEnglishA").html(option.English[0]);
                $("#optionEnglishB").html(option.English[1]);
                $("#optionEnglishC").html(option.English[2]);
                $("#optionEnglishD").html(option.English[3]);
                $("#optionEnglishE").html(option.English[4]);
                $("#answerDescriptionEnglish").html(description.English);

                $("#hindiQuestion").html(question.Hindi);
                $("#optionHindiA").html(option.Hindi[0]);
                $("#optionHindiB").html(option.Hindi[1]);
                $("#optionHindiC").html(option.Hindi[2]);
                $("#optionHindiD").html(option.Hindi[3]);
                $("#optionHindiE").html(option.Hindi[4]);
                $("#answerDescriptionHindi").html(description.Hindi);
            } else if (languageType == "English") {
                $("#englishQuestion").html(question.English);
                $("#optionEnglishA").html(option.English[0]);
                $("#optionEnglishB").html(option.English[1]);
                $("#optionEnglishC").html(option.English[2]);
                $("#optionEnglishD").html(option.English[3]);
                $("#optionEnglishE").html(option.English[4]);
                $("#answerDescriptionEnglish").html(description.English);

            } else if (languageType == "Hindi") {
                $("#hindiQuestion").html(question.Hindi);
                $("#optionHindiA").html(option.Hindi[0]);
                $("#optionHindiB").html(option.Hindi[1]);
                $("#optionHindiC").html(option.Hindi[2]);
                $("#optionHindiD").html(option.Hindi[3]);
                $("#optionHindiE").html(option.Hindi[4]);
                $("#answerDescriptionHindi").html(description.Hindi);
            }
            $('input[name="answer"][value="'+answer+'"]').prop('checked', true);
            // $(".addHeader").style('display', 'none !important');
        } else { // question type Group
            var paragraph=JSON.parse({!! json_encode($questionBank->pragraph) !!});
     
            $(".formContentData").html(`
                <input type="hidden" name="updationType" id="updationType" value="paragraph"  />
                <input type="hidden" name="paragraphIdd" id="paragraphIdd" value="${id}" />

                <input type="hidden" name="subjectId" id="subjectId" required value="${subject}" />
                <input type="hidden" name="chapterId" id="chapterId" required value="${chapter}" />
                <input type="hidden" name="questionType" id="questionType" required value="${questionType}" />
                <input type="hidden" name="languageType" id="languageType" required value="${languageType}" />

                ${languageType !='Both' ? `
                    ${languageType == 'English' ? `
                    @include('backend.questionBank.component.paragraphEnglish')
                    ` : `
                    @include('backend.questionBank.component.paragraphHindi')
                    `}
                ` : `
                    <div class="row">
                        <div class="col-md-12">
                            @include('backend.questionBank.component.paragraphEnglish')
                        </div>
                        <div class="col-md-12">
                            @include('backend.questionBank.component.paragraphHindi')
                        </div>
                    </div>
                ` }
                <div clas="row">
                    <div class="col-md-4">
                        @include('backend.questionBank.component.questionLevelOption')
                    </div>
                </div>

                <div id="errorMessages"></div>

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger">Update</button>
                    </div>
                </div>
            `);

             if(languageType !='Both'){
                if(languageType == 'English'){
                    $("#paragraphEnglish").html(paragraph.English);
                } else {
                    $("#paragraphHindi").html(paragraph.Hindi);
                }
             } else {
                $("#paragraphEnglish").html(paragraph.English);
                $("#paragraphHindi").html(paragraph.Hindi);
             }

             $("#questionLevel").val(level);

            

        }
        $('.summernote').summernote({
                placeholder: 'Write Here',
                toolbar: [
                ['style', ['style']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['height', ['height']],
                ['insert', ['picture', 'link', 'math']],
                ],
                callbacks: {
                onKeydown: function(e) {
                    if (e.key === 'Backspace') {
                    e.preventDefault();
                    handleBackspace();
                    }
                }
                }
            });

            function handleBackspace() {
                const selection = window.getSelection();
                const range = selection.getRangeAt(0);
                const currentNode = range.startContainer;

                // If the current node is a <p> tag, prevent removing it
                if (currentNode.nodeName === 'P') {
                return;
                }

                // If not, proceed with default behavior
                document.execCommand('delete');
            }

        // $('.note-modal').on('hidden.bs.modal', function(event) {
        //     event.preventDefault();
        //     event.stopPropagation();
        //     $('.edu_header_closed').addClass('modal-open');
        // });
        
    }

    function questionEditable(info){
        var id='{{$questionBank->id}}';
        
        var subject = "{{$questionBank->subject->name}}";
        var chapter = "{{$questionBank->chapter->name}}";
        var questionType = "{{$questionBank->question_type}}"; //Normal, Group
        var languageType = "{{$questionBank->language_type}}"; //English, Hindi, Both
        var level = "{{$questionBank->level}}";
        $(".error").html('');

        var questionId=info.id;
        
        var question=JSON.parse(info.question);
        var option=JSON.parse(info.option);
        var description=JSON.parse(info.ans_desc);
        var answer=info.answer;
        // alert(option.English);
        
        $(".formContentData").html(`
            <input type="hidden" name="updationType" id="updationType" value="paragraphQuestion"  />
            <input type="hidden" name="paragraphIdd" id="paragraphIdd" value="${id}" />
            <input type="hidden" name="questionId" id="questionId" value="${questionId}" />
            ${languageType == 'Both' ? `
               <!-- <input type="hidden" name="paragraphEnglish" id="paragraphEnglish" value="${$("#paragraphEnglish").val()}" />
                <input type="hidden" name="paragraphHindi" id="paragraphHindi" value="${$("#paragraphHindi").val()}" /> -->
                @include('backend.questionBank.bothLang')
                ` : `
                ${languageType == 'English' ? `
                    <input type="hidden" name="paragraphEnglish" id="paragraphEnglish" value="${$("#paragraphEnglish").val()}" />
                ` : `
                    <input type="hidden" name="paragraphHindi" id="paragraphHindi" value="${$("#paragraphHindi").val()}" />
                `}
                @include('backend.questionBank.singleLang')
            `}
        `);

        if (languageType == "Both") {
            $("#englishQuestion").html(question.English);
            $("#optionEnglishA").html(option.English[0]);
            $("#optionEnglishB").html(option.English[1]);
            $("#optionEnglishC").html(option.English[2]);
            $("#optionEnglishD").html(option.English[3]);
            $("#optionEnglishE").html(option.English[4]);
            $("#answerDescriptionEnglish").html(description.English);

            $("#hindiQuestion").html(question.Hindi);
            $("#optionHindiA").html(option.Hindi[0]);
            $("#optionHindiB").html(option.Hindi[1]);
            $("#optionHindiC").html(option.Hindi[2]);
            $("#optionHindiD").html(option.Hindi[3]);
            $("#optionHindiE").html(option.Hindi[4]);
            $("#answerDescriptionHindi").html(description.Hindi);
        } else if (languageType == "English") {
            $("#englishQuestion").html(question.English);
            $("#optionEnglishA").html(option.English[0]);
            $("#optionEnglishB").html(option.English[1]);
            $("#optionEnglishC").html(option.English[2]);
            $("#optionEnglishD").html(option.English[3]);
            $("#optionEnglishE").html(option.English[4]);
            $("#answerDescriptionEnglish").html(description.English);

        } else if (languageType == "Hindi") {
            $("#hindiQuestion").html(question.Hindi);
            $("#optionHindiA").html(option.Hindi[0]);
            $("#optionHindiB").html(option.Hindi[1]);
            $("#optionHindiC").html(option.Hindi[2]);
            $("#optionHindiD").html(option.Hindi[3]);
            $("#optionHindiE").html(option.Hindi[4]);
            $("#answerDescriptionHindi").html(description.Hindi);
        }
        $(".modal-title").html('Edit Question');
        $('input[name="answer"][value="'+answer+'"]').prop('checked', true);

        $('.summernote').summernote({
                placeholder: 'Write Here',
                toolbar: [
                ['style', ['style']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['height', ['height']],
                ['insert', ['picture', 'link', 'math']],
                ],
                callbacks: {
                onKeydown: function(e) {
                    if (e.key === 'Backspace') {
                    e.preventDefault();
                    handleBackspace();
                    }
                }
                }
            });

            function handleBackspace() {
                const selection = window.getSelection();
                const range = selection.getRangeAt(0);
                const currentNode = range.startContainer;

                // If the current node is a <p> tag, prevent removing it
                if (currentNode.nodeName === 'P') {
                return;
                }

                // If not, proceed with default behavior
                document.execCommand('delete');
            }

        // $('.note-modal').on('hidden.bs.modal', function(event) {
        //     event.preventDefault();
        //     event.stopPropagation();
        //     $('.edu_header_closed').addClass('modal-open');
        // });
    }

    function reloadPage(){
        location.reload();
    }
    </script>

@endsection