  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title">New Books/PDF : </h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            {{-- <form action="{{ route($shared['routePath'].'.manageBooks') }}"  method="POST" enctype="multipart/form-data"> --}}
            <form data="{{ route($shared['routePath'].'.manageBooks') }}" id="insertFormData"  method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" value="" name="bookId" id="bookId"  />
            <div id="errors" class="text-danger"></div> 
         <div class="row">
            <div class="col-md-6" id="bookTypeCol">
                <div class="form-group">
                    <label>Book Type : <span class="text-danger">*</span></label>
                    <select class="form-control" onchange="changeBookType()" name="book_type" id="book_type" >
                        {{-- <option selected="true" hidden="true" value="" >Book Type</option> --}}
                        {{-- <option value="Physical">Physical</option> --}}
                        <option selected="true" value="E-Book">E-Book</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3" id="bookStockCol" style="display:none">
                <div class="form-group bookStock">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Book Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="" name="book_name" id="book_name" placeholder="Enter Class Name" />
                </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Author : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="" name="author" id="author" placeholder="Enter Author Name" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Publication : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="" name="publication" id="publication" placeholder="Enter  Publication Name" />
                </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>For Classes : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="" name="class" id="class" placeholder="Enter Class Name" />
                </div>
            </div>
            <div class="col-md-6" id="priceTypeCol">
                <div class="form-group">
                    <label>Pricing Type : <span class="text-danger">*</span></label>
                    <select class="form-control" onchange="changePriceType()" name="is_payable" id="is_payable" >
                        <option selected="true" hidden="true" value="" >Pricing Type</option>
                        <option value="1">Paid</option>
                        <option value="0">Free</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3" id="priceCol" style="display:none">
                <div class="form-group priceType">
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
                      <input type="file" name="thumbnail" class="form-control"  id="uploadImage"  accept="image/*" style="display: none;">
                      <input type="hidden" name="existing_thumnail_image" id="existing_thumnail_image" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div id="pdfArea">
                        <label>Choose a pdf file  <span class="text-success fw-bold">(opt)</span> <span class="pdfLink"></span></label>
                            <input type="file" name="attachment" class="form-control"  id="uploadPdf"  accept="pdf/*">
                            <input type="hidden" name="existing_attachment" id="existing_attachment" />
                        </div>
                        <div id="pdfPreview"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Write description about the book...." ></textarea>
                </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" id="submit" class="btn btn-success w-100">Submit</button>
                </div>
            </div>
         </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    function changeBookType(){
        var bookType = $("#book_type").val();
        $(".bookStock").html('');
        if(bookType =='Physical'){
            $('#bookTypeCol').removeClass('col-md-6');
            $('#bookTypeCol').addClass('col-md-3');
            $(".bookStock").append(`
                    <label>Book Stock : <span class="text-danger">*</span></label>
                    <input type="text" name="stock" id="stock" value="100" class="form-control" placeholder="Enter book stock" required/>
            `);
            $('#bookStockCol').show();
        } else {
            $('#bookTypeCol').addClass('col-md-6');
            $('#bookTypeCol').removeClass('col-md-3');
            $(".bookStock").append(`
            <input type="hidden" name="stock" id="stock" class="form-control"/>
            `);
            $('#bookStockCol').hide();
        }
    }

    //Checking the book is paid or free
    function changePriceType(){
        var bookType = $("#is_payable").val();
        $(".priceType").html('');
        if(bookType == '1'){
            $('#priceTypeCol').removeClass('col-md-6');
            $('#priceTypeCol').addClass('col-md-3');
            $(".priceType").append(`
                    <label>Price: <span class="text-danger">*</span></label>
                    <input type="text" name="price" id="price" value="" class="form-control" placeholder="Enter book price" onkeypress="return isFloatNumberKey(event)" required/>
            `);
            $('#priceCol').show();
        } else {
            $('#priceTypeCol').addClass('col-md-6');
            $('#priceTypeCol').removeClass('col-md-3');
            $(".priceType").append(`
            <input type="hidden" name="price" id="price" value="0" class="form-control"/>
            `);
            $('#priceCol').hide();
        }
    }

    $(document).ready(function() {
  $('#uploadPdf').change(function() {
    var file = this.files[0];
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#pdfPreview').html('<embed src="' + e.target.result + '" type="application/pdf" width="100%" height="200px">');
    };

    reader.readAsDataURL(file);
  });
});
  </script>
  <script src="{{ url('assets/js/img-uploader.js')}}"></script>

