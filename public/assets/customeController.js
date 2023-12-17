// Calss 
// Batch
// Subject With Chapter
// Teacher
// set Paper For practice/Live test
// Live Quiz Room
// Book
// Banner
// Notification

// ----------------- Class -----------------------
function openClass(){
    $("#insertFormData").trigger("reset");
    $("#classId").val('');
    $("#previewImage").attr('src', '');
    $("#existing_class_image").val('');
    $("#myModal").modal('show');
}

function editClass(data = '') {
 
    $("#classId").val(data.id);
    $("#class_name").val(data.name);
    $("#prepration").val(data.prepration);
    $("#previewImage").attr('src', data.image);
    var withoutDomainImage = data.image.replace(/^https?:\/\/[^\/]+(\/)?/, '');
    $("#existing_class_image").val(withoutDomainImage);

    $("#myModal").modal('show');
}

// ************************* Batch *******************************

function openBatchModal(){
    $("#insertFormData").trigger("reset");
    $("#batchId").val('');
    $("#previewImage").attr('src', '');
    $("#existing_batch_image").val('');
    $("#batch_info_table tbody").html('');
    changeBatchType();
    $("#myModal").modal('show');
}

function addDynamicBatchField(categoryId='', category='', subCat='') {
    var count_table_tbody_tr = $("#batch_count_section").val();

    var row_id = ++count_table_tbody_tr;
    $("#batch_count_section").val(row_id);
    $('#batch_info_table tbody').append(`<tr id="row_${row_id}">
        <td class="text-center" id="td_${row_id}">${row_id}</td>
        <td>
            <input type="hidden" name="batchCategoryId[]" id="batchCategoryId_${row_id}" value="${categoryId}" />
            <input type="text" name="batchCategory[]" id="batchCategory_${row_id}" value="${category}" required  class="form-control" autocomplete="off" placeholder="Features of test" >
        </td>

        <td>
            <input type="text" name="batchSubCategory[]" id="batchSubCategory_${row_id}" value="${subCat}" class="form-control inputTagg" autocomplete="off" placeholder="Features of test" >

        </td>
        <td><button type="button" class="btn"  onclick="removeBatchRow('${row_id}')"><i class="fas fa-times" style="color: red;"></i></button></td>
    <tr/>

    `);
    $("#batchSubCategory_"+row_id).tagsinput();

}

function removeBatchRow(tr_id){
    var tableProductLength = $("#batch_info_table tbody tr").length;

    $("#batch_info_table tbody tr#row_"+tr_id).remove();
}

function editBatch(data=''){
// console.log(JSON.stringify(data));
$("#batchId").val(data.id);
$("#class").val(data.class_id);
$("#batchName").val(data.name);
$("#startDate").val(data.start_date);
$("#endDate").val(data.end_date);
$("#batchType").val(data.batch_type);
$("#badge").val(data.badge);
$("#batchPrice").val(data.batch_price);
$("#batchOfferPrice").val(data.batch_offer_price);
$("#description").html(data.description);
$("#previewImage").attr('src', data.batch_image);
var withoutDomainImage = data.batch_image.replace(/^https?:\/\/[^\/]+(\/)?/, '');
$("#existing_batch_image").val(withoutDomainImage);
$("#batch_info_table tbody").html('');
data.batch_category.forEach(element => {
    // console.log(JSON.stringify(element.batch_sub_category));
    var subCategoryNames = $.map(element.batch_sub_category, function(item) {
        return item.sub_category_name;
      }).join(", ");

    addDynamicBatchField(element.id, element.category_name, subCategoryNames);
});

$("#myModal").modal('show');
}

// ------------------- Subject With Chapter --------------
function openSubjectModal(){
    $("#insertFormData").trigger("reset");
    $("#subjectId").val('');
    $("#batch_info_table tbody").html('');
    $("#myModal").modal('show');
}

