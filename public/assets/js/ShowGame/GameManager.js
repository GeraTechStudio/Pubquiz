$(document).ready(function(){

	$.ajaxSetup({
        headers: {
        	'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
    	},
    	 statusCode : {
		    401 : function () {
		      window.location = "/login";
		    }
		  }
    })

	$(".game_locations").on("click",".show_pub", function(){
		var PubID = $(this).val();
		$("#shown_pub_id").val(PubID);
		var count = 0;
		var commands = $(this).attr('pub-command');
		if(commands == "None"){
			var command_html = "<div class='row command_row'><h1 style='font-size: 1.5em;margin: 10px 0 10px;text-align: center;font-weight: 900;letter-spacing: 0.4px;color: #1D5E6A;'>Команды Отсутствуют!</h1></div>";
	        $("#list_commands").append(command_html);
		}else{
			commands = commands.split(' | ');

			var dataForm = {
				commands: commands,
			};
			
			$.ajax({
	            type: "GET",
	            url: "/Calendar/get/Command/" + $("#id_game").val(),
	            data: dataForm,
	            dataType: 'json',
	            success: function (data) {
	            	$("#list_commands").empty();
	            	data.commands.forEach( function(element, index) {
	            		if(data.close == true){	            		
		            		if(element.command_img_path == "None"){
								var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.command_name + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
								if(element.status == "Open"){
									command_html += "<div class='col-sm-6 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-3 list_margin'></div></div></div>";
								}
								if(element.status == "Full"){
									command_html += "<div class='col-sm-6 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-3 list_margin'></div></div></div>";
								}
								if(element.status == "Close"){
									command_html += "<div class='col-sm-6 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-3 list_margin'></div></div></div>";
								}
								if(element.status == "Delete"){
									command_html += "<div class='col-sm-6 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger cancel_command' value='" + element.id + "'>Cнять заявку</button></div></div></div>";
								}
							}
							else{
								var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.command_name + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.command_img_path + "'/></div></div>";
								if(element.status == "Open"){
									command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-3  '></div></div></div>";
								}
								if(element.status == "Full"){
									command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-3  '></div></div></div>";
								}
								if(element.status == "Close"){
									command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-3  '></div></div></div>";
								}
								if(element.status == "Delete"){
									command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-3  '><button class='btn btn-danger cancel_command  ' value='" + element.id + "'>Cнять заявку</button></div></div></div>";
								}
							}
						}else{
							if(element.command_img_path == "None"){
								var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.command_name + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
								if(element.status == "Open"){
									command_html += "<div class='col-sm-5 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-3 list_margin'></div></div></div>";
								}
								if(element.status == "Full"){
									command_html += "<div class='col-sm-5 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-3 list_margin'></div></div></div>";
								}
								if(element.status == "Close"){
									command_html += "<div class='col-sm-5 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-3 list_margin'></div></div></div>";
								}
								if(element.status == "Delete"){
									command_html += "<div class='col-sm-5 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger cancel_command' value='" + element.id + "'>Cнять заявку</button></div></div></div>";
								}
							}
							else{
								var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.command_name + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.command_img_path + "'/></div></div>";
								if(element.status == "Open"){
									command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-3'></div></div></div>";
								}
								if(element.status == "Full"){
									command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-3'></div></div></div>";
								}
								if(element.status == "Close"){
									command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-3'></div></div></div>";
								}
								if(element.status == "Delete"){
									command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-3'><button class='btn btn-danger cancel_command  ' value='" + element.id + "'>Cнять заявку</button></div></div></div>";
								}
							}
						}
						
						$("#list_commands").append(command_html);
	            	});
	            },
	            error: function (data) {
	            	$("html").empty().append(data.responseText);
	                console.log('Error:', data);
	            }
			});
		}
		$.ajax({
	            type: "GET",
	            url: "/Calendar/get/Pub/" + PubID,
	            success: function (data) {
					console.log(data);
					PubName.textContent = data.Location_name;
					PubMap.textContent = "Местоположение";
					PubCommand.textContent = "Команды";
					var img = "<div class='pub_img_box' style='background-image: url(" + data.Location_img +"); background-position: center center;'> </div>"
		            $(".pub_img_box").replaceWith(img);
		            document.getElementById("pub_info_content_type").innerHTML = data.Location_type + " <span id='Pub_span_name'></span>"
		            document.getElementById("Pub_span_name").innerHTML = data.Location_name;
		            pub_info_content_address.textContent = data.Location_address;
		            pub_desc.textContent = data.Location_description;
		            document.getElementById("map_url").innerHTML = data.Location_map;
		            $('#ShowPub').modal('show');
	        	},
	        	error: function (data) {
	                console.log('Error:', data);
	            },
		});	
	});


	$(".game_locations").on("click",".apply_command", function(){
		var Pub_id = $(this).val();
		$("#command_add_pub_id").val(Pub_id);
		$.ajax({
	            type: "GET",
	            url: "/Calendar/get/createdCommandsUser/" + $("#id_game").val(),
	            success: function (data) {
	            	console.log(data)
	            	$(".addCommand_content").empty();
	            	if(data.freeCommands == "None"){
	            		if(data.freeCreateCommand == 0){
	            			var message = "<h1 style='font-size:1.5em;'>Вы использовали все команды на эту игру!</h1>";
	            			$(".addCommand_content").append(message);
	            		}else{
	            			if(data.freeCreateCommand == 5){
	            				var message = "<h1 style='font-size:1.5em;'>Чтобы подать заявку на игру, создайте команду, либо присоединитесь к другой команде!</h1>";
	            				$(".addCommand_content").append(message);
	            			}else{
	            				var message = "<h1 style='font-size:1.5em;'>Создайте команду! На эту игру вы еще можете подать " + data.freeCreateCommand + " Команды(Команду).</h1>"
	            				$(".addCommand_content").append(message);
	            			}
	            			
	            		}
	            		$(".add_command_to_game").val("disabled");
	            		$(".add_command_to_game").addClass("disabled");
	            	}
	            	else{   
	            		var select = "<div class='row'><div class='form-group'><label for='select_command' class='col-sm-4 control-label'>Выберите Команду</label><div class='col-sm-8'><select id='select_command' class='form-control'>";
	            		data.freeCommands.forEach( function(element, index) {
	            			select += "<option value='" + element.id + "'>" + element.command_name + "</option>";
	            		});
	            		select+= "</select></div></div></div>";
	            		$(".addCommand_content").append(select);
	            		$(".add_command_to_game").val("add");
	            		$(".add_command_to_game").removeClass("disabled");
	            	}
	            	$("#addCommand").modal('show');
	            },
	            error: function (data) {
	            	$("html").empty().append(data.responseText);
	                console.log('Error:', data);
	            }
			});
	});

	$("#addCommand").on("click",".add_command_to_game", function(){
		if($(this).val() == "add"){

			var formData = {
				command_id: $("#select_command").val(),
				Pub_id: $("#command_add_pub_id").val(),
			}
			$.ajax({
	          type: "POST",
	          url: '/Calendar/addCommand/' + $("#id_game").val(),
	          data: formData,
	          dataType: 'json',
	          success: function (data) {
	          	console.log(data);
	          	document.getElementById("show_pub" + formData.Pub_id).setAttribute("pub-command", data.attribute);
	          	$("#table_diagram" + formData.Pub_id).empty();

	          	var table_diagram = "<div class='pie'><div class='clip1'><div class='slice1 slice" + formData.Pub_id + "1'></div></div><div class='clip2'><div class='slice2 slice" + formData.Pub_id + "2'></div></div><div class='status'>" + data.Busy_places + "/" + data.pub_free_places + "</div></div>";
	          	$("#table_diagram" + formData.Pub_id).append(table_diagram);

                var firstHalfAngle = 180;
                var secondHalfAngle = 0;
                var drawAngle = data.Busy_places / data.pub_free_places * 360;
                if (drawAngle <= 180) {
                    firstHalfAngle = drawAngle;
                } else {
                    secondHalfAngle = drawAngle - 180;
                }
                var class1 = "."+ 'slice' + formData.Pub_id + '1';
                var class2 = "."+ 'slice' + formData.Pub_id + '2';
                rotate($(class1), firstHalfAngle);
                rotate($(class2), secondHalfAngle);


                if(data.pub_free_places <= data.Busy_places){
                	$(".manager_button" + formData.Pub_id).empty().append("<button class='btn btn-default disabled' value='" + formData.Pub_id + "'>Подать Заявку</button>");
                }
	         	$("#addCommand").modal('hide');
	          },
	          error: function (data) {
	              console.log('Error:', data);
	          }
	      });
		}
	});
	
	$("#list_commands").on("click",".cancel_command", function(){
		var formData = {
			command_id: $(this).val(),
			Pub_id: $("#shown_pub_id").val(),
		}
		$.ajax({
	            type: "DELETE",
	            url: "/Calendar/game/cancel_command/" + $("#id_game").val(),
	            data: formData,
	            dataType: 'json',
	            success: function (data){
	            	console.log(data);
	            	document.getElementById("show_pub" + formData.Pub_id).setAttribute("pub-command", data.attribute);
	            	if(data.table != 0){
	            		$(".cr" + formData.command_id).remove();
	            	}
	            	else{
	            		$(".cr" + formData.command_id).remove();
	            		var command_html = "<div class='row command_row'><h1 style='font-size: 1.5em;margin: 10px 0 10px;text-align: center;font-weight: 900;letter-spacing: 0.4px;color: #1D5E6A;'>Команды Отсутствуют!</h1></div>";
	            		$("#list_commands").append(command_html);
	            	}
	            	$("#table_diagram" + formData.Pub_id).empty();

		          	var table_diagram = "<div class='pie'><div class='clip1'><div class='slice1 slice" + formData.Pub_id + "1'></div></div><div class='clip2'><div class='slice2 slice" + formData.Pub_id + "2'></div></div><div class='status'>" + data.table + "/" + data.places + "</div></div>";
		          	$("#table_diagram" + formData.Pub_id).append(table_diagram);

	                var firstHalfAngle = 180;
	                var secondHalfAngle = 0;
	                var drawAngle = data.table / data.places * 360;
	                if (drawAngle <= 180) {
	                    firstHalfAngle = drawAngle;
	                } else {
	                    secondHalfAngle = drawAngle - 180;
	                }
	                var class1 = "."+ 'slice' + formData.Pub_id + '1';
	                var class2 = "."+ 'slice' + formData.Pub_id + '2';
	                rotate($(class1), firstHalfAngle);
	                rotate($(class2), secondHalfAngle);
	            	
	            },
	            error: function (data) {
	            	$("html").empty().append(data.responseText);
	                console.log('Error:', data);
	            }
			});
	});

	$("#list_commands").on("click",".apply_for_game", function(){
		var dataForm = {
			command_id: $(this).val(),
			Pub_id: $("#shown_pub_id").val(),
		};

		$.ajax({
	            type: "POST",
	            url: "/Calendar/apply/inCommand/" + $("#id_game").val(),
	            data: dataForm,
	            dataType: 'json',
	            success: function (data) {
	            	console.log(data);
	            	
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
			});
	});

});
