  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title">New Teacher : </h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <form data="{{ route($shared['routePath'].'.manageTeachers') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
            {{-- <form action="{{ route($shared['routePath'].'.manageTeachers') }}"  method="POST" enctype="multipart/form-data"> --}}
            @csrf
            <input type="hidden" name="teacherId" id="teacherId" value="" />
            <div id="errors" class="text-danger"></div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Name:<span class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Teacher Name" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Email: <span class="text-danger fw-bold">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email Id" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Mobile: <span class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile no.">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Gender<span class="text-danger fw-bold">*</span></label>
                        <select name="gender" class="form-control single-select" id="gender" >
                            <option value="" hidden>--select--</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Aadhar No <span class="text-danger fw-bold"> *</span></label>
                        <input type="text" class="form-control" name="aadhar_no" id="aadhar_no" placeholder="Enter Aadhar no.">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Education <span class="text-danger fw-bold"> *</span></label>
                        <input type="text" class="form-control" name="education" id="education" placeholder="Enter Education">
                    </div>
                </div>
            </div>
         <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div id="dropArea">
                        <p>Choose/Drag and drop an image here <span class="text-danger fw-bold"> *</span></p>
                        <img id="previewImage" src="#" alt="Preview">
                      </div>
                      <input type="file" name="profile_image" class="form-control"  id="uploadImage"  accept="image/*" style="display: none;">
                      <input type="hidden" name="existing_teacher_image" id="existing_teacher_image" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <textarea rows="2" class="form-control"  name="address" id="address" placeholder="Enter Address:"></textarea>
                </div>
            </div>
         </div>
         <hr class="mt-0">
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

