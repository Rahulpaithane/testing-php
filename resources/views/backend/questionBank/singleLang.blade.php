<!-- Modal Header -->

<input type="hidden" name="subjectId" id="subjectId" required value="${subject}" />
<input type="hidden" name="chapterId" id="chapterId" required value="${chapter}" />
<input type="hidden" name="questionType" id="questionType" required value="${questionType}" />
<input type="hidden" name="languageType" id="languageType" required value="${languageType}" />

<div class="modal-header d-flex justify-content-between">
    <div><h4 class="modal-title">Add Question </h4></div>
    <div class="questionLevelOption">

    </div>
    <div> <button type="button" class="close" data-dismiss="modal">&times;</button></div>
</div>
<!-- Modal body -->

<div class="modal-body singleLangBody">

            <div class="row">


                <div class="col-md-12">
                    ${languageType == 'English' ? `
                        @include('backend.questionBank.component.questionEnglish')`
                        :
                    ` @include('backend.questionBank.component.questionHindi')`
                    }
                </div>

            </div>
            <hr/>
            <div class="row">
                ${languageType == 'English' ? `
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionEnglishA')
                    </div>
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionEnglishB')
                    </div>
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionEnglishC')
                    </div>
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionEnglishD')
                    </div>
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionEnglishE')
                    </div>

                    `
                        :
                    ` <div class="col-md-4">
                        @include('backend.questionBank.component.optionHindiA')
                    </div>
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionHindiB')
                    </div>
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionHindiC')
                    </div>
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionHindiD')
                    </div>
                    <div class="col-md-4">
                        @include('backend.questionBank.component.optionHindiE')
                    </div>

                    `
                }
            </div>

            <div class="row">
                <div class="col-md-8">
                    ${languageType == 'English' ? `
                        @include('backend.questionBank.component.answerDescriptionEnglish')`
                        :
                    ` @include('backend.questionBank.component.answerDescriptionHindi')`
                    }
                </div>
                <div class="col-md-4">
                    @include('backend.questionBank.component.answer')
                    <div id="errorMessages" ></div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                        <center><button type="submit" class="btn btn-success w-100">Save</button></center>
                    </div>

                </div>


</div>
