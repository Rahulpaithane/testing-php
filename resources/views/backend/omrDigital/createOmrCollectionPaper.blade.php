@extends('backend.common.baseFile')
@section('content')

@section('title', 'Create OMR Collection Paper')
@section('papgeTitle', 'Create OMR Collection Paper')

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
                                        <th><input type="checkbox" class="checkAllTableRow"></th>
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

<script>
    $(function() {
        load_data("","","", "", ""); // first load
    });
    function load_data(subject='', chapter='', questionType='', languageType='', questionLevel='' ){
        var ajax_url = '{{route($shared['routePath'].".createPaper")}}';
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
        });
    }

    function manageQuestionFormat(){
        var subject = $("#subject").val();
        var chapter = $("#chapter").val();
        var questionType = $("#questionType").val(); //Normal, Group
        var languageType = $("#languageType").val(); //English, Hindi, Both
        var questionLevel = $("#questionLevel").val(); //English, Hindi, Both

        load_data(subject,chapter,questionType, languageType, questionLevel);
    }
</script>

<div class="createDivWrapper edu_add_question create_ppr_popup hide">
    <div class="edu_admin_informationdiv sectionHolder">
        <div class="ppr_popup_inner">
            <div class="footer_popup_inner">
                <div class="fp_btn_wrpa">
                    <p class="edu_sub_title nomarginbtm"><span class="SelectedQuestionCount">0</span> Question Selected</p>
                </div>
                <div class="fp_btn_wrpa">
                    <div class="edu_addPaper_wrap">
                        <a class="edu_admin_btn showCreatePaperModal mr-2"><i class="icofont-plus"></i>Create Collection</a>
                        <button class="btn btn-primary addQuestionLocalStorage"><i class="icofont-ui-reply"></i>Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pop Up Start  -->

  <!-- The Modal -->
  <div class="modal fade" id="createExamModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Create OMR Collection :</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form data="{{ route($shared['routePath'].'.newOmrCollectionPaper') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
            {{-- <form action="{{ route($shared['routePath'].'.insertPaper') }}"   method="POST" enctype="multipart/form-data"> --}}
                @csrf
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-12">
						<h4 class="edu_sub_title" id="no_of_totalselected_que" >TOTAL SELECTED QUESTIONS : <b> 0
						</b></h4>

					</div>
                    <input type="hidden" class="form-control" name="quetionIdsArr" id="quetionIdsArr" value="" />
					<input type="hidden" class="totalQuestions" name="total_question" value="0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="form-group">
							<label>Paper Name <sup>*</sup></label>
							<input type="text" placeholder="Enter Paper Name" class="form-control" required name="paperName" id="paperName">
						</div>
					</div>
                    
					{{-- <div class="col-lg-6 col-md-12 col-sm-12 col-12 " >
						<div class="form-group">
							<label>Exam Category <sup>*</sup></label>
							<select class="form-control" name="class" id="class" onChange="fetchClassToBatch()" url="{{route($shared['routePath'].'.fetchClassToBatch')}}" required >
                                <option selected="selected" hidden="true" value="" >Exam Category</option>
								@foreach ($classModel as $item)
                                    <option value="{{$item->id}}" >{{$item->name}}</option>
                                @endforeach
							</select>
						</div>
					</div> --}}
                    {{-- <div class="col-lg-6 col-md-12 col-sm-12 col-12">
						<div class="form-group">
							<label>Batch<sup>*</sup></label>
							<select class="form-control" name="batch" id="batch" 
 
                            required >
                                <option selected="selected" hidden="true" value="" >Batch</option>

							</select>
						</div>
					</div> --}}
                    {{-- <div class="col-lg-6 col-md-12 col-sm-12 col-12">
						<div class="form-group">
							<label>Paper Category<sup>*</sup></label>
							<select class="form-control" name="paperCategory" id="paperCategory" onchange="fetchPaperCategoryToPaperSubCategory()" url="{{route($shared['routePath'].'.fetchPaperCategoryToPaperSubCategory')}}" required >
                                <option selected="selected" hidden="true" value="" >Sub-Category</option>

							</select>
						</div>
					</div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 subPaper hide" >

					</div> --}}


					<div class="col-lg-6 col-md-12 col-sm-12 col-12">
						<div class="form-group">
							<label>Exam Duration (In Minute)<sup>*</sup></label>
							<input type="number" placeholder="Enter Exam Duration" class="form-control " required name="time_duration" id="time_duration" >
						</div>
					</div>

                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 mocktesthideshow ">
						<div class="form-group">
							<label>Exam Date<sup>*</sup></label>
							<input type="text" placeholder="Exam Date" class="form-control chooseDate" name="exam_sheduled_date" id="exam_sheduled_date" required readonly >
						</div>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 col-12 mocktesthideshow ">
						<div class="form-group">
							<label>Exam Time<sup>*</sup></label>
							<input type="text" placeholder="Exam Time" class="form-control chooseTime" name="exam_sheduled_time" id="exam_sheduled_time" required  readonly>
						</div>
					</div>
                    

                    <div class="col-lg-3 col-md-3 col-sm-12 col-12 ">
						<div class="form-group">
							<label>Total Marks<sup>*</sup></label>
							<input type="text" placeholder="Per Question No" class="form-control " onkeypress="return isFloatNumberKey(event)" name="totalMarks" id="totalMarks" required>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-12 col-12 ">
						<div class="form-group">
							<label>Per Question Number<sup>*</sup></label>
							<input type="text" placeholder="Per Question No" class="form-control " onkeypress="return isFloatNumberKey(event)" required name="perQuestionNo" id="perQuestionNo" >
						</div>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-12 col-12">
						<div class="form-group">
							<label>Negative Marking <sup>*</sup></label>
							<select class="form-control" name="negativeMarkingType" required>

								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-12 col-12 ">
						<div class="form-group">
							<label>Per Negative Number<sup>*</sup></label>
							<input type="text" placeholder="Per Negative No" class="form-control " onkeypress="return isFloatNumberKey(event)" required name="perNegativeNo" id="perNegativeNo">
						</div>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="form-group">
							<center><button type="submit" class="btn btn-success w-100" >Upload Paper</button></center>
						</div>
					</div>
				</div>
			</form>
        </div>



      </div>
    </div>
  </div>



@endsection