function addDynamicSubjectField(subjectName='', chapters='') {
    var count_table_tbody_tr = $("#count_section").val();
    var row_id = ++count_table_tbody_tr;
    $("#count_section").val(row_id);
    $('#subject_and_chapter_table tbody').append(`<tr id="row_${row_id}">
        <td class="text-center" id="td_${row_id}">${row_id}</td>
        <td>
   
            <input type="text" name="subjectName[]" id="subjectName_${row_id}" value="${subjectName}"  class="form-control" autocomplete="off" placeholder="Subject Name" >
        </td>
        <td>
            <input type="text" name="chapters[]" id="chapters_${row_id}" required value="${chapters}" class="form-control inputTagg" autocomplete="off" placeholder="Chapters (saperate by: , )" >
        </td>
        <td id="editableSubject" ><button type="button" class="btn"  onclick="removeSubjectRow('${row_id}')"><i class="fa fa-times" style="color: red;"></i></button></td>
    <tr/>

    `);
    $("#chapters_"+row_id).tagsinput();
}
function removeSubjectRow(tr_id){
    var tableProductLength = $("#subject_and_chapter_table tbody tr").length;

    $("#subject_and_chapter_table tbody tr#row_"+tr_id).remove();
}

function editSubject(data=''){
    console.log(JSON.stringify(data));
    $("#subjectId").val(data.id);
    $("#class").val(data.class_id);
    $("#addaableSubject").html('');
    $("#addaableSubject").html('');
    $("#subject_and_chapter_table tbody").html('');
    // data.forEach(element => {
        // console.log(JSON.stringify(element.batch_sub_category));
        var chapterNames = $.map(data.chapters, function(item) {
            return item.name;
          }).join(", ");
    
          addDynamicSubjectField(data.name, chapterNames);
    // });
    $("#editableSubject").html('');
    $("#myModal").modal('show');

}


// -------------- Teacher ------------------
function openTeacherModal(){
    $("#insertFormData").trigger("reset");
    $("#teacherId").val('');
    $("#previewImage").attr('src', '');
    $("#existing_teacher_image").val('');
    $("#myModal").modal('show');
}

function editTeacherModal(data=''){
    // console.log(JSON.stringify(data));
    $("#teacherId").val(data.id);
    $("#name").val(data.name);
    $("#email").val(data.email);
    $("#mobile").val(data.mobile);
    $("#gender").val(data.gender);
    $("#aadhar_no").val(data.aadhar_no);
    $("#education").val(data.education);
    $("#previewImage").attr('src', window.location.origin+'/'+data.profile_image);
    var withoutDomainTeacherImage = data.profile_image.replace(/^https?:\/\/[^\/]+(\/)?/, '');
    $("#existing_teacher_image").val(withoutDomainTeacherImage);
    $("#address").html(data.address);
    $("#myModal").modal('show');
}


// ---------------- set Paper For practice/Live test

var AllQuesArray = [];
$(document).on('click', '.checkAllTableRow', function () {
    $('.createDivWrapper').removeClass('hide');
    if ($(this).prop('checked') == true) {
        $(this).closest('table').find('.checkOneRow').prop('checked', true);
        $(this).closest('table').find('.checkOneRow:checked').each(function () {
            var value = $(this).val();
            if (AllQuesArray.indexOf(value) === -1) AllQuesArray.push(value);
            $('.create_ppr_popup').show();
        });
    } else {
        $(this).closest('table').find('.checkOneRow').prop('checked', false);
        $(this).closest('table').find('.checkOneRow').each(function () {
            var value = $(this).val();
            if ($(this).prop('checked') == false && AllQuesArray.indexOf(value) != -1) {
                AllQuesArray = $.grep(AllQuesArray, function (value) {
                    return value != value;
                });
                $('.create_ppr_popup').hide();
            }
        });
    }
    $('.SelectedQuestionCount').html(AllQuesArray.length);
});

$(document).on('click', '.checkOneRow', function () {
    var value = $(this).val();
    $('.createDivWrapper').removeClass('hide');
    if ($(this).prop('checked') == true) {
        if (AllQuesArray.indexOf(value) === -1) AllQuesArray.push(value);
        $('.create_ppr_popup').show();
    } else {
        if ($(this).prop('checked') == false && AllQuesArray.indexOf(value) != -1) {
            AllQuesArray = $.grep(AllQuesArray, function (values) {
                return values != value;
            });
            if (AllQuesArray.length == 0) {
                $('.create_ppr_popup').hide();
            }

        }
    }
    $('.SelectedQuestionCount').html(AllQuesArray.length);
});

$(document).on('click', '.addQuestionLocalStorage', function () {
    AllQuesArray = [];
    $('.SelectedQuestionCount').html(AllQuesArray.length);
    $('.checkOneRow, .checkAllTableRow').prop('checked', false);
});

