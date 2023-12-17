$("form#insertFormData").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $(".error").hide();
    var url = $(this).attr("data");

    var myAlert = Swal.fire({
        html: '<div class="sweet-alert-spinner"></div><div>Loading...</div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
        willClose: () => {
            Swal.hideLoading();
        },
    });

    // var myAlert = Swal.fire({
    //     position: 'center',
    //     text: 'Loading...',
    //     showConfirmButton: false,
    //     allowEscapeKey: false,
    //     allowOutsideClick: false,
    // })
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        beforeSend: function () {
            $("#submit").attr("disabled", true);
        },
        success: function (data) {
            $("#submit").attr("disabled", false);
            // alert(JSON.stringify(data));
            // console.log(data);
            var result = data;
            if (result.statuscode == "200") {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Added!",
                    text: result.message,
                    showConfirmButton: false,
                    timer: 2000,
                }).then(function () {
                    location.reload();
                });
            } else if (result.statuscode == "201") {
                // for modules submission
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Added!",
                    text: result.message,
                    showConfirmButton: false,
                    timer: 1200,
                }).then(function () {
                    if (result.actionType == "001") {
                        // Location Reload
                        location.reload();
                    } else if (result.actionType == "002") {
                        // function Call <Only>
                        // alert('s');
                        $.each(result.callback, function (key2, call) {
                            // alert(call);
                            window[call]();
                        });
                    } else if (result.actionType == "003") {
                        // Function Call And Close Modal
                        $.each(result.callback, function (key2, call) {
                            window[call]();
                        });

                        $("#" + result.closedPopup).modal("hide");
                    }
                });
            } else if (result.statuscode == "401") {

                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Opps!",
                    text: result.message,
                    showConfirmButton: true,
                });
              
            } else if (result.statuscode == "500") {
                // Internal Server Error! Cached Exceptions
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Opps!",
                    text: result.message,
                    showConfirmButton: false,
                    timer: 3000,
                });
                console.log(result.errors);
            } else {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Opps..",
                    text: result.message,
                    showConfirmButton: false,
                    timer: 3000,
                });
            }
        },
        error: function (xhr) {
            // Handle errors
            var errors = xhr.responseJSON.errors;
            $("#errors").html("");
            $.each(errors, function (field, messages) {
                $.each(messages, function (key, message) {
                    $("#errors").append("<div>" + message + "</div>");
                    $("#submit").attr("disabled", false);
                    setTimeout(function () {
                        myAlert.close();
                    }, 500);
                });
            });
        },

        // error: function (xhr, status, error) {
        //     $('#submit').attr('disabled', false);
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'error',
        //         title: 'Oppss..',
        //         text: error,
        //         showConfirmButton: false,
        //         timer: 3000
        //     })
        // },

        cache: false,
        contentType: false,
        processData: false,
    });
});

// ---------------- onSubmit type 2 for only questionBank

