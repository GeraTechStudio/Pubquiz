$(document).ready(function(){

	var url = "admin/Project";

	$(".sercher_block").on("click", ".create_project", function(){
		var length_img = $("#project_name").val().length;
        if(length_img < 1){
            project_name.style.border =  "1px solid #ff1212";
            return false;
        }else{
            project_name.style.border =  "1px solid #ccc";
        }

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            }
        })

		var formData = {
            project_name: $('#project_name').val(),
            project_color: $('#projet_color').val()
        }

		$.ajax({
            type: "POST",
            url: url + '/add',
            data: formData,
            dataType: 'json',
            success: function (data) {
            	console.log(data);
            	var project_id = data;
            	$.get(url + '/' + project_id, function (data){
		    		console.log(data);
		    		var project = "<tr id='project" + data.id + "'><th scope='row' class='project_inline' >" + data.Project_name + "<div class='mini_box_color' style='background-color:" + data.Project_color + "'></div></th><td class='center'><div class='btn-group btn-group-sm'>";
                    project += "<button class='btn btn-warning project_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    project += "<button class='btn btn-danger project_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
                    $("#frmProject").trigger("reset");
                 	$("#projects-list").append(project);
		    	})
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });

		$('#project_name').val(); 

	});

	$("#projects-list").on("click", ".project_delete", function(){

		var projectID = $(this).val();
		var ask = "<tr id='ask" + projectID + "'><th scope='row' style='color:red;'>Уверены?</th><td class='center'><div class='btn-group btn-group-sm'><button class='btn btn-warning delete_accept" + projectID + "' value='" + projectID + "'>Да</button>";
        ask +="<button class='btn btn-danger not_delete_accept" + projectID + "' value='" + projectID + "'>Нет</button></div></td></tr>";
		$("#project" + projectID).replaceWith(ask);

			$("#projects-list").on("click", ".not_delete_accept" + projectID, function(){
				$.get(url + '/' + projectID, function (data){
					var project = "<tr id='project" + data.id + "'><th scope='row' class='project_inline' >" + data.Project_name + "<div class='mini_box_color' style='background-color:" + data.Project_color + "'></div></th><td class='center'><div class='btn-group btn-group-sm'>";
                    project += "<button class='btn btn-warning project_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    project += "<button class='btn btn-danger project_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#ask" + projectID).replaceWith(project);
		    	})
			});
			$("#projects-list").on("click", ".delete_accept" + projectID, function(){
				$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
	            }
	        })


	        $.ajax({
	            type: "DELETE",
	            url: url + '/delete/' + projectID,
	            success: function (data) {
	            	console.log(data);
	                $("#ask" + projectID).remove();
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
		});
		

	});

	$("#projects-list").on("click", ".project_edit", function(){
		var projectID = $(this).val();
		var counter = 0;
		$.get(url + '/' + projectID, function (data){
			var ask = "<tr id='ask" + projectID + "'><th scope='row' style='display:flex;'><input type='text' class='form-control' id='project_special_name" + projectID + "' name='project_special_name' placeholder='Название проекта' value='" + data.Project_name + "' style='border-radius: 4px 0 0 4px;border-right: none;'><input type='color'  id='projet_color' value='" + data.Project_color + "' name='projet_color'></th><td class='center'><div class='btn-group btn-group-sm'><button class='btn btn-warning accept_edit" + projectID + "' value='" + projectID + "'>Да</button>";
        	ask +="<button class='btn btn-danger not_accept_edit" + projectID + "' value='" + projectID + "'>Нет</button></div></td></tr>";		
			$("#project" + projectID).replaceWith(ask);
		})
		
		$("#projects-list").on("click", ".not_accept_edit" + projectID, function(){
				$.get(url + '/' + projectID, function (data){
					var project = "<tr id='project" + data.id + "'><th scope='row' class='project_inline'>" + data.Project_name + "<div class='mini_box_color' style='background-color:" + data.Project_color + "'></div></th><td class='center'><div class='btn-group btn-group-sm'>";
                    project += "<button class='btn btn-warning project_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    project += "<button class='btn btn-danger project_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#ask" + projectID).replaceWith(project);
		    	})
			});
			$("#projects-list").on("click", ".accept_edit" + projectID, function(){
				projectID = $(this).val();

				var length_input = $("#project_special_name" + projectID).val().length; /*Защита от ввода пустой формы*/
		        if(length_input < 1){
		            $("#project_special_name" + projectID).addClass("warning_input");
		            return false;
		        }else{
		             $("#project_special_name" + projectID).removeClass("warning_input");
		        }


				counter += 1; /*Защита от двоение запроса! При повторной попытке отправить форму, данные сворачиваются!*/
				if(counter >= 2){
					return false;
				}

				$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
	            }
	        })	

				var dataForm = {
					Project_name: $("#project_special_name" + projectID).val(),
					Project_color: $('#projet_color').val()	
				};

	        $.ajax({
	            type: "PUT",
	            url: url + '/change/' + projectID,
	            data: dataForm,
	            dataType: 'json',
	            success: function (data) {
	            	console.log(data);
	            	var project = "<tr id='project" + data.id + "'><th scope='row' class='project_inline' >" + data.Project_name + "<div class='mini_box_color' style='background-color:" + data.Project_color + "'></div></th><td class='center'><div class='btn-group btn-group-sm'>";
                    project += "<button class='btn btn-warning project_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    project += "<button class='btn btn-danger project_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#ask" + projectID).replaceWith(project);
	                
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
		});
	});

});