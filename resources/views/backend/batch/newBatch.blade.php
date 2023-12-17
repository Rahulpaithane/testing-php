

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title">New Batch:</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form data="{{ route($shared['routePath'].'.batchManage') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
                {{-- <form action="{{ route('admin.batchManage') }}"   method="POST" enctype="multipart/form-data"> --}}
                @csrf
                <input type="hidden" class="form-control" value="" name="batchId" id="batchId"  />
         <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
                <div class="form-group">
                    <label>Enter Batch Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control"  value="" name="batchName" id="batchName" required placeholder="Enter Batch Number" />
                </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Start Date : <span class="text-danger">*</span></label>
                    <input type="text" name="startDate" class="form-control chooseDate" id="startDate" placeholder="Enter Date" required readonly  />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>End Date : <span class="text-danger">*</span></label>
                    <input type="text" name="endDate" class="form-control chooseDate" id="endDate" placeholder="Enter Date" required readonly />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Type : <span class="text-danger">*</span></label>
                    <select name="batchType" id="batchType" class="form-control" onchange="changeBatchType()" required >
                        <option value="Paid">Paid</option>
                        <option value="Free">Free</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Badge : <span class="text-success">(opt)</span></label>
                    <input type="text" class="form-control"  value="" name="badge" id="badge" placeholder="Enter Badge" />
                </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
                <div class="row batchPricableInfo"  >

                </div>
            </div>
         </div>
         <hr />
         <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="batch_count_section" id="batch_count_section" value="0">

                    <table class="table table-bordered table-responsive" id="batch_info_table">
                        <thead>
                        <tr height="20px; width:100%">
                                <th style="min-width: 5%; ">S.NO</th>
                                <th style="min-width: 20%, max-width: 20%">Category <span class="text-danger">*</span></th>
                                <th style="min-width: 70%, max-width: 70%">Sub-Category <span class="text-success">(Opt)</span></th>
                                <th style="width:5%"><button type="button" onclick="addDynamicBatchField()" id="add_row" class="btn btn-default"><i class="fa fa-plus" style="color: green;"></i></button></th>
                        </tr>
                        <tbody>
                        </tbody>

                    </table>
            </div>
         </div>
         <hr/>
         <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Write description about the batch...." ></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div id="dropArea">
                        <p>Choose/Drag and drop an image here <span class="text-danger fw-bold"> *</span></p>
                        <img id="previewImage" src="#" alt="Preview">
                      </div>
                      <input type="file" name="batchImage" class="form-control"  id="uploadImage"  accept="image/*" style="display: none;">
                      <input type="hidden" name="existing_batch_image" id="existing_batch_image" />
                </div>
        </div>
         </div>
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

  <script>
   

    function changeBatchType(){
        var batchType = $("#batchType").val();
        $(".batchPricableInfo").html('');
        if(batchType =='Paid'){
            $(".batchPricableInfo").append(`
            <div class="col-md-6">
                <div class="form-group">
                    <label>Batch Price : <span class="text-danger">*</span></label>
                    <input type="text" name="batchPrice" id="batchPrice" value="" class="form-control" onkeypress="return isFloatNumberKey(event)" placeholder="Enter Batch Price" required />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Batch Offer Price : <span class="text-danger">*</span></label>
                    <input type="text" name="batchOfferPrice" id="batchOfferPrice" value="" onkeypress="return isFloatNumberKey(event)" class="form-control" placeholder="Enter Batch Offer Price" required />
                </div>
            </div>
        `);
        } else {
            $(".batchPricableInfo").append(`
            <input type="hidden" name="batchPrice" id="batchPrice" value="0" class="form-control" placeholder="Enter Batch Price"  />
            <input type="hidden" name="batchOfferPrice" id="batchOfferPrice" value="0" class="form-control" placeholder="Enter Batch Price"  />
            `);
        }
    }

    $(function() {
        changeBatchType();

  });

  </script>
<script src="{{ url('assets/js/img-uploader.js')}}"></script>
