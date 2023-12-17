$(document).on("click", ".delete2023", function () {
    $(element).closest("tr").fadeOut();
    var id = $(this).attr('id');
    var url = $(this).attr('url');
    var token = $('meta[name="csrf-token"]').attr('content');
    var element = this;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data: {
                    id: id,
                    '_token': token
                },
                success: function (data) {
                    var result = data;
                    // alert(JSON.stringify(result));
                    if (result.statuscode == '001') {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Done!',
                            text: result.message,
                            showConfirmButton: false,
                            timer: 2000
                        })
                        $(element).closest("tr").fadeOut();
                    }
                    //  else if (result.statuscode == '0011') {
                    //     Swal.fire({
                    //         position: 'center',
                    //         icon: 'success',
                    //         title: 'Deleted!',
                    //         text: result.message,
                    //         showConfirmButton: false,
                    //         timer: 2000
                    //     })

                    //     $(element).closest("tr").fadeOut();
                    //     setTimeout(function () { $('#productInfoModal').modal('hide') }, 2000);
                    //     load_data("", "", "", "", "", "");

                    // }
                    else if(result.statuscode == '000') {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Not Deleted!',
                            text: result.errors,
                            showConfirmButton: false,
                            timer: 100
                        })
                    }
                }
            });
        }
    })
});

//To update the status of all the lists
 $(document).ready(function(){
    $(document).on("click",".toggleStatus",function(){
        var data = $(this).attr('data');
        // alert(data);
        var element = this;
        var id = $(this).attr('id');
        var status_data = $(this).attr('status_data');
        var token = $('meta[name="csrf-token"]').attr('content');

          if(status_data == '1'){
               var status = '0';
               var visStatus = '<a href="javascript:void(0);" class="toggleStatus alert alert-danger" data="' + data + '" id="'+ id +'"  status_data="'+ status +'" >Inactive</a>';
          }else{
              var status = '1';
              var visStatus = ' <a href="javascript:void(0);" class="toggleStatus alert alert-success" data="' + data +'" id="'+ id +'"  status_data="'+ status +'" >Active</a>';
            }

            Swal.fire({
                  title: 'Are you sure?',
                  text: "Don't worry this action is reversible!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, update it!'
                }).then((result) => {
                if (result.value) {
             $.ajax({
                    type: "POST",
                    url: data,
                    headers: {
                    'X-CSRF-TOKEN': token
                    },
                    data: {
                        id        : id,
                        status     : status,
                    },
                    success: function(data) {
                        var result = data;
                        if(result.statuscode == '001'){
                          Swal.fire({
                              position: 'center',
                              icon: 'success',
                              title: 'Done!',
                              text: result.message,
                              showConfirmButton: false,
                              timer: 2000
                              })
                              $(element).closest("td").html(visStatus);
                        //    history.go(0);
                          }else{
                              Swal.fire({
                              position: 'center',
                              icon: 'warning',
                              title: 'Not Updated!',
                              text: result.message,
                              showConfirmButton: false,
                              timer: 1000
                              })
                          }
                    }
                });
            }
        })
    });
});

//To update the popular of all the lists
$(document).ready(function(){
    $(document).on("click",".toggleBatchPopular",function(){
        var data = $(this).attr('data');
       
        var element = this;
        var id = $(this).attr('id');
        var status_data = $(this).attr('status_data');
        var token = $('meta[name="csrf-token"]').attr('content');
        // alert(status_data);
          if(status_data == '0'){
               var status = '1';
               var visStatus = '<a href="javascript:void(0);" class="toggleBatchPopular alert alert-danger" data="' + data + '" id="'+ id +'"  status_data="'+ status +'" >Popular</a>';
          }else{
              var status = '0';
              var visStatus = ' <a href="javascript:void(0);" class="toggleBatchPopular alert alert-success" data="' + data +'" id="'+ id +'"  status_data="'+ status +'" >Normal</a>';
            }

            Swal.fire({
                  title: 'Are you sure?',
                  text: "Don't worry this action is reversible!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, update it!'
                }).then((result) => {
                if (result.value) {
             $.ajax({
                    type: "POST",
                    url: data,
                    headers: {
                    'X-CSRF-TOKEN': token
                    },
                    data: {
                        id        : id,
                        status     : status,
                    },
                    success: function(data) {
                        var result = data;
                        if(result.statuscode == '001'){
                          Swal.fire({
                              position: 'center',
                              icon: 'success',
                              title: 'Done!',
                              text: result.message,
                              showConfirmButton: false,
                              timer: 2000
                              })
                              $(element).closest("td").html(visStatus);
                        //    history.go(0);
                          }else{
                              Swal.fire({
                              position: 'center',
                              icon: 'warning',
                              title: 'Not Updated!',
                              text: result.message,
                              showConfirmButton: false,
                              timer: 1000
                              })
                          }
                    }
                });
            }
        })
    });
});
