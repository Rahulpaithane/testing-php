@extends('backend.common.baseFile')
@section('content')

@section('title', 'Add OMR Paper')
@section('papgeTitle', 'Add OMR Paper')

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card digitalPaperInfoCard" >
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Paper Name : <span class="text-danger">*</span></label>
                                        <input type="text" value="" class="form-control require" name="paperName" id="paperName" required placeholder="Enter Paper Name" error-message="Enter Paper Name" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Option Format : <span class="text-danger">*</span></label>
                                        <select name="optionFormat" id="optionFormat" class="form-control require" >
                                            <option value="Alphabetical">Alphabetical</option>
                                            <option value="Numbering">Numbering</option>
                                            <option value="Roman">Roman</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Exam Category <sup>*</sup></label>
                                        <select class="form-control require" name="class" id="class" onChange="fetchClassToBatch()" url="{{route($shared['routePath'].'.fetchClassToBatch')}}" error-message="Select Exam Category" >
                                            <option selected="selected" hidden="true" value="" >Exam Category</option>
                                            @foreach ($classModel as $item)
                                                <option value="{{$item->id}}" >{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Batch<sup>*</sup></label>
                                        <select class="form-control require" name="batch" id="batch" 

                                        error-message="Select Batch" >
                                            <option selected="selected" hidden="true" value="" >Batch</option>
            
                                        </select>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Exam Date<sup>*</sup></label>
                                        <input type="text" placeholder="Exam Date" class="form-control chooseDate require" name="exam_sheduled_date" id="exam_sheduled_date"  readonly error-message="Enter Exam Date" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Exam Time<sup>*</sup></label>
                                        <input type="text" placeholder="Exam Time" class="form-control chooseTime require" name="exam_sheduled_time" id="exam_sheduled_time" readonly  error-message="Enter Exam Time" >
                                    </div>
                                </div>
                        
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Total Question : <span class="text-danger">*</span></label>
                                    <input type="text" value="" name="totalQuestion" id="totalQuestion" onkeypress="return isFloatNumberKey(event)" class="form-control require" placeholder="Enter Total Question" error-message="Enter Total Quetion" />
                                </div>
                                <div class="col-md-6">
                                    <label>Total Marks : <span class="text-danger">*</span></label>
                                    <input type="text" value="" name="totalMarks" id="totalMarks" onkeypress="return isFloatNumberKey(event)" class="form-control require" placeholder="Enter Total Marks" error-message="Enter Total Marks" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Total Duration (In Min.) : <span class="text-danger">*</span></label>
                                    <input type="text" value="" name="totalDuration" id="totalDuration" onkeypress="return isFloatNumberKey(event)" class="form-control require" placeholder="Enter Total Duration" error-message="Enter Total Duration" />
                                </div>
                                <div class="col-md-6">
                                    <label>Number/Question : <span class="text-danger">*</span></label>
                                    <input type="text" value="" name="numberPerQuestion" id="numberPerQuestion" onkeypress="return isFloatNumberKey(event)" class="form-control require" placeholder="Enter Numbering Per Question" error-message="Enter Numbering Per Question" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Negative Marking : <span class="text-danger">*</span></label>
                                    <select class="form-control require" name="isNegativeMarking" id="isNegativeMarking" error-message="Select Negative Marking"  >
                                        <option value="Yes" >Yes</option>
                                        <option value="No" >No</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Number/Negative : <span class="text-danger">*</span></label>
                                    <input type="text" name="numberPerNegative" id="numberPerNegative" onkeypress="return isFloatNumberKey(event)" class="form-control require" value="0" placeholder="Enter Numbering Per Negative" error-message="Enter Numbering Per Negative" />
                                </div>
                            </div>
                         
                        </div>
                        <div class="col-md-2 mt-4">
                            <div id="errorMessages"></div>
                            <center><button type="button" onclick="uploadAnswerInfo()" class="btn btn-danger" >Upload Answer</button></center>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card digitalAnswerInfoCard " style="display:none" >
                <div class="card-body">
                    <form data="{{ route($shared['routePath'].'.newDigitalPaper') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
                        {{-- <form action="{{ route($shared['routePath'].'.newDigitalPaper') }}"  method="POST" enctype="multipart/form-data"> --}}
                        @csrf
                        <div class="formContentInfo"></div>
                        <div class="row">
                            <div class="col-md-4 formContentInfo2">

                            </div>
                            <div class="col-md-8">
                                <div class="badge badge-pill badge-danger p-3" style="font-size: 15px" ><span>Paper Name : <span class="paperId"></span></span></div>
                                <div >
                                    <span class="badge badge-pill badge-info" ><span>Option Format : <span class="optionId"></span></span></span>
                                </div>
                                <div >
                                    <span class="badge badge-pill badge-dark" ><span>Total Question : <span class="totalQuestionId"></span></span></span>
                               
                                    <span class="badge badge-pill badge-primary"><span>Total Marks : <span class="totalMarksId"></span></span></span>
                                    <span class="badge badge-pill badge-warning" ><span>Exam Duration : <span class="examDurationId"></span> Min</span></span>
                                </div>
                              
                                <div >
                                    <span class="badge badge-pill badge-success"><span>Number/Question : <span class="numberPerQuestionId"></span></span></span>
                                    <span class="badge badge-pill badge-warning"><span>Is Negative No. : <span class="isNegativeMarkingId"></span></span></span>
                                    <span class="badge badge-pill badge-danger"><span>Neg/Question : <span class="numberPerNegativeId"></span></span></span>
                                </div>
                                <br>
                                <br/>
                                <center><button type="submit" class="btn btn-success" >Upload Paper</button></center>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function uploadAnswerInfo() {
        var isValid = [];
        $('#errorMessages').empty(); // Clear previous error messages
        $('.require').each(function() {
        
            var content = $(this).val(); // Get Summernote content as HTML
            //   alert(content);
            // Check if content is empty or contains only whitespace
            if (content =='') {
                isValid.push(false);
                var fieldName = $(this).attr('error-message');
                // alert(fieldName);
                // var errorMessage = 'Please enter content for ' + fieldName + '.';
                $('#errorMessages').append('<p class="text-danger">' + fieldName + '</p>');
            } else {
                isValid.push(true);
            }
        });
        if (isValid.includes(false) == false) {
            isValid = [];
            var paperName=$("#paperName").val();
            var optionFormat=$("#optionFormat").val();
            var totalQuestion=$("#totalQuestion").val();
            var totalMarks=$("#totalMarks").val();
            var totalDuration=$("#totalDuration").val();
            var numberPerQuestion=$("#numberPerQuestion").val();
            var isNegativeMarking=$("#isNegativeMarking").val();
            var numberPerNegative=$("#numberPerNegative").val();

            
            // var batch=$("#batch").val();
            var exam_sheduled_date=$("#exam_sheduled_date").val();
            var exam_sheduled_time=$("#exam_sheduled_time").val();
            var timeZone=$("#timeZone").val();

            

            $(".paperId").html(paperName);
            $(".optionId").html(optionFormat);
            $(".totalQuestionId").html(totalQuestion);
            $(".totalMarksId").html(totalMarks);
            $(".examDurationId").html(totalDuration);
            $(".numberPerQuestionId").html(numberPerQuestion);
            $(".isNegativeMarkingId").html(isNegativeMarking);
            $(".numberPerNegativeId").html(numberPerNegative);

            

            $(".formContentInfo").html(`
                <input type="hidden" name="paperName" id="paperName" value="${paperName}" />
                <input type="hidden" name="optionFormat" id="optionFormat" value="${optionFormat}" />
                <input type="hidden" name="totalQuestion" id="totalQuestion" value="${totalQuestion}" />
                <input type="hidden" name="totalMarks" id="totalMarks" value="${totalMarks}" />
                <input type="hidden" name="totalDuration" id="totalDuration" value="${totalDuration}" />
                <input type="hidden" name="numberPerQuestion" id="numberPerQuestion" value="${numberPerQuestion}" />
                <input type="hidden" name="isNegativeMarking" id="isNegativeMarking" value="${isNegativeMarking}" />
                <input type="hidden" name="numberPerNegative" id="numberPerNegative" value="${numberPerNegative}" />

                
     
                <input type="hidden" name="exam_sheduled_date" id="exam_sheduled_date" value="${exam_sheduled_date}" />
                <input type="hidden" name="exam_sheduled_time" id="exam_sheduled_time" value="${exam_sheduled_time}" />
                <input type="hidden" name="timeZone" id="timeZone" value="${timeZone}" />
              
            `);
            var num=1;
            var Alphabetical=["A", "B", "C", "D", "E"];
            var Numbering=["1","2","3","4","5"];
            var Roman=["I", "II", "III", "IV", "V"];

            if (optionFormat === "Alphabetical") {
                var optionA = Alphabetical[0];
                var optionB = Alphabetical[1];
                var optionC = Alphabetical[2];
                var optionD = Alphabetical[3];
                var optionE = Alphabetical[4];
            } else if (optionFormat === "Numbering") {
                var optionA = Numbering[0];
                var optionB = Numbering[1];
                var optionC = Numbering[2];
                var optionD = Numbering[3];
                var optionE = Numbering[4];
            } else if (optionFormat === "Roman") {
                var optionA = Roman[0];
                var optionB = Roman[1];
                var optionC = Roman[2];
                var optionD = Roman[3];
                var optionE = Roman[4];
            }
            for (var i = 0; i < parseInt(totalQuestion); i++) {
               
                $(".formContentInfo2").append(`
                    <div class="row">
                       
                        <div class="col-md-12 col-12">
                            <div class="row">
                                <div class="col-md-1 col-2" style="padding: 0px;">Q. ${num} </div>
                                <div class="col-md-11 col-10">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" required name="answer_${num}" value="${optionA}" >${optionA}
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" required name="answer_${num}" value="${optionB}"  >${optionB}
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" required name="answer_${num}" value="${optionC}" >${optionC}
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" required name="answer_${num}" value="${optionD}"  >${optionD}
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" required name="answer_${num}" value="${optionE}"  >${optionE}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <hr/>

                        </div>
                    </div>
                `);
                num++;
            }
            $(".digitalAnswerInfoCard").show()
            $(".digitalPaperInfoCard").hide();
        }
    }
</script>

@endsection