$('.showCreatePaperModal').on('click', function () {
    if ($.trim($('.SelectedQuestionCount').html()) == 0) {
        toastr.error('Select Question At Least 1');
        return false;
    } else {
        // alert(JSON.stringify(AllQuesArray));
        $('#no_of_totalselected_que b').html(AllQuesArray.length);
        $('#quetionIdsArr').val(JSON.stringify(AllQuesArray));
        $('.totalQuestions').val(AllQuesArray.length);
        $("#createExamModal").modal('show');
    }
});


// ----------------- Live Quiz Room -----------------------
function openQuizRoom(){
    $("#insertFormData").trigger("reset");
    $("#roomId").val('');
    $("#previewImage").attr('src', '');
    $("#existing_room_image").val('');
    $("#previewImage2").attr('src', '');
    $("#existing_room_banner").val('');
    $("#room_banner").val('');
    $(".roomBanner").hide();
    $(".priceType").html('');
    $("#myModal").modal('show');
}

function editQuizRoom(data = '') {
    // alert(JSON.stringify(data));
    $("#roomId").val(data.id);
    $("#room_name").val(data.name);
    $("#prepration").val(data.prepration);
    $("#room_type").val(data.room_type);

    if(data.room_type =='Paid'){
        $(".priceType").html(`
                    <label>Price: <span class="text-danger">*</span></label>
                    <input type="text" name="price" id="price" value="${data.price}" class="form-control" placeholder="Enter Room price" onkeypress="return isFloatNumberKey(event)" required/>
            `);
        $('#priceCol').show();

        $("#previewImage2").attr('src', data.banner);
        var withoutDomainBanner = data.banner.replace(/^https?:\/\/[^\/]+(\/)?/, '');
        $("#existing_room_banner").val(withoutDomainBanner);
        $('.roomBanner').show();

    } else {
        $(".priceType").html('');
        $('#priceCol').hide();

        $("#previewImage2").attr('src', '');
        $("#existing_room_banner").val('');
        $("#room_banner").val('');
        $(".roomBanner").hide();
    }

    $("#previewImage").attr('src', data.image);
    var withoutDomainImage = data.image.replace(/^https?:\/\/[^\/]+(\/)?/, '');
    $("#existing_room_image").val(withoutDomainImage);

    $("#myModal").modal('show');
}

let AllQuizQuesArray = [];
$(document).on('click', '.checkAllQuizTableRow', function () {
    // AllQuizQuesArray=[];

    // $('.createDivWrapper').removeClass('hide');
    if ($(this).prop('checked') == true) {
        $(this).closest('table').find('.checkQuizRow').prop('checked', true);
        $(this).closest('table').find('.checkQuizRow:checked').each(function () {
            var value = $(this).val();
            if (AllQuizQuesArray.indexOf(value) === -1) AllQuizQuesArray.push(value);
            // $('.create_ppr_popup').show();
        });
    } else {
        $(this).closest('table').find('.checkQuizRow').prop('checked', false);
        $(this).closest('table').find('.checkQuizRow').each(function () {
            var value = $(this).val();
            if ($(this).prop('checked') == false && AllQuizQuesArray.indexOf(value) != -1) {
                AllQuizQuesArray = $.grep(AllQuizQuesArray, function (value) {
                    return value != value;
                });
                // $('.create_ppr_popup').hide();
            }
        });
    }
    $('.selectedQuestionCount').html(AllQuizQuesArray.length);
    $('#quetionIdsArr').val(JSON.stringify(AllQuizQuesArray));
    $('.totalQuestions').val(AllQuizQuesArray.length);
});

$(document).on('click', '.checkQuizRow', function () {
    var value = $(this).val();
    // $('.createDivWrapper').removeClass('hide');
    if ($(this).prop('checked') == true) {
        if (AllQuizQuesArray.indexOf(value) === -1) AllQuizQuesArray.push(value);
        // $('.create_ppr_popup').show();
    } else {
        if ($(this).prop('checked') == false && AllQuizQuesArray.indexOf(value) != -1) {
            AllQuizQuesArray = $.grep(AllQuizQuesArray, function (values) {
                return values != value;
            });
            // if (AllQuizQuesArray.length == 0) {
            //     $('.create_ppr_popup').hide();
            // }

        }
    }

    $('.selectedQuestionCount').html(AllQuizQuesArray.length);
    $('#quetionIdsArr').val(JSON.stringify(AllQuizQuesArray));
    $('.totalQuestions').val(AllQuizQuesArray.length);
    // alert(AllQuizQuesArray.length);
});

