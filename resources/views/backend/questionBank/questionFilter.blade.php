<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                    <div class="row"> 
                        <div class="col-md-3">
                            <div class="from-group">
                                <label>Select Subject <span class="text-danger">*</span></label>
                                <select name="subject" id="subject" class="form-control" onChange="fetchChapter()" url="{{route($shared['routePath'].'.fetchChapter')}}" required >
                                    <option selected="selected" hidden="true"  value="" >Selct Subject</option>
                                    @foreach ($subject as $item)
                                        <option value="{{$item->id}}">{{$item->name}} ( {{$item->class->name}} )</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="error subjectError text-danger" ></div>
                        </div>
                        <div class="col-md-3">
                            <div class="from-group">
                                <label>Select Chapter <span class="text-danger">*</span></label>
                                <select name="chapter" id="chapter" class="form-control" required >
                                    <option selected="selected" hidden="true" value="" >Select</option>

                                </select>
                            </div>
                            <div class="error chapterError text-danger" ></div>
                        </div>

                        <div class="col-md-2">
                            <div class="from-group">
                                <label>Question Type <span class="text-danger">*</span></label>
                                <select name="questionType" id="questionType" class="form-control" required >
                                    <option selected="selected"  value="Normal" >Normal</option>
                                    <option  value="Group" >Group</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="from-group">
                                <label>Language Type <span class="text-danger">*</span></label>
                                <select name="languageType" id="languageType" class="form-control" required >
                                    <option selected="selected"  value="English" >English</option>
                                    <option  value="Hindi" >Hindi</option>
                                    <option  value="Both" >Both</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mt-3">
                            <div class="from-group mt-2">
                                <button type="button" class="btn btn-success w-100" onClick="manageQuestionFormat()" ><i class="fas fa-filter"></i>Filter</button>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>
