@extends('backend.common.baseFile')
@section('content')

@section('title', 'Import Institute Question')
@section('papgeTitle', 'Import Institute Question')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card" >
                <div class="card-body">
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Institute : <span class="text-danger">*</span></label>
                                <select class="form-control" name="instituteName23" id="instituteName23" >
                                    <option selected="selected" hidden="true" value="" >Select Institute</option>
                                    @foreach ($instituteList as $inst)
                                        <option value="{{$inst->tenant_id}}">{{$inst->institute_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="text-danger" id="instituteName23Error"></label>
                        </div>
                        <div class="col-md-2 mt-1">
                            <div class="form-group mt-4">
                                <button type="button" class="btn btn-primary btn-large" onclick="findInstituteData()" >Search</button>
                            </div>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="instituteQuestionTable" style="display: none">
    <div class="row">
        <div class="col-md-12">
                <div class="edu_main_wrapper edu_table_wrapper">
                <div class="edu_admin_informationdiv sectionHolder dropdown_height">
                    <div class="tableFullWrapper">
                        
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
 
<div class="createDivWrapper edu_add_question create_ppr_popup hide">
    <div class="edu_admin_informationdiv sectionHolder">
        <div class="ppr_popup_inner">
            <div class="footer_popup_inner">
                <div class="fp_btn_wrpa">
                    <p class="edu_sub_title nomarginbtm"><span class="SelectedQuestionCount">0</span> Question Selected</p>
                </div>
                <div class="fp_btn_wrpa">
                    <div class="edu_addPaper_wrap">
                        <a class="edu_admin_btn mr-2 showCreatePaperModal" ><i class="icofont-plus"></i>Import Question</a>
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
          <h4 class="modal-title">Import Question</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form data="{{ route($shared['routePath'].'.importQuestionTenantToAdmin') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
            {{-- <form action="{{ route($shared['routePath'].'.importQuestionTenantToAdmin') }}"   method="POST" enctype="multipart/form-data"> --}}
                @csrf
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-12">
						<h4 class="edu_sub_title" id="no_of_totalselected_que" >TOTAL SELECTED QUESTIONS : <b> 0
						</b></h4>

					</div>
                    <input type="hidden" name="tenantId" id="tenantId" />
                    <input type="hidden" class="form-control" name="quetionIdsArr" id="quetionIdsArr" value="" />
					<input type="hidden" class="totalQuestions" name="total_question" value="0">
                    
                    
    
					<div class="col-md-4" >
						<div class="form-group">
							<label>Exam Category <sup>*</sup></label>
							<select class="form-control" name="class" id="class" onChange="fetchClassToSubject()" url="{{route($shared['routePath'].'.fetchClassToSubject')}}" required >
                                <option selected="selected" hidden="true" value="" >Exam Category</option>
								@foreach ($classModel as $item)
                                    <option value="{{$item->id}}" >{{$item->name}}</option>
                                @endforeach
							</select>
						</div>
					</div>

                    <div class="col-md-4">
                        <div class="from-group">
                            <label>Select Subject <span class="text-danger">*</span></label>
                            <select name="subject" id="subject" class="form-control" onChange="fetchChapter()" url="{{route($shared['routePath'].'.fetchChapter')}}" required >
                              
                            </select>
                        </div>
                        <div class="error subjectError text-danger" ></div>
                    </div>

                    <div class="col-md-4">
                        <div class="from-group">
                            <label>Select Chapter <span class="text-danger"></span></label>
                            <select name="chapter" id="chapter" class="form-control"  >
                                <option selected="selected" value="" >All</option>

                            </select>
                        </div>
                        <div class="error chapterError text-danger" ></div>
                    </div>
                   
					<div class="col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="form-group">
							<center><button type="submit" class="btn btn-success" > Import Question</button></center>
						</div>
					</div>
				</div>
			</form>
        </div>



      </div>
    </div>
  </div>

<script>
    function importData(){
        if ($.trim($('.SelectedQuestionCount').html()) == 0) {
        toastr.error('Select Question At Least 1');
        return false;
    } else {
        // alert(JSON.stringify(AllQuesArray));
        var questionLength =AllQuesArray.length;
        var quetionIdsArr= AllQuesArray;
        var tenantId=$("#tenantId").val();
        // alert(tenantId);
        var url = '{{route($shared['routePath'].".importQuestionTenantToAdmin")}}';
        var token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
        title: 'Are you sure?',
        text: "You Import this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Import it!'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        tenantId : tenantId,
                        questionLength: questionLength,
                        quetionIdsArr : quetionIdsArr,
                        '_token': token
                    },
                    success: function (data) {
                        alert(JSON.stringify(data));
                        var result = data;
                        if (result.statuscode == '001') {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Done!',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 2000
                            })
                            $(element).closest("tr").fadeOut();
                        }
                        //  else if (result.statuscode == '0011') {
                        //     Swal.fire({
                        //         position: 'center',
                        //         icon: 'success',
                        //         title: 'Deleted!',
                        //         text: result.message,
                        //         showConfirmButton: false,
                        //         timer: 2000
                        //     })

                        //     $(element).closest("tr").fadeOut();
                        //     setTimeout(function () { $('#productInfoModal').modal('hide') }, 2000);
                        //     load_data("", "", "", "", "", "");

                        // }
                        else if(result.statuscode == '000') {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Not Deleted!',
                                text: result.errors,
                                showConfirmButton: false,
                                timer: 100
                            })
                        }
                    }
                });
            }
        })
    }
    }
</script>

<script>
    // $(function() {
    //     load_data(""); // first load
    // });
    function load_data(instituteId){
        var ajax_url = '{{route($shared['routePath'].".importInstituteQuestionIndex")}}';
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
                "tenant_id" : instituteId,
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

    function findInstituteData(){
        var instituteId = $("#instituteName23").val();
        if(instituteId !=''){
            $("#instituteQuestionTable").show();
            $("#tenantId").val(instituteId);
            load_data(instituteId);
        } else {
            $("#instituteName23Error").html('Select Institute');
        }
        
    }
</script>

@endsection