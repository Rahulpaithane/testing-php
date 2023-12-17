

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title">New Notification:</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form data="{{ route($shared['routePath'].'.notificationManage') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
                {{-- <form action="{{ route('admin.notificationManage') }}"   method="POST" enctype="multipart/form-data"> --}}
                @csrf
                <input type="hidden" class="form-control" value="" name="notificationId" id="notificationId"  />

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Select Class : <span class="text-danger">*</span></label>
                            <select class="form-control" name="targetStudent" id="targetStudent" onchange="changeNotificationType()" >
                                <option selected="seleected"  value="All" >All Class</option>
                                <option value="Specfic" >Specfic Class</option>
                                
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 specficNotificationClass">
                        
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Enter Notification Title : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  value="" name="title" id="title" required placeholder="Enter Notification Title" />
                        </div>
                    </div>
                </div>
      
      
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea name="message" id="message" class="form-control" rows="5" placeholder="Write Mesage...." ></textarea>
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
   

    function changeNotificationType(){
        var notificationType = $("#targetStudent").val();

        if(notificationType =='Specfic'){
            $(".specficNotificationClass").html(`
            <div class="form-group">
                <label>Select Class : <span class="text-danger">*</span></label>
                <select class="form-control" name="class_id" id="class_id" >
                        <option selected="seleected" hidden="true" value="" >Select Class</option>
                        @foreach ($classCategory as $item)
                            <option  value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                </select>
            </div>
        `);
        } else {
            $(".specficNotificationClass").html(`
            <input type="hidden" name="class_id" id="class_id" value="" class="form-control" placeholder="Enter"  />
            `);
        }
    }

    $(function() {
        changeNotificationType();

  });

  </script>
<script src="{{ url('assets/js/img-uploader.js')}}"></script>
