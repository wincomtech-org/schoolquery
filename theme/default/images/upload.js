$.fn.bindUpload = function () {
    var myData = {
        url: arguments[0].url || "",
        type: arguments[0].type || "",
        num: arguments[0].num || "",
        callbackPath: arguments[0].callbackPath || "#callbackPath"+idx,
        size:arguments[0].size || 5
    }
    var _this = this;
    var _this_parent=$(this).parent().parent().siblings().children('td');
    var input = this.find('input.add');
    var sike = 0;
    var itemList = null;
    var status = [];
    var reg = /[\u4e00-\u9fa5]/g;

    if (myData.num == 1) {
        input.removeAttr("multiple");
    } else {
        input.attr("multiple");
    }
    //打开上传进度窗口
    this.openWindow = function (files) {
        var allSize = 0;
        for (var x = 0; x < files.length; x++) {
            if (myData.num && x < myData.num || !myData.num) {
                allSize += files[x].size;
                status.push({name: files[x].name, state: 0});
            } else {
                status.push({name: files[x].name, state: 1});
            }
        }
        var box = '<div class="zwb_upload_status">' +
            '<div class="box">' +
            // '        <div class="title">' +
            // '            共' + files.length + '个文件&nbsp;' + ((allSize / 1048576).toFixed(3) == 0 ? allSize / 1048576 : (allSize / 1048576).toFixed(3)) + 'mb' +
            // '        </div>' +
            '        <div class="list">' +
            '<ul class="listBox"></ul>' +
            '</div>' +
            '        <div class="foot">' +
            '            <span class="ok">一键上传</span>' +
            '            <span class="container">继续添加</span>' +
            '            <span class="off">关闭</span>' +
            '        </div>' +
            '</div>' +
            '    </div>';
        
        _this_parent.append(box);
        
        //关闭进度窗口函数
        _this_parent.find(".off").bind("click", function () {
            _this.css('background-color',"#1452c8").css("color","#fff")
            _this.children('input').show();
            input.val("");
            _this_parent.find(".zwb_upload_status").fadeOut(100, function () {

                _this_parent.find(".zwb_upload_status").remove();
            });
            status = [];
            files = null;
             
        });

        _this_parent.find(".container").bind("click", function () {
           $(_this.children('input')).trigger('click')
            
        });
        _this.getImgSrc(files);
    }
    //显示缩略图函数
    this.getImgSrc = function (files) {
        var allSize = 0;
        for (var x = 0; x < files.length; x++) {
            if (myData.num && x < myData.num || !myData.num) {
                allSize += files[x].size;
                status.push({name: files[x].name, state: 0});
            } else {
                status.push({name: files[x].name, state: 1});
            }
        }

        
        var reader = new FileReader();
        var size = files[sike].size;
        var name = files[sike].name;
        var index1 = name.lastIndexOf(".");
        var type = name.substring(index1 + 1, name.length);//后缀名
        var info = "";
        if (myData.type != "" && myData.type.indexOf(type) == -1) {
            info = "建议上传图片格式";
            status[sike].state = 1;//禁止上传
        }
        if (reg.test(name)) {
            info = "文件名不建议含有中文";
            status[sike].state = 1;//禁止上传
        }
        if (myData.size != ""&&(size / 1048576)>myData.size) {
            info = "文件过大";
            status[sike].state = 1;//禁止上传
        }
        if (type == "png" || type == "jpg" || type == "gif" || type == "svg") {
            reader.readAsDataURL(files[sike]);
            reader.onload = function () {
                var imgPath = reader.result;
                console.log(myData.num)
                if (sike < myData.num || myData.num == "") {
                    _this_parent.find(".zwb_upload_status ul.listBox").append('<li>' +
                        '<img  src="' + imgPath + '"/>' +
                        '<a>' +
                        '<p class="name">' + name + '</p>' +
                        '<p class="size">大小:&nbsp;' + ((size / 1048576).toFixed(3) == 0 ? size / 1048576 : (size / 1048576).toFixed(3)) + 'b</p>' +
                        '<p>状态:&nbsp;<b class="state">未上传</b></p>' +
                        '</a>' +
                        '<span class="success"></span>' +
                        '<span class="delete"></span>' +
                        '<div class="err"' + (info ? 'style="width:100%;"' : '') + '>' + info +
                        '</div>' +
                        '<div class="progressBox">' +
                        '<div class="progress"></div>' +
                        '</div>' +
                        '</li>' );
                }

                sike++;

                if (sike == files.length) {
                      
                    sike = 0;
                    _this_parent.find(_this_parent.find('.zwb_upload_status').fadeIn(100));
                    itemList = _this_parent.find(".zwb_upload_status ul.listBox li");
                    console.log(itemList );
                    

                    _this_parent.find(".ok").bind("click", function () {
                      
                        var lengthI = files.length;
                        for (var i = 0; i < itemList.length; i++) {
                            console.log(itemList.length)
                            if (status[i].state == 0) {
                                _this.upload(itemList, i, files[i]);
                            }else{
                                status[i].state = 0
                            }
                        }
                    });

                    _this_parent.find(".delete").bind("click", function () {
                        var index = $(this).parent().index();
                        console.log(index)
                        status[index].state=1;
                        $(this).parent().hide(100);
                    });

                    // return sike++;
                } else {
                    return _this.getImgSrc(files);
                }

            }
        } else {
            if (type == "zip" || type == "rar" || type == "jar" || type == "7z" || type == "cab" || type == "iso") {
                imgPath = 'images/zip.png';
            }else if (type == "text") {
                imgPath = 'images/text.png';
            }else if (type == "doc") {
                imgPath = 'images/word.png';
            }else if (type == "html" || type == "htm" || type == "url") {
                imgPath = 'images/html.png';
            }else if (type == "xls") {
                imgPath = 'images/excal.png';
            }else if (type == "exe") {
                imgPath = 'images/exe.png';
            }else if (type == "css" || type == "less" || type == "sass") {
                imgPath = 'images/css.jpg';
            }else if (type == "js") {
                imgPath = 'images/js.jpg';
            }else if (type == "php") {
                imgPath = 'images/php.png';
            }else if (type == "sql") {
                imgPath = 'images/sql.png';
            }else if (type == "ttf" || type == "otf" || type == "ttc" || type == "woff" || type == "woff2") {
                imgPath = 'images/font.png';
            }else{
                imgPath = 'images/unknown.png';
            }
            if (sike < myData.num || myData.num == "") {
                _this_parent.find(".zwb_upload_status ul.listBox").append('<li>' +
                    '<img  src="' + imgPath + '"/>' +
                    '<a>' +
                    '<p class="name">' + name + '</p>' +
                    '<p class="size">大小:&nbsp;' + ((size / 1048576).toFixed(3) == 0 ? size / 1048576 : (size / 1048576).toFixed(3)) + 'b</p>' +
                    '<p>状态:&nbsp;<b class="state">未上传</b></p>' +
                    '</a>' +
                    '<span class="success"></span>' +
                    '<span class="delete"></span>' +
                    '<div class="err"' + (info ? 'style="width:100%;"' : '') + '>' + info +
                    '</div>' +
                    '<div class="progressBox">' +
                    '<div class="progress"></div>' +
                    '</div>' +
                    '</li>');
            }

            sike++;
            if (sike == files.length) {
                sike = 0;
                _this_parent.find(_this_parent.find('.zwb_upload_status').fadeIn(100));
                itemList = _this_parent.find(".zwb_upload_status ul.listBox li");
                _this_parent.find(".ok").bind("click", function () {
                    var lengthI = files.length;
                    for (var i = 0; i < files.length; i++) {
                        if (status[i].state == 0) {
                            _this.upload(lengthI, i, files[i]);
                        }
                    }
                });
                _this_parent.find(".delete").bind("click", function () {
                    var index = $(this).parent().index();
                    status[index].state=1;
                    $(this).parent().hide(100);
                });
            } else {
                return _this.getImgSrc(files);
            }
        }
        _this.css('background-color',"#eee").css("color","#666")
        _this.children('input').hide();
       return files;
        
    }
    this.upload = function (max, i, item) {
        var formData = new FormData();
        formData.append("upload", 1);
        formData.append("file", item);
        console.log(max,i,item)
        // console.log(file,item)
        $.ajax({
            url: myData.url,
            type: "post",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                itemList.eq(i).find(".progressBox").show();
            },
            xhr: function () {
                myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { //检查upload属性是否存在
                    //绑定progress事件的回调函数
                    myXhr.upload.addEventListener('progress', function (e) {
                        var percent = (e.loaded / e.total * 100).toFixed(2);
                        itemList.eq(i).find(".progress").width(percent + "%");
                    }, false);
                }
                return myXhr; //xhr对象返回给jQuery使用
            }, success: function (data) {
                
                //更改状态
                if (data.state == 1) {
                    console.log(data);
                    status[i].state = 1;
                    itemList.eq(i).find(".state").html("上传成功");
                    itemList.eq(i).find(".success").addClass("success2");
                    itemList.eq(i).find(".delete").remove();
                    var callInput = $(myData.callbackPath);
                    var val = callInput.val();
                    if (myData.num && i == (myData.num - 1) || i == (max - 1)) {
                        callInput.val(val + item.name + "," + data.path);
                    } else {
                        callInput.val(val + item.name + "," + data.path + "|");
                    }
                } else {
                    itemList.eq(i).find(".state").html(data.errmsg);
                    itemList.eq(i).find(".err").width("100%").html(data.errmsg);
                }
                itemList.find(".progressBox").hide();
            }, error: function () {
                itemList.eq(i).find(".progressBox").hide();
                itemList.eq(i).find(".state").html("请求失败");
                itemList.eq(i).find(".err").width("100%").html("请求失败");
            }
        })
    }
    input.bind("change", function (files) {

        if($(this).val()!="") {
           
            if(_this_parent.find('*').hasClass('zwb_upload_status')){


                    
                    _this.getImgSrc(this.files);

            }else{
                 _this.openWindow(this.files);
            }
           
        }
    });

};