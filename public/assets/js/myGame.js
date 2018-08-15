$(document).ready(function(){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
        }
    })

    $(".content_popular").on("click", ".show_pub", function(){
    	var Pub_id = $(this).attr("pub-id");

    	$.get("/home/myCommand/getPub/" + Pub_id, function(data){
    		PubName.textContent = data.Location_name;
			var img = "<div class='pub_img_box' style='background-image: url(" + data.Location_img +"); background-position: center center;'> </div>"
            $(".pub_img_box").replaceWith(img);
            document.getElementById("pub_info_content_type").innerHTML = data.Location_type + " <span id='Pub_span_name'></span>"
            document.getElementById("Pub_span_name").innerHTML = data.Location_name;
            pub_info_content_address.textContent = data.Location_address;
            pub_desc.textContent = data.Location_description;
            $('#ShowPub').modal('show');
    	});
    });

    $(".content_popular").on("click", ".show_pub_map", function(){
    	var Pub_id = $(this).attr("pub-id");

    	$.get("/home/myCommand/getPub/" + Pub_id, function(data){
    		PubMap.textContent = "Местоположение";
			document.getElementById("map_url").innerHTML = data.Location_map;
            $('#ShowPubMap').modal('show');
    	});
    });

    $(".content_popular").on("click", ".apply_round", function(){
        var game_id = $(this).attr('game-id');
        var command_id = $(this).attr('command-id');

        $("#game_id").val(game_id);
        $("#command_id").val(command_id);

        $(".commands").empty();
        $(".apply_commands").empty();
        // $.get("/home/myCommandApplyers/" + game_id + '/' + command_id, function(data){
        //     if(data.users != "None"){
        //         var count = 0;
        //         data.users.forEach( function(element, index) {
        //             if(count == 0){
        //                 if(element.user_img_path == "None"){
        //                     var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
        //                         command_html += "<div class='col-sm-3 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-default disabled' value='" + element.id + "'>Командир</button></div></div></div>";
        //                 }
        //                 else{
        //                     var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
        //                         command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-default disabled' value='" + element.id + "'>Командир</button></div></div></div>";
        //                 }
        //                 count++;
        //             }else{
        //                 if(element.user_img_path == "None"){
        //                     var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
        //                         command_html += "<div class='col-sm-3 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger delete_user' value='" + element.id + "'>Выгнать</button></div></div></div>";
        //                 }
        //                 else{
        //                     var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
        //                         command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger delete_user' value='" + element.id + "'>Выгнать</button></div></div></div>";
        //                 }
        //             }
                    
        //             $(".commands").append(command_html);
                    
        //         });
        //     }else{
        //         var command_html = "<div class='row command_row'><h1 style='font-size: 1.5em;margin: 10px 0 10px;text-align: center;font-weight: 900;letter-spacing: 0.4px;color: #1D5E6A;'>Нет Заявок</h1></div>";
        //         $("#list_commands").append(command_html);
        //     }

        //     if(data.apply_users != "None"){
        //         data.apply_users.forEach( function(element, index) {
        //             if(element.user_img_path == "None"){
        //                 var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
        //                     command_html += "<div class='col-sm-3 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-success accept_apply' value='" + element.id + "'>Принять заявку</button></div></div></div>";
        //             }
        //             else{
        //                 var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
        //                     command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-success accept_apply' value='" + element.id + "'>Принять заявку</button></div></div></div>";
        //                 }
        //             $(".apply_commands").append(command_html);
                    
        //         });
        //     }else{
        //         var command_html = "<div class='row command_row'><h1 style='font-size: 1.5em;margin: 10px 0 10px;text-align: center;font-weight: 900;letter-spacing: 0.4px;color: #1D5E6A;'>Нет Заявок</h1></div>";
        //         $("#list_commands").append(command_html);
        //     }
        //     MyCommands.textContent = "Состав Команды";
        //     MyCommandsApply.textContent = "Заявки в команду";
        //     $("#show_command").modal('show');
        // });
    });

    // $(".apply_commands").on("click", ".accept_apply", function(){
    //     var id_user = $(this).val();
    //     var formData = {
    //         game_id:$("#game_id").val(),
    //         command_id:$("#command_id").val(),
    //     }
    //     $.ajax({
    //             type: "POST",
    //             url: "/home/myCommand/AcceptApply/" + id_user,
    //             data:formData,
    //             dataType:'json',
    //             success: function (data) {
    //                 console.log(data);
    //                 $(".cr" + id_user).remove();
    //                 if(data.user_img_path == "None"){
    //                     var command_html = "<div class='row command_row cr" + data.id + "' style='margin-top: 20px;'><div class='form-group command" + data.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
    //                         command_html += "<div class='col-sm-3 command_name list_margin'>" + data.login + "</div><div class='col-sm-3 list_margin'>" + data.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger delete_user' value='" + data.id + "'>Выгнать</button></div></div></div>";
    //                 }
    //                 else{
    //                     var command_html = "<div class='row command_row cr" + data.id + "'><div class='form-group command" + data.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + data.user_img_path + "'/></div></div>";
    //                         command_html += "<div class='col-sm-4 command_name list_margin'>" + data.login + "</div><div class='col-sm-3 list_margin'>" + data.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger delete_user' value='" + data.id + "'>Выгнать</button></div></div></div>";
    //                 }
    //                 $(".commands").append(command_html);
    //             },
    //             error: function (data) {
    //                 console.log('Error:', data);
    //             }
    //         });
    // });

    // $(".commands").on("click", ".delete_user", function(){
    //     var id_user = $(this).val();
    //     var formData = {
    //         game_id:$("#game_id").val(),
    //         command_id:$("#command_id").val(),
    //     }
    //     $.ajax({
    //             type: "DELETE",
    //             url: "/home/myCommand/DeleteApply/" + id_user,
    //             data:formData,
    //             dataType:'json',
    //             success: function (data) {
    //                 $(".cr" + id_user).remove();
    //             },
    //             error: function (data) {
    //                 console.log('Error:', data);
    //             }
    //         });
    // });

    
});