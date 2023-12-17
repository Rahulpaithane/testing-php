  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title">New Class : </h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body --> 
        <div class="modal-body">
            <form data="{{ route($shared['routePath'].'.manageClasses') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
              
            @csrf
            <input type="hidden" name="classId" id="classId" value="" />
            <div id="errors" class="text-danger"></div>
         <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <label>Enter Class Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="" name="class_name" id="class_name" placeholder="Enter Class Name" />
                </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Prepration : <span class="text-danger">*</span></label>
                <select name="prepration" id="prepration" class="form-control" >
                  <option selected="selected" hidden="true" value="" >Select Type</option>
                  <option value="Govt">Govt.</option>
                  <option value="School">School</option>
                </select>
              </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div id="dropArea">
                        <p>Choose/Drag and drop an image here <span class="text-danger fw-bold"> *</span></p>
                        <img id="previewImage" src="#" alt="Preview">
                      </div>
                      <input type="file" name="class_image" class="form-control"  id="uploadImage"  accept="image/*" style="display: none;">
                      <input type="hidden" name="existing_class_image" id="existing_class_image" value="" />
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" id="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
         </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ url('assets/js/img-uploader.js')}}"></script>