function resetQuizSelectedData(){
    AllQuizQuesArray=[];
    // alert('s');
    // $('.createDivWrapper').removeClass('hide');
    // // if ($(this).prop('checked') == true) {
        // $(".checkAllQuizTableRow").find('.checkQuizRow').prop('checked', true);
        $(".checkAllQuizTableRow").find('.checkQuizRow:checked').each(function () {
            var value = $(".checkQuizRow").val();
            alert(value);
    //         if (AllQuizQuesArray.indexOf(value) === -1) AllQuizQuesArray.push(value);
    //         $('.create_ppr_popup').show();
        });
    // } else {
    //     $(this).closest('table').find('.checkQuizRow').prop('checked', false);
    //     $(this).closest('table').find('.checkQuizRow').each(function () {
    //         var value = $(this).val();
    //         if ($(this).prop('checked') == false && AllQuizQuesArray.indexOf(value) != -1) {
    //             AllQuizQuesArray = $.grep(AllQuizQuesArray, function (value) {
    //                 return value != value;
    //             });
    //             $('.create_ppr_popup').hide();
    //         }
    //     });
    // }
    $('.selectedQuestionCount').html(AllQuizQuesArray.length);
    $('#quetionIdsArr').val(JSON.stringify(AllQuizQuesArray));
    $('.totalQuestions').val(AllQuizQuesArray.length);
}


// -------------------- Book ------------------------

function openBookModal(){
    $("#insertFormData").trigger("reset");
    $("#bookId").val('');
    $("#previewImage").attr('src', '');
    $("#existing_thumnail_image").val('');
    $("#uploadPdf").val('');
    $("#existing_attachment").val('');
    $(".pdfLink").html('');
    $("#myModal").modal('show');
}

function editBook(data = '') {
    // alert(data.is_payable);
    $("#bookId").val(data.id);
    $("#book_type").val(data.book_type);
    if(data.book_type == 'Physical'){
        $("#stock").val(data.stock);
    }
    $("#book_name").val(data.book_name);
    $("#author").val(data.author);
    $("#publication").val(data.publication);
    $("#class").val(data.class);
    $("#is_payable").val(data.is_payable == true ? '1' : '0');
    if(data.is_payable == true){
        changePriceType();
        $("#price").val(data.price);
    }
    $("#description").val(data.description);
    $("#previewImage").attr('src', data.thumbnail);
    var withoutDomainThumbnail = data.thumbnail.replace(/^https?:\/\/[^\/]+(\/)?/, '');
    $("#existing_thumnail_image").val(withoutDomainThumbnail);
    $(".pdfLink").html(`<a target="_blank" href="${data.attachment}">Link</a>`);
    var withoutDomainAttachment = data.attachment.replace(/^https?:\/\/[^\/]+(\/)?/, '');
    $("#existing_attachment").val(withoutDomainAttachment);

    $("#myModal").modal('show');
}

// -------------------- Banner ------------------------

function openBannerModal(){
    $("#insertFormData").trigger("reset");
    $("#bannerId").val('');
    $("#previewImage").attr('src', '');
    $("#uploadImage").val('');
    $("#existing_banner").val('');
    $('#clickableData').hide();
    $("#myModal").modal('show');
}

function editBanner(data = '') {
    // alert(data.is_payable);
    $("#bannerId").val(data.id);
    $("#prepration").val(data.prepration);
    $("#previewImage").attr('src', data.image);
    var withoutDomainImage = data.image.replace(/^https?:\/\/[^\/]+(\/)?/, '');
    $("#existing_thumnail_image").val(withoutDomainImage);
    $("#isClickable").val(data.isClickable);
    if(data.isClickable == 1){
        changeClickableType();
        $("#class").val(data.class_id);
        fetchClassToBatch(data.batch_id)

    } else {
        changeClickableType();
    }

    $("#myModal").modal('show');
}


// ************************* Notification *******************************

function openNotificationModal(){
    $("#insertFormData").trigger("reset");
    $("#notificationId").val('');
    changeNotificationType();
    $("#myModal").modal('show');
}

function editNotification(data=''){
    // console.log(JSON.stringify(data));
    $("#notificationId").val(data.id);
    $("#targetStudent").val(data.targetStudent);
    $("#title").val(data.title);
    $("#message").val(data.message);

    changeNotificationType()
    $("#class_id").val(data.class_id);
    
    $("#myModal").modal('show');
    }






