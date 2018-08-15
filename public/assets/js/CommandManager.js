$(document).ready(function(){
	var url = "/home";
	var user_id = $("#user_id").val();
	var delete_id = 0;
	var delete_count = 0;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
        }
    })


	$(".home_game_boxs").on('click', '.btn_create_command',function(){
		var count = $(this).val();
		var command_name_input = "<h1 class='command_name_label'>Название Команды</h1><div class='create_game_name'><input type='name' class='command_input' placeholder='Название команды' id='command_name" + count + "'/><div class='btn-group btn-group-sm'><button class='btn btn-success create_game' style='border-radius:0;' value='" + count + "'><i class='fas fa-check'></i></button>";
        	command_name_input += "<button class='btn btn-danger cancel_game' value='" + count + "'><i class='fas fa-trash-alt'></i></button></div></div>";
		$(".command" + count).addClass("add_game_border").empty().append(command_name_input);
	});



	$(".home_game_boxs").on('click', '.cancel_game',function(){
		var count = $(this).val();
		var add_comand_game = "<div class='add_comand_game command" + count + "'><div class='add_command_round'><span><button type='button' class='btn_create_command' value='" + count + "'>+</button></span></div>";
            add_comand_game +="<div class='add_command_button'>Создать команду</div></div>";
        $(".command" + count).replaceWith(add_comand_game);
        $(".command_warning").remove();
	});


	$(".home_game_boxs").on('click', '.create_game',function(){
		var count = $(this).val();
		$(".command_warning").remove();
		$(".replace_command" + count).parent().removeClass("warning_offset");

		var length_input = $("#command_name" + count).val().length; /*Защита от ввода пустой формы*/
			if(length_input < 1){
			    $("#command_name" + count).addClass("warning_input");
			    return false;
			}else{
			    $("#command_name" + count).removeClass("warning_input");
			}

		var command_name_find  = $("#command_name" + count).val();
		if(
			command_name_find.indexOf('_') !== -1 ||
			command_name_find.indexOf('<') !== -1 ||
			command_name_find.indexOf('[') !== -1 ||
			command_name_find.indexOf(']') !== -1 ||
			command_name_find.indexOf('>') !== -1 ||
			command_name_find.indexOf(',') !== -1 ||
			command_name_find.indexOf('=') !== -1 ||
			command_name_find.indexOf('.') !== -1 ||
			command_name_find.indexOf('|') !== -1 ||
			command_name_find.indexOf('(') !== -1 ||
			command_name_find.indexOf('"') !== -1 ||
			command_name_find.indexOf(')') !== -1 ||
			command_name_find.indexOf('None') !== -1 ||
			command_name_find.indexOf('->') !== -1 ){
			alert('Название команд не должно включать такие символы как: "_", "<", ">", "[", "]", ",", "=", ".", "|", "(", ")", "->"!');
			return false;
		}
		
		
		

		var formData = {
			Command_name_val: $("#command_name" + count).val()
		};	

		$.ajax({
	            type: "GET",
	            url: url + '/checkUnique/Command_name',
	            data: formData,
	            success: function (data) {
	                if(data.length == 1){
	                	$(".replace_command" + count).parent().addClass("warning_offset");
	                	$(".replace_command" + count).append("<div class='command_warning'>Команда уже существует</div>");

	                }
	                else{

	                	var formData = {
							Command_name_val: $("#command_name" + count).val(),
							count: count,
							user_id: user_id,
						};	

	                	$.ajax({
					        type: "POST",
					        url: url + '/create/Command',
					        data: formData,
					        success: function (data) {
					            var command_box = "<div class='home_game_one_box_img'>";
					            	command_box += "<div class='update_command_img img_after_update" + data.command.count +"'><i class='fas fa-smile'></i><form enctype='multipart/form-data' class='command_img'><div class='file-upload img-transparent' id='empty_zone'><label><input type='file' class='full_box' count-game='" + data.command.count +"' name-game='" + data.command.command_name +"' accept='image/*'><span>Выберите файл</span></label></div></form></div>";
                                    command_box += "</div></div><div id='accordion' class='panel-group'><div class='panel'>";
                                	command_box += "<div class='panel-heading panel_game'><h4 class='panel-title'><a href='#game-" + data.command.count + "' data-parent='#accordion' data-toggle='collapse'>" + data.command.command_name + "</a></h4></div>";
                                	command_box += "<div id='game-" + data.command.count + "' class='panel-collapse collapse out'><div class='panel-body'><div class='link_box'><a class='command_edit' command-count='" + data.command.count + "' command-name='" + data.command.command_name + "'>Переименовать</a><a class='myCommand' href='" + data.url + '/' + data.command.id +"' command-id='" + data.command.id + "'>Мои Игры</a><a class='command_delete' command-id='" + data.command.id + "' command-count='" + data.command.count + "' command-name='" + data.command.command_name + "'>Удалить</a></div></div></div></div></div>";
                                $(".replace_command" + count).replaceWith(command_box);	
                            },
					        error: function (data) {
					            console.log('Error:', data);
					        }
					    });
	                }
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	    }); 
	});


	$(".home_game_boxs").on('change', ".full_box", function(e){
	            var Avatar = new FormData();
	            var count_command = $(this).attr('count-game');
	            Avatar.append("Avatar", this.files[0]);
	            Avatar.append("command_name", $(this).attr('name-game'));
	            Avatar.append("count_command", count_command);
	            Avatar.append("user_id", user_id);
	                $.ajax({
	                    type: "POST",
	                    url: url + '/upload/CommandAvatar',
	                    data: Avatar,
	                    dataType: 'json',
	                    processData: false,
	                    contentType: false,
	                    success: function (data) {
	                        console.log(data);
	                        $(".img_after_update" + count_command + " svg").remove();
	                        $(".command_img" + count_command).remove();
	                        $(".img_after_update" + count_command).append("<img class='command_img" + count_command + "' src='" + data.img_path + "'>")
	                    },
	                    error: function (data) {
	                        console.log('Error:', data);
	                    } 
	                
	                });
	});

	$(".home_game_boxs").on('click', '.command_edit',function(){
		var count = $(this).attr("command-count");
		var name = $(this).attr("command-name");
		$("#edit_command_name").val(name);
		$(".command_warning").remove();
		$("#CommandEdit").modal("show");

		$("#CommandEdit").on('click', '#btn-change-command',function(){
			$(".command_warning").remove();
			if($("#edit_command_name").val() == name){ /*Проверка на сходство с оригиналом*/
				$("#CommandEdit").modal("hide");
				return false;
			}

			var length_input = $("#edit_command_name").val().length; /*Защита от ввода пустой формы*/
			if(length_input < 1){
			    $("#edit_command_name").addClass("warning_input");
			    return false;
			}else{
			    $("#edit_command_name").removeClass("warning_input");
			}

			var command_name_find  = $("#edit_command_name").val();
			if(
				command_name_find.indexOf('_') !== -1 ||
				command_name_find.indexOf('<') !== -1 ||
				command_name_find.indexOf('[') !== -1 ||
				command_name_find.indexOf(']') !== -1 ||
				command_name_find.indexOf('>') !== -1 ||
				command_name_find.indexOf(',') !== -1 ||
				command_name_find.indexOf('=') !== -1 ||
				command_name_find.indexOf('.') !== -1 ||
				command_name_find.indexOf('|') !== -1 ||
				command_name_find.indexOf('(') !== -1 ||
				command_name_find.indexOf('"') !== -1 ||
				command_name_find.indexOf(')') !== -1 ||
				command_name_find.indexOf('None') !== -1 ||
				command_name_find.indexOf('->') !== -1 ){
				alert('Название команд не должно включать такие символы как: "_", "<", ">", "[", "]", ",", "=", ".", "|", "(", ")", "->"!');
				return false;
			}
			var formData = {
				Command_name_val: $("#edit_command_name").val()
			};

			$.ajax({
	            type: "GET",
	            url: url + '/checkUnique/Command_name',
	            data: formData,
	            success: function (data) {
	                if(data.length == 1){
	                	$(".command_warning").remove();
	                	$(".edit_command_warning").append("<div class='command_warning'>Команда уже существует</div>");
	                }
	                else{
	                	var formData = {
							Command_name_val: $("#edit_command_name").val(),
							count: count,
							user_id: user_id,
						};
						$.ajaxSetup({
					        headers: {
					            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
					        }
					    })	
						$.ajax({
					        type: "POST",
					        url: url + '/change/Command',
					        data: formData,
					        dataType: 'json',
					        success: function (data) {
					            console.log(data);
					            document.getElementById("command_link_name" + data.command.count).innerHTML = data.command.command_name;
					            var accordion ="<div id='game-" + data.command.count + "' class='panel-collapse collapse out'><div class='panel-body'><div class='link_box'><a class='command_edit' command-count='" + data.command.count + "' command-name='" + data.command.command_name + "'>Переименовать</a><a class='myCommand' href='" + data.url + '/' + data.command.id +"' command-id='" + data.command.id + "'>Мои Игры</a><a class='command_delete' command-id='" + data.command.id + "' command-count='" + data.command.count + "' command-name='" + data.command.command_name + "'>Удалить</a></div></div></div>";
                                    $("#game-" + data.command.count).replaceWith(accordion);
                                    $("#CommandEdit").modal("hide");
                            },
					        error: function (data) {
					            console.log('Error:', data);
					        }
					    });	
	                	
	                }
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	    	}); 	
		});
	});


	$(".home_game_boxs").on('click', '.command_delete',function(){
		delete_id = $(this).attr("command-id");
		delete_count = $(this).attr("command-count");
		var name = $(this).attr("command-name");
		document.getElementById("CommandDeleteLabel").innerHTML = "Вы уверены, что хотите удалить команду \"" + name + "\" ?";
		$("#CommandDelete").modal('show');

	});

	$("#CommandDelete").on('click', '#btn_cancel_command',function(){
			$("#CommandDelete").modal('hide');
			return false;
		});

		$("#CommandDelete").on('click', '#btn_delete_command',function(){
			var formData = {
				id:delete_id,
			};

			$.ajax({
				type: "DELETE",
				url: url + '/delete/Command',
				data: formData,
				dataType: 'json',
				success: function (data) {
				    console.log(data);
				    $("#CommandDelete").modal('hide');
				    var add_command_box = "<div class='home_game__one_box'><div class='home_game_one_box_img empty_box replace_command" + delete_count + "'><div class='add_comand_game command" + delete_count + "'>";
                        add_command_box +="<div class='add_command_round'><span><button type='button' class='btn_create_command' value='" + delete_count + "'>+</button></span></div><div class='add_command_button'>Создать команду</div></div></div></div>";
				    $(".img_after_update" + delete_count).parent().parent().replaceWith(add_command_box);
				 },
				 error: function (data) {
				     console.log('Error:', data);
				 },
			});
		});



	$(".link_box").on('click', ".myCommandConsist", function(){
		var command_id = $(this).attr("command-id");
		var is_commander = $(this).attr("commander");
		$.ajax({
			type: "GET",
			url: url + '/get/commandConsist/' + command_id,
			success: function (data) {
				var count = 0;
				$(".commands").empty();
				$(".apply_commands").empty();
				if(is_commander != "no"){
					data.consist.forEach( function(element, index) {
	                    if(count == 0){
	                        if(element.user_img_path == "None"){
	                            var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
	                                command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-default disabled' value='" + element.id + "'>Командир</button></div></div></div>";
	                        }
	                        else{
	                            var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
	                                command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-default disabled' value='" + element.id + "'>Командир</button></div></div></div>";
	                        }
	                        count++;
	                    }else{
	                        if(element.user_img_path == "None"){
	                            var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
	                                command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger delete_user' value='" + element.id + "'>Выгнать</button></div></div></div>";
	                        }
	                        else{
	                            var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
	                                command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger delete_user' value='" + element.id + "'>Выгнать</button></div></div></div>";
	                        }
	                    }
	                    $(".commands").append(command_html);
	            	});
            		if(data.apply != "None"){
            			data.apply.forEach( function(element, index) {
	                    	if(element.user_img_path == "None"){
								var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
								command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><div class='btn-group btn-group-sm'><button class='btn btn-success accept_user' value='" + element.id + "'><i class='fas fa-check'></i></button><button class='btn btn-danger cancel_user' value='" + element.id + "'><i class='fas fa-trash-alt'></i></button></div></div></div></div>";
							}
							else{
								var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
								command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><div class='btn-group btn-group-sm'><button class='btn btn-success accept_user' value='" + element.id + "'><i class='fas fa-check'></i></button><button class='btn btn-danger cancel_user' value='" + element.id + "'><i class='fas fa-trash-alt'></i></button></div></div></div></div>";
							}
		                    $(".apply_commands").append(command_html);
			                });
	            		}else{
	            			$(".apply_commands").append("<h1 style='text-align:center;'>Нет Заявок</h1>");
	            		}
	            	}else{
	            		data.consist.forEach( function(element, index) {
		                    if(count == 0){
		                        if(element.user_img_path == "None"){
		                            var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
		                                command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-default disabled' value='" + element.id + "'>Командир</button></div></div></div>";
		                        }
		                        else{
		                            var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
		                                command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-default disabled' value='" + element.id + "'>Командир</button></div></div></div>";
		                        }
		                        count++;
		                    }else{
		                        if(element.user_img_path == "None"){
		                            var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
		                                command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-default disabled' value='" + element.id + "'>Игрок</button></div></div></div>";
		                        }
		                        else{
		                            var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
		                                command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-default disabled' value='" + element.id + "'>Игрок</button></div></div></div>";
		                        }
		                    }
		                    $(".commands").append(command_html);
	            		});
	            		$(".apply_commands").append("<h2 style='text-align: center;'>Просмотр запрещен!</h2>");
	            	}

				
            	$("#command_id").val(command_id);
				$("#show_command").modal("show");
			},
			error: function (data) {
				console.log('Error:', data);
			},
		});
	});

	$(".commands").on('click', ".delete_user",function(){
		var formData = {
			command_id: $("#command_id").val(),
			user_id: $(this).val(),
		}
		$.ajax({
			type: "POST",
			url: url + '/get/commandConsist/deleteUser',
			data: formData,
			dataType: 'json',
			success: function (data) {
				$(".commands .cr" + formData.user_id).remove();
			},
			error: function (data) {
				$("html").empty().append(data.responseText);
			},
		});
	})


	$(".apply_commands").on('click', ".accept_user",function(){
		var formData = {
			command_id: $("#command_id").val(),
			user_id: $(this).val(),
		}
		$.ajax({
			type: "POST",
			url: url + '/get/commandConsist/applyUser',
			data: formData,
			dataType: 'json',
			success: function (data) {
				var element = data;
				if(element.user_img_path == "None"){
	                var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.id + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
	            	command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger delete_user' value='" + element.id + "'>Выгнать</button></div></div></div>";
	            }
	            else{
	                var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.id + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.user_img_path + "'/></div></div>";
	            	command_html += "<div class='col-sm-4 command_name list_margin'>" + element.login + "</div><div class='col-sm-3 list_margin'>" + element.name + "</div><div class='col-sm-3 list_margin'><button class='btn btn-danger delete_user' value='" + element.id + "'>Выгнать</button>	</div></div></div>";
	            }
				$(".commands").append(command_html);
				$(".apply_commands .cr" + formData.user_id).remove();
			},
			error: function (data) {
				$("html").empty().append(data.responseText);
			},
		});
	})
	$(".apply_commands").on('click', ".cancel_user",function(){
		var formData = {
			command_id: $("#command_id").val(),
			user_id: $(this).val(),
		}
		$.ajax({
			type: "POST",
			url: url + '/get/commandConsist/cancelUser',
			data: formData,
			dataType: 'json',
			success: function (data) {
				$(".apply_commands .cr" + formData.user_id).remove();
			},
			error: function (data) {
				$("html").empty().append(data.responseText);
			},
		});
	})


	$(".applyers_list").on('click', '.add_command_input', function(){
        $.get('/admin/game/get_command', function(data){
            var array = [];
            if(data != "None"){
                for(var i=0; i<data.length; i++){
                    array.push(data[i].command_name);
                }
                $(".add_command_input").autocomplete({ //на какой input:text назначить результаты списка
                    source: array
                });
            }else{
                $(".add_command_input").replaceWith("Команды не найдены");
                setTimeout(function() { $(".find_input" + index).replaceWith("<i class='fas fa-times-circle not_found not_found" + index + "'></i>") }, 3000);
            } 
        });

    });

	$(".applyers_list").on('click', '.apply_command-btn', function(){
        
        var formData = {
        	command_name:$(".add_command_input").val(),
        }
        $.ajax({
			type: "GET",
			url: url + '/get/commandConsist/checkCommand/check',
			data: formData,
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data == "Exist"){
					alert("Вы уже подавали Заявку");
					$(".add_command_input").val("");
					return false;
				}
				if(data == "Error"){
					alert("Команда не найдена!");
					return false;
				}
				if(data == "Present"){
					alert("Вы находитесь в этой команде!");
					$(".add_command_input").val("");
				}
				else{
					var table_row = "<tr id='applyer" + data.id + "'> <th scope='row' class='applyer' id='applyer" + data.id + "'>" + formData.command_name + "<div class='mini_box_color'></div></th>";
						table_row += "<td>На рассмотрении</td>";
						table_row += "<td>" + data.updated_at + "</td>";
					$(".applyers_list table tbody").append(table_row);
					$(".add_command_input").val("");
				}
			},
			error: function (data) {
				$("html").empty().append(data.responseText);
			},
		});

    });

  	$(".link_box").on('click', '.myCommandExit', function(){
  		var formData = {
			command_id: $(this).attr("command-id"),
		}
		var ask = prompt("Напишите 'ДА' - для подтверждения!");
		if(ask == "ДА"){
	  		$.ajax({
				type: "DELETE",
				url: url + '/delete/commandConsist/ExitCommand',
				data: formData,
				dataType: 'json',
				success: function (data) {
					console.log(data);
					$(".applyer_box" + formData.command_id).remove();
				},
				error: function (data) {
					$("html").empty().append(data.responseText);
				},
			});
	  	}
  	}); 

});

