$(document).ready(function(){
	var url = "/admin";
    var url_preloader = $("#preloader").val();

	/*change admin profile*/
    $('#admin').on("click", ".open-modal", function(){
    	var admin_id = $(this).val();
    	$.get(url + '/' + admin_id, function (data){
    		console.log(data);
    		$('#admin_id').val(data.id);
    		$('#login').val(data.login);
    		$('#name').val(data.name);
    		$('#email').val(data.email);
    		$('#tel').val(data.tel);
    		$('#verified').val(data.verified);
    		$('#main_admin').val(1);
    		$('#profileAdmin').modal('show');
    	})

    });

	/*change admins profile*/
    $('#admins-list').on("click", ".open-modal", function(){
    	var admin_id = $(this).val();
    	$.get(url + '/' + admin_id, function (data){
    		console.log(data);
    		$('#admin_id').val(data.id);
    		$('#login').val(data.login);
    		$('#name').val(data.name);
    		$('#email').val(data.email);
    		$('#tel').val(data.tel);
    		$('#verified').val(data.verified);
    		$('#profileAdmin').modal('show');
    	})

    });

    $("#btn-save_admin").click(function (e) {

    	$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            }
        })


    	var length = $('#password').val().length;
        if(length==0){

        }
        else{if(length < 6 || length > 30){
        	if(length  < 6){
        		msg_ask.style.display = "none"; answer.style.color = 'red'; answer.textContent = 'Минимум 6 символов';
        	}
        	if(length > 30){
        		msg_ask.style.display = "none"; answer.style.color = 'red'; answer.textContent = 'Максмум 30 символов';
        	}
        	return false;
        }}

    	var admin_id = $('#admin_id').val();
    	var main_admin = $('#main_admin').val();
        var formData = {
            admin_id: $('#admin_id').val(),
            login: $('#login').val(),
            name: $('#name').val(),
            email: $('#email').val(),
            tel: $('#tel').val(),
            password: $('#password').val(),
            verified: $('#verified').val(),
        }

        $.ajax({
            type: "PUT",
            url: url + '/change/' + admin_id,
            data: formData,
            dataType: 'json',
            success: function (data) {
            	console.log(data);

            	if(main_admin==0){
            		var admin = '<tr id="admin' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '">' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
	            	+ data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
	                admin +='<td><div class="btn-group btn-group-sm"><button class="btn btn-default change-status" value="' +  data.id + '"><i class="fas fa-lock-open"></i></button>' +
	                '<button class="btn btn-warning open-modal" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
					'<button class="btn btn-danger delete-admin" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>';
				}else{
					var admin = '<tr id="admin' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '">' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
	            	+ data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
	                admin +='<td><div class="btn-group btn-group-sm"><button class="btn btn-default disabled" value="' +  data.id + '"><i class="fas fa-lock-open"></i></button>' +
	                '<button class="btn btn-warning disabled" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
					'<button class="btn btn-danger disabled" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>';
				}	
				$("#admin" + admin_id).replaceWith( admin );

            	$('#frmTasks').trigger("reset");
            	msg_ask.style.display = "none";
            	answer.textContent = '';
                $('#profileAdmin').modal('hide');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    })

    password.addEventListener('keyup', function(evt){
	  let length = this.value.length
	  if (length < 6){msg_ask.style.display = "inline-block"; answer.style.color = 'red'; answer.textContent = 'Недопустимый';}
	  else if (length == 6) {msg_ask.style.display = "inline-block"; answer.style.color = 'red'; answer.textContent = 'Слабый';}
	  else if (length > 8  && length < 12) {msg_ask.style.display = "inline-block"; answer.style.color = '#e6d112'; answer.textContent = 'Номальный';}
	  else if (length > 15 && length < 31) {msg_ask.style.display = "inline-block"; answer.style.color = 'green'; answer.textContent = 'Сложный';}
	  else if (length > 30){msg_ask.style.display = "inline-block"; answer.style.color = 'red'; answer.textContent = 'Недопустимый';}
	})

    /*delete admin*/
	$('#admins-list').on("click", ".delete-admin", function(e){
        var admin_id = $(this).val();
        $('#Delete_Change').modal('show');
        $('#admin_id').val(admin_id);
        $('#btn_ask').val("delete");
        Delete_Change_ask.textContent = "Вы действительно хотите удалить администратора?";
        btn_ask.textContent = "Удалить";
    });

	/*change admin status*/
	$('#admins-list').on("click", ".change-status", function(e){
        var admin_id = $(this).val();
        $('#Delete_Change').modal('show');
        $('#admin_id').val(admin_id);
        $('#btn_ask').val("change");
        Delete_Change_ask.textContent = "Вы действительно хотите убрать с поста администратора?";
        btn_ask.textContent = "Убрать";
    });

	$('#Delete_Change').on("click", "#btn_ask", function(e){
        var admin_id = $('#admin_id').val();
        var state = $('#btn_ask').val();

        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
	            }
	        })

        if(state == "delete"){
	        $.ajax({
	            type: "DELETE",
	            url: url + '/delete/' + admin_id,

	            success: function (data) {
	            	console.log(data);
	            	$('#Delete_Change').modal('hide');
	                $("#admin" + admin_id).remove();
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
	    }

	    if(state == "change"){
		       var formData = {
	           is_admin: "user",
	        }

	        $.ajax({
	            type: "PUT",
	            url: url + '/' + admin_id,
	            data: formData,
	            dataType: 'json',
	            success: function (data) {
	            	console.log(data);
	            	$('#Delete_Change').modal('hide');

	            	var user = '<tr id="user' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '">' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
	                + data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
	                user +='<td><div class="btn-group btn-group-sm"><button class="btn btn-primary change-status" value="' +  data.id + '"><i class="fas fa-lock"></i></button>' +
	                '<button class="btn btn-warning open-modal" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
	                '<button class="btn btn-danger delete-admin" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>';
	                $('#users-list').append(user);	

	                $("#admin" + admin_id).remove();
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
	    }     
    });
	/*Cancel*/
	$('#Delete_Change').on("click", "#btn_cancel", function(e){
        $('#Delete_Change').modal('hide');
    });


    $(".toolbar_order").on("click", ".toolbar-order_page", function(){
        var toolbar = $(this).attr("toolbar");
        var formData = {
            toolbar: toolbar = $(this).attr("toolbar"),
            toggle: $(this).attr("toggle"),
            carrently_page: $(".choose_page[toolbar='" + toolbar + "']").val(),
            carrently_ammount_page: $(".choose_items[toolbar='" + toolbar + "']").val(),
        }

            if(formData.toggle == "asc"){
                $(this).removeClass("toolbar-btn-allow").addClass("toolbar-btn-active");
                $(".toolbar-order_page-up").removeClass("toolbar-btn-active").addClass("toolbar-btn-allow");
                $(".toolbar").attr('toggle', 'asc');
            }else{
               $(this).removeClass("toolbar-btn-allow").addClass("toolbar-btn-active");
                $(".toolbar-order_page-down").removeClass("toolbar-btn-active").addClass("toolbar-btn-allow");
                $(".toolbar").attr('toggle', 'desc');
            }
            $.ajax({
                type: "GET",
                url: url + '/getToolbar/order',
                data: formData,
                dataType: 'json',
                beforeSend: function(){
                    var preload_gif = "<div class='preload' style='position: relative;padding: 73.5px; top: 0px; width: 100%; height: 100%; z-index: 2147483647; background-image: url(" + url_preloader + "); background-repeat: no-repeat; background-position: center center;'></div>";
                    $('#users-list').empty().append(preload_gif);
                },
                success: function (Formdata) {
                    $(".preload").remove();
                    Formdata.forEach( function(element, index) {
                        var data = element;
                        var user = '<tr id="user' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '">' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
                        + data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
                        user +='<td><div class="btn-group btn-group-sm"><button class="btn btn-primary change-status" value="' +  data.id + '"><i class="fas fa-lock"></i></button>' +
                        '<button class="btn btn-warning open-modal" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
                        '<button class="btn btn-danger delete-admin" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>';
                        $('#users-list').append(user);  
                    });

                    
                },
                error: function (data) {
                    $(".preload").remove();
                    $("html").empty().append(data.responseText);
                    console.log('Error:', data);
                }
            });
    });

    $(".toolbar_manage-page").on("change", ".choose_page", function(){
        var toolbar = $(this).attr("toolbar");
        var formData = {
            toolbar: toolbar = $(this).attr("toolbar"),
            toggle: $(".toolbar").attr("toggle"),
            carrently_page: $(".choose_page[toolbar='" + toolbar + "']").val(),
            carrently_ammount_page: $(".choose_items[toolbar='" + toolbar + "']").val(),
        }

            $.ajax({
                type: "GET",
                url: url + '/getToolbar/order',
                data: formData,
                dataType: 'json',
                beforeSend: function(){
                    var preload_gif = "<div class='preload' style='position: relative;padding: 73.5px; top: 0px; width: 100%; height: 100%; z-index: 2147483647; background-image: url(" + url_preloader + "); background-repeat: no-repeat; background-position: center center;'></div>";
                    $('#users-list').empty().append(preload_gif);
                },
                success: function (Formdata) {
                    $(".preload").remove();
                    Formdata.forEach( function(element, index) {
                        var data = element;
                        var user = '<tr id="user' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '">' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
                        + data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
                        user +='<td><div class="btn-group btn-group-sm"><button class="btn btn-primary change-status" value="' +  data.id + '"><i class="fas fa-lock"></i></button>' +
                        '<button class="btn btn-warning open-modal" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
                        '<button class="btn btn-danger delete-admin" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>';
                        $('#users-list').append(user);  
                    });

                    
                },
                error: function (data) {
                    $(".preload").remove();
                    $("html").empty().append(data.responseText);
                    console.log('Error:', data);
                }
            });
    });

    $(".toolbar_manage_items").on("change", ".choose_items", function(){
        var toolbar = $(this).attr("toolbar");
        var formData = {
            toolbar: toolbar = $(this).attr("toolbar"),
            toggle: $(".toolbar").attr("toggle"),
            carrently_page: $(".choose_page[toolbar='" + toolbar + "']").val(),
            carrently_ammount_page: $(".choose_items[toolbar='" + toolbar + "']").val(),
        }
            var count_users = $("#count_users").val();
            var user_pages = parseInt(count_users / formData.carrently_ammount_page) + 1;

            var select_string = '<option selected value="' + 1 + '">' + 1 + '</option>';
            for(var i=2; i<=user_pages; i++){
                select_string += '<option value="' + i + '">' + i + '</option>';
            }
            $(".choose_page").empty().append(select_string);

            $.ajax({
                type: "GET",
                url: url + '/getToolbar/order',
                data: formData,
                dataType: 'json',
                beforeSend: function(){
                    var preload_gif = "<div class='preload' style='position: relative;padding: 73.5px; top: 0px; width: 100%; height: 100%; z-index: 2147483647; background-image: url(" + url_preloader + "); background-repeat: no-repeat; background-position: center center;'></div>";
                    $('#users-list').empty().append(preload_gif);
                },
                success: function (Formdata) {
                    $(".preload").remove();
                    Formdata.forEach( function(element, index) {
                        var data = element;
                        var user = '<tr id="user' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '">' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
                        + data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
                        user +='<td><div class="btn-group btn-group-sm"><button class="btn btn-primary change-status" value="' +  data.id + '"><i class="fas fa-lock"></i></button>' +
                        '<button class="btn btn-warning open-modal" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
                        '<button class="btn btn-danger delete-admin" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>';
                        $('#users-list').append(user);  
                    });

                    
                },
                error: function (data) {
                    $(".preload").remove();
                    $("html").empty().append(data.responseText);
                    console.log('Error:', data);
                }
            });
    });

    $(".toolbar_searcher_table").on("click", ".searcher_admin_pannel", function(){
        $.ajax({
            type: "GET",
            url: url + '/get/users',
            success: function (data) {
            var array = [];
                if(data != "None"){
                    for(var i=0; i<data.length; i++){
                        array.push(data[i].login);
                    }
                    $(".searcher_admin_pannel").autocomplete({ //на какой input:text назначить результаты списка
                        source: array
                    });
                }else{
                    $(".add_command_input").replaceWith("Команды не найдены");
                    setTimeout(function() { $(".find_input" + index).replaceWith("<i class='fas fa-times-circle not_found not_found" + index + "'></i>") }, 3000);
                }
            },
            error: function (data) {
                $("html").empty().append(data.responseText);
                console.log('Error:', data);
            } 
        });
    });

     $(".toolbar_searcher_table").on("change", ".searcher_admin_pannel", function(){
        var formData = {
            input: $(".searcher_admin_pannel").val(),
        }
            $.ajax({
                type: "GET",
                url: url + '/get/user',
                data: formData,
                dataType: 'json',
                beforeSend: function(){
                    var preload_gif = "<div class='preload' style='position: relative;padding: 73.5px; top: 0px; width: 100%; height: 100%; z-index: 2147483647; background-image: url(" + url_preloader + "); background-repeat: no-repeat; background-position: center center;'></div>";
                    $('#users-list').empty().append(preload_gif);
                },
                success: function (Formdata) {
                    $(".preload").remove();
                    Formdata.forEach( function(element, index) {
                        var data = element;
                        var user = '<tr id="user' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '">' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
                        + data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
                        user +='<td><div class="btn-group btn-group-sm"><button class="btn btn-primary change-status" value="' +  data.id + '"><i class="fas fa-lock"></i></button>' +
                        '<button class="btn btn-warning open-modal" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
                        '<button class="btn btn-danger delete-admin" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>';
                        $('#users-list').append(user);  
                    });

                    
                },
                error: function (data) {
                    $(".preload").remove();
                    $("html").empty().append(data.responseText);
                    console.log('Error:', data);
                }
            });
    
    });

});