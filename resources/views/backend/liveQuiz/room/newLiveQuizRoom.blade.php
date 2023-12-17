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
            <form data="{{ route($shared['routePath'].'.quizRoomManage') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
              {{-- <form action="{{ route($shared['routePath'].'.quizRoomManage') }}"  method="POST" enctype="multipart/form-data"> --}}
              
            @csrf
            <input type="hidden" name="roomId" id="roomId" value="" />
            <div id="errors" class="text-danger"></div>
         <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <label>Enter Room Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="" name="room_name" id="room_name" placeholder="Enter Room Name" />
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
          <div class="col-md-3">
            <div class="form-group">
              <label>Room Type : <span class="text-danger">*</span></label>
              <select name="room_type" id="room_type" onchange="changePriceType()" class="form-control" >
                <option selected="true" value="Free">Free</option>
                <option value="Paid">Paid</option>
              </select>
            </div>
          </div>
          
          <div class="col-md-3" id="priceCol" style="display:none">
            <div class="form-group priceType">
            </div>
        </div>

         </div>

         <div class="row roomBanner " style="display: none">
          <div class="col-md-12">
            <div class="form-group">
                <div id="dropArea2">
                    <p>Choose/Drag and drop an Room Banner here <span class="text-danger fw-bold"> *</span></p>
                    <img id="previewImage2" src="#" alt="Preview">
                  </div>
                  <input type="file" name="room_banner" class="form-control"  id="uploadImage2"  accept="image/*" style="display: none;">
                  <input type="hidden" name="existing_room_banner" id="existing_room_banner" value="" />
            </div>
        </div>
      </div>

         <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div id="dropArea">
                        <p>Choose/Drag and drop an Room Logo here <span class="text-danger fw-bold"> *</span></p>
                        <img id="previewImage" src="#" alt="Preview">
                      </div>
                      <input type="file" name="room_image" class="form-control"  id="uploadImage"  accept="image/*"  style="display: none;">
                      <input type="hidden" name="existing_room_image" id="existing_room_image" value="" />
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

  <script>
    function changePriceType(){
        var bookType = $("#room_type").val();
        $(".priceType").html('');
        if(bookType == 'Paid'){

            $(".priceType").append(`
                    <label>Price: <span class="text-danger">*</span></label>
                    <input type="text" name="price" id="price" value="" class="form-control" placeholder="Enter Room price" onkeypress="return isFloatNumberKey(event)" required/>
            `);
            $('#priceCol').show();

            $(".roomBanner").show();
        } else {

            $(".priceType").append(`
            <input type="hidden" name="price" id="price" value="0" class="form-control"/>
            `);
            $('#priceCol').hide();
            $(".roomBanner").hide();
        }
    }
  </script>
  <script src="{{ url('assets/js/img-uploader.js')}}"></script>

