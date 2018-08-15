$(document).ready(function(){

	var url = "Type";

	$(".sercher_block").on("click", ".create_type", function(){

		var length_img = $("#type_name").val().length;
        if(length_img < 1){
            type_name.style.border =  "1px solid #ff1212";
            return false;
        }else{
            type_name.style.border =  "1px solid #ccc";
        }

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            }
        })
		var formData = {
            project_name: $('#type_name').val(),
        }

		$.ajax({
            type: "POST",
            url: url + '/add',
            data: formData,
            dataType: 'json',
            success: function (data) {
            	console.log(data);
            	var type_id = data;
            	$.get(url + '/' + type_id, function (data){
		    		console.log(data);
		    		var type = "<tr id='type" + data.id + "'><th scope='row'>" + data.Type_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
                    type += "<button class='btn btn-warning type_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    type += "<button class='btn btn-danger type_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
                    $("#frmType").trigger("reset");
                 	$("#types-list").append(type);
		    	})
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });


	});

	$("#types-list").on("click", ".type_delete", function(){
		var typeID = $(this).val();
		var ask = "<tr id='ask" + typeID + "'><th scope='row' style='color:red;'>Уверены?</th><td class='center'><div class='btn-group btn-group-sm'><button class='btn btn-warning accept' value='" + typeID + "'>Да</button>";
        ask +="<button class='btn btn-danger not_accept' value='" + typeID + "'>Нет</button></div></td></tr>";
		$("#type" + typeID).replaceWith(ask);

			$("#ask" + typeID).on("click", ".not_accept", function(){
				$.get(url + '/' + typeID, function (data){
					var type = "<tr id='type" + data.id + "'><th scope='row'>" + data.Type_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
                    type += "<button class='btn btn-warning type_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    type += "<button class='btn btn-danger type_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#ask" + typeID).replaceWith(type);
		    	})
			});
			$("#ask" + typeID).on("click", ".accept", function(){
				$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
	            }
	        })


	        $.ajax({
	            type: "DELETE",
	            url: url + '/delete/' + typeID,
	            success: function (data) {
	            	console.log(data);
	                $("#ask" + typeID).remove();
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
		});
		

	});

	$("#types-list").on("click", ".type_edit", function(){
		var typeID = $(this).val();
		var counter = 0;
		$.get(url + '/' + typeID, function (data){
			var change = "<tr id='change" + typeID + "'><th scope='row'><input type='text' class='form-control' id='type_special_name" + typeID + "' name='type_special_name' placeholder='Назовите тип' value='" + data.Type_name + "'></th><td class='center'><div class='btn-group btn-group-sm'><button class='btn btn-warning accept_edit" + typeID + "' value='" + typeID + "'>Да</button>";
        	change +="<button class='btn btn-danger not_accept_edit" + typeID + "' value='" + typeID + "'>Нет</button></div></td></tr>";		
			$("#type" + typeID).replaceWith(change);
		})

		$("#types-list").on("click", ".not_accept_edit" + typeID, function(){
			
				$.get(url + '/' + typeID, function (data){
					var type = "<tr id='type" + data.id + "'><th scope='row'>" + data.Type_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
                    type += "<button class='btn btn-warning type_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    type += "<button class='btn btn-danger type_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#change" + typeID).replaceWith(type);
		    	})
			});
		$("#types-list").on("click", ".accept_edit" + typeID, function(){
				typeID = $(this).val();
			
				var length_input = $("#type_special_name" + typeID).val().length; /*Защита от ввода пустой формы*/
		        if(length_input < 1){
		            $("#type_special_name" + typeID).addClass("warning_input");
		            return false;
		        }else{
		             $("#type_special_name" + typeID).removeClass("warning_input");
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
					Type_name: $("#type_special_name" + typeID).val(),	
				};

	        $.ajax({
	            type: "PUT",
	            url: url + '/change/' + typeID,
	            data: dataForm,
	            dataType: 'json',
	            success: function (data) {
	            	console.log(data);
	            	var type = "<tr id='type" + data.type.id + "'><th scope='row'>" + data.type.Type_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
                    type += "<button class='btn btn-warning type_edit' value='" + data.type.id + "'><i class='fas fa-cog'></i></button>";
                    type += "<button class='btn btn-danger type_delete' value='" + data.type.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#change" + typeID).replaceWith(type);
	                
	                var array = data.pub_array.split('/');
   	
	                array.forEach(function(item,i,array){
	                	if(!!item){
	                		document.getElementById("pub_type" + item).textContent= data.type.Type_name;
	                	
	                	}
	                	
	                });
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
		});
	});

});