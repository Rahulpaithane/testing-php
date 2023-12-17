@extends('backend.common.baseFile')
@section('content')

@section('title', 'Add Question')
@section('papgeTitle', 'Add New Question')

<section>
    <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
        <a href="{{route($shared['routePath'].'.bulkUploadQuestion')}}" class="btn btn-primary" > <i class="fas fa-plus"></i> Bulk Upload Question</a>
        <a href="{{route($shared['routePath'].'.questionBankList')}}" class="btn btn-primary" > <i class="fas fa-list"></i> Question List</a>
    </div>
    @include('backend.questionBank.questionFilter')
    <br>
    <br>
    <div class="row">
        <div class="col-md-12 questionFormat">
            <div class="card ">

            </div>

        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="myModal" data-bs-keyboard="false" data-bs-backdrop="static">
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

</section>

<script>
    function manageQuestionFormat () {
        var subject = $("#subject").val();
        var chapter = $("#chapter").val();
        var questionType = $("#questionType").val(); //Normal, Group
        var languageType = $("#languageType").val(); //English, Hindi, Both
        $(".error").html('');
        if(subject ==''){
            $(".subjectError").html('Select Subject');
        } else if(chapter ==''){
            $(".chapterError").html('Select Subject');
        } else {
            if(questionType == 'Normal' && languageType =='Both' ){
                $(".questionFormat").html(`
                    <div class="card">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-8">

                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                        Add New Question
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                `);
                $(".formContentData").html(`
                    @include('backend.questionBank.bothLang')
                `);

                $('.bothLangBody').prepend(`
                    @include('backend.questionBank.component.previousAskedYears')
                `);

                $(".questionLevelOption").html(`

                    @include('backend.questionBank.component.questionLevelOption')
                `);
            } else if(questionType == 'Normal' && languageType !='Both'){
                $(".questionFormat").html(`

                    <div class="card">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-8">

                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                        Add New Question
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                `);

                $(".formContentData").html(`
                    @include('backend.questionBank.singleLang')
                `);

                $('.singleLangBody').prepend(`
                    @include('backend.questionBank.component.previousAskedYears')
                    `);
                $(".questionLevelOption").html(`

                    @include('backend.questionBank.component.questionLevelOption')
                `);
            } else if(questionType == 'Group'){
                $(".questionFormat").html(`

                    <div class="card">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-9">
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
                                    @include('backend.questionBank.component.previousAskedYears')

                                </div>
                                <div class="col-md-2 paragraphQuestionInfo "  >
                                    <center>
                                        @include('backend.questionBank.component.questionLevelOption')
                                        <br/><br/>
                                        <div id="errorMessages" ></div>
                                        <button type="button" class="btn btn-primary" onClick="createParagraphQuestion()" >
                                            Create Question
                                        </button>
                                    </center>
                                </div>
                                <div class="col-md-1"  >
                                    <strong class="text-center text-danger" >No. Of Q.</strong>
                                    <hr />
                                    <div id="paragrapquestionCount" ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="groupQuestionBox"></div>


                `);
                $('.formContentData').html('');

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

            $('.note-modal').on('hidden.bs.modal', function(event) {
                event.preventDefault();
                event.stopPropagation();
                $('.edu_header_closed').addClass('modal-open');
            });


        }



    }


</script>

<script>
    function createParagraphQuestion(){
        var subject = $("#subject").val();
        var chapter = $("#chapter").val();
        var questionType = $("#questionType").val(); //Normal, Group
        var languageType = $("#languageType").val(); //English, Hindi, Both
        var questionLevel = $("#questionLevel").val();
        var previousAskedYear = $("#previousAskedYear").val();
        // alert(previousAskedYear).val();
        $('#previousAskedYear').prop("disabled", true);

        var isValid = false;
        $('#errorMessages').empty(); // Clear previous error messages

        $('.summernoterequired').each(function() {

        var content = $(this).summernote('code'); // Get Summernote content as HTML
        //   alert(content);
        // Check if content is empty or contains only whitespace
        if (!content.trim() || content === '<p><br></p>') {
            isValid = false;
            var fieldName = $(this).attr('name');
            // alert(fieldName);
            var errorMessage = 'Please enter content for ' + fieldName + '.';
            $('#errorMessages').append('<p class="text-danger">' + errorMessage + '</p>');
        } else {
            isValid = true;
        }
        });
        if (isValid) {
            if(languageType !='Both' ){
                if(languageType =='English'){
                    $('#paragraphEnglish').summernote('disable');
                } else {
                    $('#paragraphHindi').summernote('disable');

                }

            } else {
                $('#paragraphEnglish').summernote('disable');
                $('#paragraphHindi').summernote('disable');

            }

            $(".paragraphQuestionInfo").html(`
                <center>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                        Add New Question
                    </button>
                </center>
            `);

            $(".formContentData").html(`
                <input type="hidden" name="questionLevel" id="questionLevel" value="${questionLevel}"  />
                <input type="hidden" name="paragraphIdd" id="paragraphIdd" value="" />
                <input type="hidden" name="previousAskedYear" id="previousAskedYear" value="${previousAskedYear}" />
                ${languageType == 'Both' ? `
                    <input type="hidden" name="paragraphEnglish" id="paragraphEnglish" value="${$("#paragraphEnglish").val()}" />
                    <input type="hidden" name="paragraphHindi" id="paragraphHindi" value="${$("#paragraphHindi").val()}" />
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

            $('.note-modal').on('hidden.bs.modal', function(event) {
                event.preventDefault();
                event.stopPropagation();
                $('.edu_header_closed').addClass('modal-open');
            });
        }







    }
</script>

<script>
    // $('#myModal').modal({backdrop: 'static', keyboard: false});
    //  $(".activeModal").on('hidden.bs.modal', function(){
    //     alert('The modal is now hidden.');
    // });



//  var myModalEl = $('.note-modal.show');
//     myModalEl.addEventListener('hidden.bs.modal', function (event) {
//         alert('asd');
//   // do something...
// })
</script>

@endsection