$("form#questionInsertFormData").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $(".error").hide();
    var url = $("#insertFormData").attr("data");
    // alert('df');
    var isValid = [];
    $("#errorMessages").empty(); // Clear previous error messages

    $("#questionInsertFormData .summernoterequired").each(function () {
        var content = $(this).summernote("code"); // Get Summernote content as HTML
        //   alert(content);
        // Check if content is empty or contains only whitespace
        if (!content.trim() || content === "<p><br></p>") {
            isValid.push(false);
            var fieldName = $(this).attr("name");
            // alert(fieldName);
            var errorMessage = "Please enter content for " + fieldName + ".";
            $("#errorMessages").append(
                '<p class="text-danger">' + errorMessage + "</p>"
            );
        } else {
            isValid.push(true);
        }
    });

    // if (isValid) {
    if (isValid.includes(false) == false) {
        isValid = [];
        // alert($("#paragraphIdd").val());
        Swal.fire({
            position: "center",
            text: "Loading...",
            showConfirmButton: false,
            allowEscapeKey: false,
            allowOutsideClick: false,
        });
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            beforeSend: function () {
                $("#submit").attr("disabled", true);
            },
            success: function (data) {
                $("#submit").attr("disabled", false);
                // alert(JSON.stringify(data));
                // console.log(data);
                var result = data;
                if (result.statuscode == "201") {
                    // for modules submission
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Updated!",
                        text: result.message,
                        showConfirmButton: false,
                        timer: 3000,
                    }).then(function () {
                        if (result.actionType == "001") {
                            // alert('as');
                            // Location Reload
                            setTimeout(function() {
                                // Location Reload after a specified delay (e.g., 2 seconds)
                                location.reload();
                            }, 2000);
                        } else if (result.actionType == "002") {
                            // function Call <Only>
                            // alert('s');
                            $.each(result.callback, function (key2, call) {
                                // alert(call);
                                window[call]();
                            });
                        } else if (result.actionType == "003") {
                            // Function Call With Closed Modal
                            var languageType = $("#languageType").val();
                            if (languageType == "Both") {
                                $("#englishQuestion").summernote("reset");
                                $("#optionEnglishA").summernote("reset");
                                $("#optionEnglishB").summernote("reset");
                                $("#optionEnglishC").summernote("reset");
                                $("#optionEnglishD").summernote("reset");
                                $("#optionEnglishE").summernote("reset");
                                $("#answerDescriptionEnglish").summernote(
                                    "reset"
                                );
                                $("#hindiQuestion").summernote("reset");
                                $("#optionHindiA").summernote("reset");
                                $("#optionHindiB").summernote("reset");
                                $("#optionHindiC").summernote("reset");
                                $("#optionHindiD").summernote("reset");
                                $("#optionHindiE").summernote("reset");
                                $("#answerDescriptionHindi").summernote(
                                    "reset"
                                );
                            } else if (languageType == "English") {
                                $("#englishQuestion").summernote("reset");
                                $("#optionEnglishA").summernote("reset");
                                $("#optionEnglishB").summernote("reset");
                                $("#optionEnglishC").summernote("reset");
                                $("#optionEnglishD").summernote("reset");
                                $("#optionEnglishE").summernote("reset");
                                $("#answerDescriptionEnglish").summernote(
                                    "reset"
                                );
                            } else if (languageType == "Hindi") {
                                $("#hindiQuestion").summernote("reset");
                                $("#optionHindiA").summernote("reset");
                                $("#optionHindiB").summernote("reset");
                                $("#optionHindiC").summernote("reset");
                                $("#optionHindiD").summernote("reset");
                                $("#optionHindiE").summernote("reset");
                                $("#answerDescriptionHindi").summernote(
                                    "reset"
                                );
                            }

                            if (result.data.questionType == "Group") {
                                // alert(result.data.questionBankId);
                               
                                $("#paragraphIdd").val(
                                    result.data.questionBankId
                                );
                                isValid = false;

                                $.each(result.callback, function (key2, call) {
                                    // alert(call);
                                    window[call](result.data);
                                });
                            } else {
                                $("#previousAskedYear").val('');
                            }
                            $("#myModal").modal("hide");

                            // groupQuestionInfo(result.data);

                            // $('#'+result.closedPpopup).modal('hide');
                        } else if(result.actionType == "004"){
                            // alert(JSON.stringify(result.badInsertion));
                            $("#questionInsertFormData").trigger("reset");
                            $(".logQuestion").html(result.badInsertion.toString());
                            $("#badInsertionModal").modal('show');
                        }
                    });
                }else if (result.statuscode == "401") { 

                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Opps Something Error",
                        text: result.message,
                        showConfirmButton: false,
                        timer: 3000,
                    }).then(function () {
                        if (result.actionType == "004") {
                            $(".logQuestion").html(result.badInsertion.toString());
                            $("#badInsertionModal").modal('show');
                        }
                    });


                }else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Opps..",
                        text: result.message,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            },
            error: function (xhr, status, error) {
                $("#submit").attr("disabled", false);
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Oppss..",
                    text: error,
                    showConfirmButton: false,
                    timer: 3000,
                });
            },

            cache: false,
            contentType: false,
            processData: false,
        });
    }
});

