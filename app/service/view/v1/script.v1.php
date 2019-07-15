var api_url = "<?= $url_path; ?>";
app = {
    createTable: function(params){
        var tview = params.table
        var nomer = params.data.no;
        var tabel = params.data.tabel;
        tview.tbody.html("");
        tview.pagging.html("");
        tview.query.html(params.data.query);
        if(params.data.jumlah > 0){
            for(var rows in tabel){
                var row = tview.tbodyRows.clone();
                var result = row.html().replace("{no}", nomer++);
                json = tabel[rows];
                for(key in json){
                    var find = new RegExp("{"+key+"}", "g");
                    result = result.replace(find, json[key]);
                    row.html(result);
                }
                tview.tbody.append(row);
            }

            tview.content.show();
            params.onShow(tview.content);
            // Create pagging table
            var page = parseInt(params.data.page);
            var total_pages = Math.ceil(params.data.jumlah / params.data.batas);
            var prev_number = (page > 1) ? page - 1 : 1;
            var next_number = (page < total_pages) ? page + 1 : total_pages;
            var page_number = tview.paggingItem.html();
            
            var btn_first = tview.paggingItem.clone().attr("number-page", 1).html(page_number.replace("{page}", "&laquo;"));
            var btn_last = tview.paggingItem.clone().attr("number-page", total_pages).html(page_number.replace("{page}", "&raquo;"));
            var btn_prev = tview.paggingItem.clone().attr("number-page", prev_number).html(page_number.replace("{page}", "&lsaquo;"));
            var btn_next = tview.paggingItem.clone().attr("number-page", next_number).html(page_number.replace("{page}", "&rsaquo;"));
            var btn_dots = tview.paggingItem.clone().addClass("disabled").html(page_number.replace("{page}", "..."));
            var btn_active = tview.paggingItem.clone().addClass("active").html(page_number.replace("{page}", page));
            
            if(total_pages > 1){
                if(page > 3){
                    tview.pagging.append(btn_first);
                    tview.pagging.append(btn_prev);
                    tview.pagging.append(btn_dots);
                }

                for(i = (page - 2); i < page; i++){
                    if(i < 1) continue;
                    var pages = tview.paggingItem.clone().attr("number-page", i).html(page_number.replace("{page}", i));
                    tview.pagging.append(pages);
                }

                tview.pagging.append(btn_active);

                for(i = (page + 1); i < (page + 3); i++){
                    if(i > total_pages) break;
                    var pages = tview.paggingItem.clone().attr("number-page", i).html(page_number.replace("{page}", i));
                    tview.pagging.append(pages);
                }

                if((page + 2) < total_pages) tview.pagging.append(btn_dots);
                
                if(page < (total_pages - 2)){
                    tview.pagging.append(btn_next);
                    tview.pagging.append(btn_last);
                }
                
                // action page button
                tview.content.find(".pagging[number-page!='']").on("click", function(event){
                    event.preventDefault();
                    var page = $(this).attr("number-page");
                    params.onPagging(page);
                });
            }
        }else{
            tview.empty.show();
        }
    },
    createForm: {
        inputKey: function(id, value){
            return $("<input>").attr({type: "hidden", id: id, name: id, value: value});
        },
        inputText: function(id, value){
            return $("<input>").attr({type: "text", id: id, name: id, value: value, class: "form-control"});
        },
        textArea: function(id, value){
            return $("<textarea>").attr({id: id, name: id, value: value, class: "form-control"});
        },
        selectOption: function(id, data, value){
            var group = $("<div>");
            var select = $("<select>").attr({id: id, name: id, class: "form-control custom-select", style: "cursor: pointer;"});
            var value = value.split(",");
            $.each(data, function(key, val){
                var option = $("<option>").attr({value: val, selected: ($.inArray(val, value) != -1)}).text(val)
                select.append(option);
            });
            group.append(select);
            return group.children();
        },
        radioButton: function(id, data, value){
            var group = $("<div>");
            $.each(data, function(key, val){
                var radio = $("<input>").attr({type: "radio", id: id, name: id, value: key, checked: (key == value)});
                var label = $("<label>").attr("style", "color: #000; cursor: pointer; margin: 10px;").append(radio).append(" "+val.text);
                group.append(label);
            });
            return group.children();
        },
        checkBox: function(id, data, value){
            var group = $("<div>");
            var value = value.split(",");
            $.each(data, function(key, val){
                var check = $("<input>").attr({type: "checkbox", id: id, name: id, value: val, checked: ($.inArray(val, value) != -1)});
                var label = $("<label>").attr("style", "color: #000; cursor: pointer; margin: 5px;").append(check).append(" "+val);
                group.append(label);
            });
            return group.children();
        },
        uploadImage: function(id, image, mimes, desc){
            var group = $("<div>");
            var preview = $("<div>").attr({class: "image-preview"}).html('<img src="'+image+'" class="img-responsive thumbnail" alt="image" width="100%">');
            var file = $("<input>").attr({type: "file", id: id, name: id, class: "file-image", style: "display: none;", accept: mimes});
            var button = $("<label>").attr({for: id, class: "btn btn-block btn-sm btn-dark", style: "cursor: pointer; margin-top: 10px;"}).html("UPLOAD");
            var desc = $("<p>").attr({class: "help-block"}).html(desc);
            group.append(preview).append(file).append(button).append(desc);
            return group.html();
        },
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
            url: api_url+params.url,
            type: "POST",
            data: params.data,
            headers: {},
            dataType: "json",
            success: params.onLoad,
            error: function (e) {
                //console.log(e);
            }
        });
    },
    save: function(params){
        $.ajax({
            url: api_url+params.url,
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
            url: api_url+params.url,
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
}