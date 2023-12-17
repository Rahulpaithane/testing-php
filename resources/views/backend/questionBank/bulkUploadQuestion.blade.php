@extends('backend.common.baseFile')
@section('content')

@section('title', 'Upload Bulk Question')
@section('papgeTitle', 'Upload Bulk Question')

<section>

    <div class="row">
        <div class="col-md-12 questionFormat">
            <div class="edu_btn_wrapper sectionHolder padderBottom30 text-right">
                <a href="{{route($shared['routePath'].'.questionManage')}}" class="btn btn-primary" > <i class="fas fa-plus"></i> New Question</a>
                <a href="{{route($shared['routePath'].'.questionBankList')}}" class="btn btn-primary" > <i class="fas fa-list"></i> Question List</a>
             </div>

             <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route($shared['routePath'].'.bulkUploadQuestion') }}">Excel</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route($shared['routePath'].'.bulkWordOfficeUploadQuestion') }}">Word Office</a>
                </li>
               
            </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane container-fluid active" id="xcel">
                        <div class="card-body">
                            <form data="{{ route($shared['routePath'].'.bulkUploadQuestion') }}" id="questionInsertFormData"  method="POST" enctype="multipart/form-data">
                                 <!-- <form action="{{ route($shared['routePath'].'.bulkUploadQuestion') }}"   method="POST" enctype="multipart/form-data">  -->
                                    @csrf
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
                
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Question Type <span class="text-danger">*</span></label>
                                                <select name="questionType" id="questionType" class="form-control" required >
                                                    <option selected="selected"  value="Normal" >Normal</option>
                                                    {{-- <option  value="Group" >Group</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <label>Language Type <span class="text-danger">*</span></label>
                                                <select name="languageType" id="languageType" class="form-control" required >
                                                    <option selected="selected"  value="English" >English</option>
                                                    <option  value="Hindi" >Hindi</option>
                                                    <option  value="Both" >Both</option>
                                                </select>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Select CSV File</label>
                                                <input type="file" class="form-control" name="csvFile" id="csvFile" required />
                                            </div>
                                        </div>
        
                                        <div class="col-md-3 mt-4">
                                            <button type="submit" class="btn btn-primary">Upload Bulk Question</button>
                                        </div>
                                        <div class="col-md-3 mt-4">
                                            <a href="{{ url('assets/uploadDumyQuestionCsv.csv')}}" download="uploadDumyQuestionCSV.csv" class="btn btn-info">Download Demo File</a>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>

                    
                </div>
                  
          
            
        </div>
    </div>
    

    
</section>

 <!-- The Modal -->
  <div class="modal fade" id="badInsertionModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Error Log Question</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12"><Strong style="color: red;">Something error some question! Plz Update file then Upload </Strong></div>
                <div class="col-md-12 logQuestion">

                </div>
            </div>
        </div>
        
        
      </div>
    </div>
  </div>


@endsection