function groupQuestionInfo(data) {
    $("#paragrapquestionCount").html("");
    $.each(data.questionDetails, function (key, value) {
        $("#paragrapquestionCount").append(`
        <div class="row">
            <div class="col-md-12">
                ${key + 1}
            </div>
        </div>
        `);
    });
}

function fetchClassToBatch() {
    var id = $("#class").val();

    var url = $("#class").attr("url");
    // alert(category);
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
        },
        url: url,
        data: {
            id: id,
            _token: token,
        },
        success: function (data) {
            // alert(JSON.stringify(data));
            $("#batch").empty();
            $("#batch").append(
                '<option hidden="true" value="" selected >Select Batch.</option>'
            );
            $.each(data.batch, function (key, value) {
                $("#batch").append(
                    '<option value="' +
                        value.id +
                        '">' +
                        value.name +
                        "</option>"
                );
            });
        },
    });
}

function fetchClassToSubject() {
    var id = $("#class").val();

    var url = $("#class").attr("url");
    // alert(category);
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
        },
        url: url,
        data: {
            id: id,
            _token: token,
        },
        success: function (data) {
            // alert(JSON.stringify(data));
            $("#subject").empty();
            $("#subject").append(
                '<option hidden="true" value="" selected >Select Subject.</option>'
            );
            $.each(data.subject, function (key, value) {
                $("#subject").append(
                    '<option value="' +
                        value.id +
                        '">' +
                        value.name +
                        "</option>"
                );
            });
        },
    });
}

function fetchChapter() {
    var category = $("#subject").val();

    var url = $("#subject").attr("url");
    // alert(category);
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
        },
        url: url,
        data: {
            category: category,
            _token: token,
        },
        success: function (data) {
            // alert(JSON.stringify(data));
            $("#chapter").empty();
            $("#chapter").append(
                '<option hidden="true" value="" selected >Select Chapter.</option>'
            );
            $.each(data.chapter, function (key, value) {
                $("#chapter").append(
                    '<option value="' +
                        value.id +
                        '">' +
                        value.name +
                        "</option>"
                );
            });
        },
    });
}

function fetchBatchToPaperCategory() {
    var id = $("#batch").val();

    var url = $("#batch").attr("url");
    // alert(category);
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
        },
        url: url,
        data: {
            id: id,
            _token: token,
        },
        success: function (data) {
            // alert(JSON.stringify(data));
            $("#paperCategory").empty();
            $("#paperCategory").append(
                '<option hidden="true" value="" selected >Select Catgory.</option>'
            );
            $.each(data.paperCategory, function (key, value) {
                $("#paperCategory").append(
                    '<option value="' +
                        value.id +
                        '">' +
                        value.category_name +
                        "</option>"
                );
            });
        },
    });
}

function fetchPaperCategoryToPaperSubCategory() {
    var id = $("#paperCategory").val();

    var url = $("#paperCategory").attr("url");
    // alert(category);
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
        },
        url: url,
        data: {
            id: id,
            _token: token,
        },
        success: function (data) {
            // alert(JSON.stringify(data));
            // alert(data.paperSubCategory.length);
            if (data.paperSubCategory.length > 0) {
                // $('#paperSubCategory').empty();
                $(".subPaper").html(`
                <div class="form-group">
                    <label>Paper Sub-Category<sup>*</sup></label>
                    <select class="form-control" name="paperSubCategory" id="paperSubCategory"   required >
                        <option selected="selected" hidden="true" value="" >Sub-Category</option>
                    
                    </select>
                </div>`);
                $(".subPaper").show();
                $.each(data.paperSubCategory, function (key, value) {
                    $("#paperSubCategory").append(
                        '<option value="' +
                            value.id +
                            '">' +
                            value.sub_category_name +
                            "</option>"
                    );
                });
            } else {
                $(".subPaper").html("");
                $(".subPaper").hide();
            }
        },
    });
}

function isFloatNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function onlyNumberKey(evt) {
    // Only ASCII charactar in that range allowed
    var ASCIICode = evt.which ? evt.which : evt.keyCode;
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) return false;
    return true;
}
