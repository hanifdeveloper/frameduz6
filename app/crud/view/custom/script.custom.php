crud = {
    init: function(){
        project = $("#crud-project");
        modalForm = $(".form-modal-input");
        modul = {
            table: {
                action: project.find(".frmData"),
                loader: project.find(".table-loader"),
                content: project.find(".table-content"),
                empty: project.find(".table-empty"),
                query: project.find(".table-content .query"),
                tbody: project.find(".table-content .table tbody"),
                tbodyRows: project.find(".table-content .table tbody tr"),
                pagging: project.find(".table-content .table-pagging ul.pagination"),
                paggingItem: project.find(".table-content .table-pagging ul.pagination .page-item"),
            },
            form: {
                modal: modalForm,
                action: modalForm.find(".frmInput"),
                title: modalForm.find(".form-modal-title"),
                content: modalForm.find(".form-modal-content"),
                loader: modalForm.find(".form-modal-loader"),
                button: modalForm.find(".form-modal-button"),
                objectForm: modalForm.find(".form-modal-content").clone().html(),
            },
        };
        
        crud.showTable();
        $(document).on("change", "#cari, #jenis", function(){
            modul.table.action.find("#page").val("1");
            crud.showTable();
        });
        $(document).on("click", ".btn-form", function(event){
            crud.showForm(this.id);
        });
        $(document).on("click", ".btn-delete", function(event){
            if(confirm("Yakin data ini akan dihapus ?")){
                app.delete({
                    url: "/crud/hapus",
                    data: {id: this.id},
                    onSuccess: function(response){
                        // console.log(response);
                        crud.showTable();
                    }
                });
            }
        });
    },
    showTable: function(){
        modul.table.content.hide();
        modul.table.empty.hide();
        modul.table.loader.show();
        app.load({
            url: "/crud/tabel",
            data: modul.table.action.serialize(),
            onLoad: function(response){
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
            }
        });
    },
    showForm: function(id){
        app.load({
            url: "/crud/form",
            data: {id: id},
            headers: {},
            onLoad: function(response){
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
                modul.form.loader.hide();
                modul.form.button.show();
                modul.form.content.html(modul.form.objectForm);
                $.each(object, function(key, val){ modul.form.content.find("span[data-form-object='"+key+"']").replaceWith(val); });
                modul.form.title.html(data.form_title);
                modul.form.content.find(".file-image").on("change", function(event){ app.imagePreview(this); });
                modul.form.modal.modal("show");
                modul.form.action.off();
                modul.form.action.on("submit", function(event){
                    event.preventDefault();
                    modul.form.button.hide();
                    modul.form.loader.show();
                    app.save({
                        url: "/crud/simpan",
                        data: $(modul.form.action)[0],
                        onSuccess: function(response){
                            // console.log(response);
                            modul.form.modal.modal("hide");
                            crud.showTable();
                        }
                    });
                });
            },
            error: function (e) {
                //console.log(e);
            }
        });
    },
};

crud.init();