$(document).ready(function(){
	var url = "/admin/Location/Manager";
    var img_buffer = "";

  
        
    

    /*Cancel Module*/
    $('.modal-header').on("click", ".close", function(){

        if($("#buffer").val() != "empty"){
            var dataForm = {pub_img: $("#buffer").val(),};
             $.ajax({
                type: "DELETE",
                url: url + '/delete_img',
                data: dataForm,
                dataType: 'json',
                success: function (data) {
                        $("#buffer").val("empty");
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }             
            });
        }

        if($("#pub_img").val() != "empty" && $("#btn_create_pub").val() == "create"){
            dataForm = {
                pub_img: img_buffer,
            };

            $.ajax({
                type: "DELETE",
                url: url + '/delete_img',
                data: dataForm,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                     $('#PubManager_img').trigger("reset");
                        $('#PubManager').trigger("reset");
                        $("#pub_img").val("empty");
                        $("#pub_img_path").val("empty");
                        $("#Pub_id").val("empty");
                        var img = "<div class='img_box'><div class='size'>[250x300]</div></div>"
                        $(".img_box").replaceWith(img);
                },
                error: function (data) {
                    console.log('Error:', data);
                } 
            
            });
        }

    });


	/*crate location*/
    $('.add_command_round').on("click", "#btn_create_Pub", function(){

        if($("#buffer").val() != "empty"){
            var dataForm = {pub_img: $("#buffer").val(),};
             $.ajax({
                type: "DELETE",
                url: url + '/delete_img',
                data: dataForm,
                dataType: 'json',
                success: function (data) {
                        $("#buffer").val("empty");
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }             
            });
        }

        if($("#pub_img").val() != "empty" && $("#btn_create_pub").val() == "create"){
            var dataForm = {pub_img: $("#pub_img").val(),};
             $.ajax({
                type: "DELETE",
                url: url + '/delete_img',
                data: dataForm,
                dataType: 'json',            
            });
        }
        /*Очистка буффера*/
        Location_name.style.border =  "1px solid #ccc"; Location_name.placeholder = "Назовите Локацию";
        Location_address.style.border =  "1px solid #ccc"; Location_address.placeholder = "Адрес Локации";
        Location_map.style.border =  "1px solid #ccc"; Location_map.placeholder = "Ссылка на карту Google";
        empty_zone.style.border =  "none"; empty_zone.style.background =  "#6da047";
        Location_description.style.border =  "1px solid #ccc"; Location_description.placeholder = "Описание Паба";

        var img = "<div class='img_box'><div class='size'>[250x300]</div></div>"
        $(".img_box").replaceWith(img);
        $("#Location_type").empty();
        $("#pub_img").val("empty");
        $("#pub_img_path").val("empty");
        $("#Pub_id").val("empty");
        $('#PubManager_img').trigger("reset");
        $('#PubManager').trigger("reset");
        /*Конец Очистки буффера*/

        $.get(url + '/type_of_pub', function(data){
            var selects = "";
            var count = 0;
            data.forEach(function(item,i,data){
                selects += "<option value='" + item.Type_name + "'>" + item.Type_name + "</option>";
                count = 1+i;
            });
            if(!count==0){
                $('#Location_type').append(selects);
                PubManagerCreateLabel.textContent = "Создать Локацию";
                $("#btn_create_pub").val("create");
                document.getElementById("btn_create_pub").innerHTML = "Создать";
                $('#PubManagerCreate').modal('show');
            }
            else{
                alert("Отсутствуют Типы Локаций!");
            }

            
                        
        });
        

    });


    $(document).ready( function() {
        $("#logo_pub").change(function(){
            var imgPub = new FormData();
            var path = $("#pub_img_path").val();
            var name = $("#pub_img").val();
            var id_pub = $("#Pub_id").val();

            if( $('#btn_create_pub').val() == "change" ){
                imgPub.append("buffer", $('#buffer').val());
                imgPub.append("type", $('#btn_create_pub').val());

                console.log(this.files);
                var Special_id = $('#Pub_id').val();
                imgPub.append("imgPub", this.files[0]);
                imgPub.append("pub_img_path", path);
                imgPub.append("pub_img_name", name);
                imgPub.append("pub_id", id_pub);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                    }
                })

                $.ajax({
                    type: "POST",
                    url: url + '/upload',
                    data: imgPub,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        console.log(data);
                        img_buffer = data.buffer;
                        if(img_buffer != "empty"){
                            var img = "<div class='img_box img_box-true' style='background-image: url(" + data.img_path + data.buffer +"); background-position: center center;'> </div>"
                            $(".img_box").replaceWith(img);
                        }
                        $("#buffer").val(data.buffer);

     
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    } 
                
                });
            }else{
                console.log(this.files);
                var Special_id = $('#Pub_id').val();
                imgPub.append("imgPub", this.files[0]);
                imgPub.append("pub_img_path", path);
                imgPub.append("pub_img_name", name);
                imgPub.append("pub_id", id_pub);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                    }
                })

                $.ajax({
                    type: "POST",
                    url: url + '/upload',
                    data: imgPub,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        console.log(data);
                        img_buffer = data.img_name;
                        if(data.buffer == "empty"){
                            $("#pub_img_path").val(data.img_path);
                        }else{
                            $("#pub_img_path").val(data.img_path + data.img_name);
                            var img = "<div class='img_box img_box-true' style='background-image: url(" + data.img_path + data.img_name +"); background-position: center center;'> </div>"
                            $(".img_box").replaceWith(img);
                        }
                        $("#pub_img").val(data.img_name);
                        $("#Pub_id").val(data.pub_id);
                        $("#buffer").val("empty");
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    } 
                
                });


            }
        

        });
    });         
    $(".modal-footer").on('click', '#btn_create_pub', function(){
        var length_name = $("#Location_name").val().length;
        if(length_name < 1){
            Location_name.style.border =  "1px solid #ff1212"; Location_name.placeholder = "Заполните строку!";
        }else{
            Location_name.style.border =  "1px solid #ccc"; Location_name.placeholder = "Назовите Локацию";
        }
        var length_address = $("#Location_address").val().length;
        if(length_address < 1){
            Location_address.style.border =  "1px solid #ff1212"; Location_address.placeholder = "Заполните строку!";
        }else{
            Location_address.style.border =  "1px solid #ccc"; Location_address.placeholder = "Адрес Локации";
        }
        var length_map = $("#Location_map").val().length;
        if(length_map < 1){
            Location_map.style.border =  "1px solid #ff1212"; Location_map.placeholder = "Заполните строку!";
        }else{
            Location_map.style.border =  "1px solid #ccc"; Location_map.placeholder = "Ссылка на карту Google";
        }
        if($("#btn_create_pub").val() == "create")
            var length_img = $("#pub_img").val().length;
            if(length_img < 1){
                empty_zone.style.border =  "1px solid #ff1212"; empty_zone.style.background =  "#ff1212";
            }else{
                empty_zone.style.border =  "none"; empty_zone.style.background =  "#6da047";
            }


        var length_description = $("#Location_description").val().length;
        if(length_description < 1){
            Location_description.style.border =  "1px solid #ff1212"; Location_description.placeholder = "Заполните строку!";
        }else{
            Location_description.style.border =  "1px solid #ccc"; Location_description.placeholder = "Описание Паба";
        }

        if(length_name == 0 || length_address == 0 || length_map == 0 || length_img == 0 || length_description == 0){
            return false;
        }
        else{

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                }
            })

            dataForm = {
                Location_id: $("#Pub_id").val(),
                Location_name: $("#Location_name").val(),
                Location_address: $("#Location_address").val(),
                map: $("#Location_map").val(),
                Location_img: $("#pub_img_path").val(),
                img_name: $("#pub_img").val(),
                Location_type: $("#Location_type").val(),
                Location_description: $("#Location_description").val(),
                color: $("#pub_color").val(),
            }

            if($("#btn_create_pub").val() == "create")
                $.ajax({
                    type: "POST",
                    url: url + '/crate_pub',
                    data: dataForm,
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        var pub_id = data;
                        $("#warning").remove();
                        $.get(url + '/get_pub/' + pub_id, function (data){
                            console.log(data);
                            var Pub = "<div class='col-md-4'><div class='pub_box pub" + data.id + "'><div class='pub_box_background' style='background-image: url(" + data.Location_img + ");'>";
                            Pub +="<div class='full_screen'><button class='show_pub' value='" + data.id + "'><i class='fas fa-eye'></i></button></div><div class='pub_box_manager_panel'><h2>" + data.Location_name +"</h2>";
                            Pub +="<div class='manager_button'><button class='btn btn-warning edit-pub' value='" + data.id + "'>Редактировать<i class='fas fa-cog'></i></button>"
                            Pub +="<button class='btn btn-danger delete-pub' value='" + data.id + "'>Удалить <i class='fas fa-trash-alt'></i></button></div></div><div class='pub_box_manager_type'><h2 id='pub_type" + data.id + "'>" + data.Location_type + "</h2></div></div></div></div>";
                            $("#add_pubs").append(Pub);
                            $("#pub_img").val("empty");
                            $("#pub_img_path").val("empty");
                            $("#Pub_id").val("empty");
                            $('#PubManagerCreate').modal('hide');

                            $('#PubManager_img').trigger("reset");
                            $('#PubManager').trigger("reset");
                            
                            var img = "<div class='img_box'><div class='size'>[250x300]</div></div>";
                            $(".img_box").replaceWith(img);
                            
                        })
                    },
                    error: function (data) {
                        $("html").empty().append(data.responseText);
                        console.log('Error:', data.responseText);
                    } 
                
                });

        }
    });








    

	/*Cancel*/
	$('#Delete_Change').on("click", "#btn_cancel", function(e){
        $('#Delete_Change').modal('hide');
    });
});