crud = {
    init: function(){
        app_url = "<?= $url_path; ?>";
        project = $("#crud-project");
        modalForm = $(".form-modal-input");
        modul = {
            table: {
                action: project.find(".frmData"),
                loader: project.find(".table-loader"),
                content: project.find(".table-content"),
            },
            formModal: {
                modal: modalForm,
                action: modalForm.find(".frmInput"),
                content: modalForm.find(".form-modal-content"),
                loader: modalForm.find(".form-modal-loader"),
                button: modalForm.find(".form-modal-button"),
            },
        };
        $(document).on("change", "#cari, #jenis", function(){
            modul.table.action.find("#page").val("1");
            crud.showTable();
        });
        $(document).on("click", ".btn-form", function(event){
            crud.showForm(this.id);
        });
        $(document).on("click", ".btn-delete", function(event){
            if(confirm("Yakin data ini akan dihapus ?")){
                var id = this.id
                setTimeout(function(){
                    crud.sendData({
                        url: app_url+"/delete",
                        data: {id: id},
                        onSuccess: function(response){
                            // console.log(response);
                            $(".err_message").html(crud.showErrMessage(response));
                            crud.showTable();
                        },
                        onError: function(error){
                            // console.log(error);
                            $(".err_message").html(crud.showErrMessage(error));
                        }
                    });
                }, 200);
            }
        });
        crud.showTable();
    },
    showTable: function(){
        modul.table.content.hide();
        modul.table.loader.show();
        crud.sendData({
            url: app_url+"/table",
            data: modul.table.action.serialize(),
            onSuccess: function(response){
                // console.log(response);
                modul.table.loader.hide();
                modul.table.content.html(response).show();
                modul.table.content.find("[data-toggle='tooltip']").tooltip();
                // action page button
                modul.table.content.find(".pagging[number-page!='']").on("click", function(event){
                    event.preventDefault();
                    var page = $(this).attr("number-page");
                    modul.table.action.find("#page").val(page);
                    crud.showTable();
                    $(document).scrollTop(0);
                });
            },
            onError: function(error){
                // console.log(error);
                modul.table.loader.hide();
                $(".err_message").html(crud.showErrMessage(error));
            }
        });
    },
    showForm: function(id){
        crud.sendData({
            url: app_url+"/form",
            data: {id: id},
            onSuccess: function(response){
                // console.log(response);
                modul.formModal.loader.hide();
                modul.formModal.button.show();
                modul.formModal.content.html(response);
                modul.formModal.content.find(".file-image").on("change", function(event){ crud.imagePreview(this); });
                modul.formModal.modal.modal("show");
                modul.formModal.action.off();
                modul.formModal.action.on("submit", function(event){
                    event.preventDefault();
                    modul.formModal.button.hide();
                    modul.formModal.loader.show();
                    crud.sendDataMultipart({
                        url: app_url+"/save",
                        data: $(modul.formModal.action)[0],
                        onSuccess: function(response){
                            // console.log(response);
                            $(".err_message").html(crud.showErrMessage(response));
                            modul.formModal.modal.modal("hide");
                            crud.showTable();
                        },
                        onError: function(error){
                            // console.log(error);
                            $(".err_message").html(crud.showErrMessage(error));
                            modul.formModal.modal.modal("hide");
                        }
                    });
                });
            },
            onError: function(error){
                // console.log(error);
                $(".err_message").html(crud.showErrMessage(error));
            }
        });
    },
    showErrMessage: function(params){
        var type = (params.status == "error") ? "bg-danger" : "bg-success";
        var errMessage = $("<div>").attr({class: "alert alert-dismissible "+type+" text-white border-0", role: "alert"});
        var closeButton = $("<button>").attr({type: "button", class: "close", "data-dismiss": "alert", "aria-label": "Close"}).html("<span aria-hidden='true'>×</span>")
        var message = $("<span>").html("<strong>"+params.message.title+"</strong> "+params.message.text);
        return errMessage.append(closeButton).append(message);
    },
    imagePreview: function(obj){
        if(obj.files && obj.files[0]){
            var reader = new FileReader();
            var preview = $(obj).siblings(".image-preview");
            reader.onload = function(e){
                preview.html('<img src="'+e.target.result+'" class="img-responsive thumbnail" alt="image" width="100%">');
            };
            reader.readAsDataURL(obj.files[0]);
        }
    },
    sendData: function(params){
        var token = (params.token == undefined) ? "app_token" : params.token;
        $.ajax({
            url: params.url,
            type: "POST",
            data: params.data,
            headers: {"Token": token},
            async: false,
            success: params.onSuccess,
            error: function (e) {
                // console.log(e);
                if(e.status !== 200){
                    if(typeof params.onError === "function") params.onError(e.responseJSON);
                }
            }
        });
    },
    sendDataMultipart: function(params){
        var token = (params.token == undefined) ? "app_token" : params.token;
        $.ajax({
            url: params.url,
            type: "POST",
            enctype: "multipart/form-data",
            data: new FormData(params.data),
            headers: {"Token": token},
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            datatype: "json",
            xhr: function () {
                var jxhr = null;
                if(window.ActiveXObject) 
                    jxhr = new window.ActiveXObject( "Microsoft.XMLHTTP" );
                else
                    jxhr = new window.XMLHttpRequest();

                if(jxhr.upload) {
                    jxhr.upload.addEventListener("progress", function (evt) {
                        if(evt.lengthComputable) {
                            let percent = Math.round((evt.loaded / evt.total) * 100);
                            if(typeof params.onProgress === "function") params.onProgress(percent);
                        }
                    }, false);
                }
                return jxhr;
            },
            success: params.onSuccess,
            error: function (e) {
                // console.log(e);
                if(e.status !== 200){
                    if(typeof params.onError === "function") params.onError(e.responseJSON);
                }
            }
        });
    },
};

crud.init();