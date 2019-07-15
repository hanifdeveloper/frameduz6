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
            form: {
                modal: modalForm,
                action: modalForm.find(".frmInput"),
                content: modalForm.find(".form-modal-content"),
                loader: modalForm.find(".form-modal-loader"),
                button: modalForm.find(".form-modal-button"),
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
                crud.delete({
                    url: app_url+"/hapus",
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
        modul.table.loader.show();
        crud.load({
            url: app_url+"/tabel",
            data: modul.table.action.serialize(),
            onLoad: function(response){
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
        });
    },
    showForm: function(id){
        crud.load({
            url: app_url+"/form",
            data: {id: id},
            onLoad: function(response){
                // console.log(response);
                modul.form.loader.hide();
                modul.form.button.show();
                modul.form.content.html(response);
                modul.form.content.find(".file-image").on("change", function(event){ crud.imagePreview(this); });
                modul.form.modal.modal("show");
                modul.form.action.off();
                modul.form.action.on("submit", function(event){
                    event.preventDefault();
                    modul.form.button.hide();
                    modul.form.loader.show();
                    crud.save({
                        url: app_url+"/simpan",
                        data: $(modul.form.action)[0],
                        onSuccess: function(response){
                            // console.log(response);
                            modul.form.modal.modal("hide");
                            crud.showTable();
                        }
                    });
                });
            },
        });
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
    load: function(params){
        $.ajax({
            url: params.url,
            type: "POST",
            data: params.data,
            headers: {},
            success: params.onLoad,
            error: function (e) {
                //console.log(e);
            }
        });
    },
    save: function(params){
        $.ajax({
            url: params.url,
            type: "POST",
            enctype: "multipart/form-data",
            data: new FormData(params.data),
            headers: {},
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            datatype: "json",
            success: params.onSuccess,
            error: function (e) {
                //
            }
        });
    },
    delete: function(params) {
        $.ajax({
            url: params.url,
            type: "POST",
            data: params.data,
            headers: {},
            dataType: "json",
            success: params.onSuccess,
            error: function (e) {
                //console.log(e);
            }
        });
    },
};

crud.init();