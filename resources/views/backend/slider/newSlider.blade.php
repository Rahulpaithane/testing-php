  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal Header --> 
        <div class="modal-header">
          <h5 class="modal-title">New Banner : </h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body --> 
        <div class="modal-body">
            <form data="{{ route($shared['routePath'].'.bannerManage') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
              {{-- <form action="{{ route($shared['routePath'].'.bannerManage') }}"  method="POST" enctype="multipart/form-data"> --}}
              
            @csrf
            <input type="hidden" name="bannerId" id="bannerId" value="" />
            <div id="errors" class="text-danger"></div>
         <div class="row">
             
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

            <div class="col-lg-3 col-md-4 col-sm-12 col-12">
              <div class="form-group">
                <label>is Clicklable <sup>*</sup></label>
                <select class="form-control " onchange="changeClickableType()" name="isClickable" id="isClickable" require >
                  <option selected="selected" value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>
            </div>

            <div class="col-md-6" id="clickableData" style="display: none">
              <div class="row" id="clickableDataInfo">
                <input type="hidden" name="class" id="class" class="form-control"/>
                <input type="hidden" name="batch" id="batch" class="form-control"/>
              </div>
            </div>

            

         </div>
         <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div id="dropArea">
                        <p>Choose/Drag and drop an Banner here (Accept Only JPEG AND PNG) <span class="text-danger fw-bold"> *</span></p>
                        <img id="previewImage" src="#" alt="Preview">
                      </div>
                      <input type="file" name="banner" class="form-control"  id="uploadImage"  accept="image/jpeg, image/png" style="display: none;">
                      <input type="hidden" name="existing_banner" id="existing_banner" value="" />
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

  <script>
    function changeClickableType(){
        var clickableType = $("#isClickable").val();
     
        $("#clickableDataInfo").html('');
        if(clickableType =='1'){
         
            // $('#bookTypeCol').removeClass('col-md-6');
            // $('#bookTypeCol').addClass('col-md-3');
            $("#clickableDataInfo").html(`
            <div class="col-md-6" >
              <div class="form-group">
                <label>Exam Category <sup>*</sup></label>
                <select class="form-control" name="class" id="class" onChange="fetchClassToBatch()" url="{{route($shared['routePath'].'.fetchClassToBatch')}}" required >
                                  <option selected="selected" hidden="true" value="" >Exam Category</option>
                  @foreach ($classModel as $item)
                                      <option value="{{$item->id}}" >{{$item->name}}</option>
                                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Batch<sup>*</sup></label>
                <select class="form-control" name="batch" id="batch" onchange="fetchBatchToPaperCategory()" url="{{route($shared['routePath'].'.fetchBatchToPaperCategory')}}" required >
                                  <option selected="selected" hidden="true" value="" >Batch</option>
  
                </select>
              </div>
            </div>
            `);
            $('#clickableData').show();
        } else {
            // $('#bookTypeCol').addClass('col-md-6');
            // $('#bookTypeCol').removeClass('col-md-3');
            $("#clickableDataInfo").append(`
            <input type="hidden" name="class" id="class" class="form-control"/>
            <input type="hidden" name="batch" id="batch" class="form-control"/>
            `);
            $('#clickableData').hide();
        }
    }
  </script>

