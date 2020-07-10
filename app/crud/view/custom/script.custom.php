crud = {
    init: function(){
        modul = app.initModul("#crud_project");
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
                    app.sendData({
                        url: "/crud/delete",
                        data: {id: id},
                        onSuccess: function(response){
                            // console.log(response);
                            $(".err_message").html(app.showErrMessage(response));
                            crud.showTable();
                        },
                        onError: function(error){
                            // console.log(error);
                            $(".err_message").html(app.showErrMessage(error));
                        }
                    });
                }, 200);
            }
        });
        crud.showTable();
    },
    showTable: function(){
        modul.table.content.hide();
        modul.table.empty.hide();
        modul.table.loader.show();
        app.sendData({
            url: "/crud/table",
            data: modul.table.action.serialize(),
            onSuccess: function(response){
                // console.log(response);
                modul.table.loader.hide();
                app.createTable({
                    table: modul.table,
                    data: response.data,
                    onShow: function(content){
                        content.find("[data-toggle='tooltip']").tooltip();
                    },
                    onPagging: function(page){
                        modul.table.action.find("#page").val(page);
                        crud.showTable();
                        $(document).scrollTop(0);
                    }
                });
            },
            onError: function(error){
                // console.log(error);
                modul.table.loader.hide();
                $(".err_message").html(app.showErrMessage(error));
            }
        });
    },
    showForm: function(id){
        app.sendData({
            url: "/crud/form",
            data: {id: id},
            headers: {},
            onSuccess: function(response){
                var data = response.data;
                var form = data.form;
                var object = {
                    id_user: app.createForm.inputKey("id_user", form.id_user),
                    nama_user: app.createForm.inputText("nama_user", form.nama_user).attr("required", true),
                    jenis_kelamin: app.createForm.radioButton("jenis_kelamin", data.pilihan_jenis_kelamin, form.jenis_kelamin),
                    hobby_user: app.createForm.checkBox("hobby_user[]", data.pilihan_hobby, form.hobby_user),
                    alamat_user: app.createForm.textArea("alamat_user", form.alamat_user).attr("rows", 3),
                    foto_user: app.createForm.uploadImage("foto", form.foto_user, data.mimes_image, data.keterangan_upload_image),
                };
                
                modul.formModal.content.html(modul.formModal.objectForm);
                $.each(object, function(key, val){ modul.formModal.content.find("span[data-form-object='"+key+"']").replaceWith(val); });
                modul.formModal.title.html(data.form_title);
                modul.formModal.content.find(".file-image").on("change", function(event){ app.imagePreview(this); });
                modul.formModal.loader.hide();
                modul.formModal.button.show();
                modul.formModal.modal.modal("show");
                modul.formModal.action.off();
                modul.formModal.action.on("submit", function(event){
                    event.preventDefault();
                    modul.formModal.button.hide();
                    modul.formModal.loader.show();
                    app.sendDataMultipart({
                        url: "/crud/save",
                        data: $(modul.formModal.action)[0],
                        onSuccess: function(response){
                            // console.log(response);
                            $(".err_message").html(app.showErrMessage(response));
                            modul.formModal.modal.modal("hide");
                            crud.showTable();
                        },
                        onError: function(error){
                            // console.log(error);
                            $(".err_message").html(app.showErrMessage(error));
                            modul.formModal.modal.modal("hide");
                        }
                    });
                });
            },
            onError: function(error){
                // console.log(error);
                $(".err_message").html(app.showErrMessage(error));
            }
        });
    },
};

crud.init();