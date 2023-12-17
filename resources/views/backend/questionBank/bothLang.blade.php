<input type="hidden" name="subjectId" id="subjectId" required value="${subject}" />
<input type="hidden" name="chapterId" id="chapterId" required value="${chapter}" />
<input type="hidden" name="questionType" id="questionType" required value="${questionType}" />
<input type="hidden" name="languageType" id="languageType" required value="${languageType}" />

            <!-- Modal Header -->
            <div class="modal-header addHeader d-flex justify-content-between">
                <div><h4 class="modal-title">Add Question </h4></div>
                <div class="questionLevelOption"></div>
                <div class="quInfo">
                <div> <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                </div>
            </div>
            <div class="editHeader"></div>

            <!-- Modal body -->
            <div class="modal-body bothLangBody">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                @include('backend.questionBank.component.questionEnglish')
                            </div>
                            <div class="col-md-12">
                                @include('backend.questionBank.component.questionHindi')
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionEnglishA')
                            </div>
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionHindiA')
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionEnglishB')
                            </div>
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionHindiB')
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionEnglishC')
                            </div>
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionHindiC')
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionEnglishD')
                            </div>
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionHindiD')
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionEnglishE')
                            </div>
                            <div class="col-md-6">
                                @include('backend.questionBank.component.optionHindiE')
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                @include('backend.questionBank.component.answerDescriptionEnglish')
                            </div>
                            <div class="col-md-6">
                                @include('backend.questionBank.component.answerDescriptionHindi')
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @include('backend.questionBank.component.answer')
                            </div>

                        </div>
                        <div id="errorMessages" ></div>

                        <div class="row">
                            <div class="col-md-12">
                                <center><button class="btn btn-success w-100">Save</button></center>
                            </div>

                        </div>

                    </div>
                </div>

            </div>




