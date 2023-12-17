 <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title">New Subject & Chpaters:</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form data="{{ route($shared['routePath'].'.manageSubject') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
                {{-- <form action="{{ route($shared['routePath'].'.manageSubject') }}"   method="POST" enctype="multipart/form-data"> --}}
                @csrf
                <div id="errors" class="text-danger"></div>
                <input type="hidden" class="form-control" value="" name="subjectId" id="subjectId"  />
         <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Select Class : <span class="text-danger">*</span></label>
                    <select class="form-control" name="class" id="class" >
                        <option selected="seleected" hidden="true" value="" >Select Class</option>
                        @foreach ($classCategory as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
         </div>
         <hr />
         <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="count_section" id="count_section" value="0">
                    <table class="table table-bordered table-responsive" id="subject_and_chapter_table">
                        <thead>
                        <tr height="10px; width:50%" style="height:10px !important">
                                <th style="min-width: 5%; ">#S.No</th>
                                <th style="min-width: 20%, max-width: 20%">Subject Name<span class="text-danger">*</span></th>
                                <th style="min-width: 70%, max-width: 70%">Chapters<span class="text-danger">*</span></th>
                                <th style="width:5%" id="addaableSubject" ><button type="button" onclick="addDynamicSubjectField()" id="add_row" class="btn btn-default"><i class="fa fa-plus" style="color: green;"></i></button></th>
                        </tr>
                        <tbody>
                        </tbody>
                    </table>
            </div>
         </div>
         <hr/>
         <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" id="submit" class="btn btn-success" >Submit</button>
                </div>
            </div>
         </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  
