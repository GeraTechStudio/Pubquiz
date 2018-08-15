$(document).ready(function(){

	var url = "admin/Season";

	$(".sercher_block").on("click", ".create_season", function(){

		var length_name = $("#season_name").val().length;
        if(length_name < 1){
            season_name.style.border =  "1px solid #ff1212";
            return false;
        }else{
            season_name.style.border =  "1px solid #ccc";
        }

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            }
        })
		var formData = {
            season_name: $('#season_name').val(),
        }

		$.ajax({
            type: "POST",
            url: url + '/add',
            data: formData,
            dataType: 'json',
            success: function (data) {
            	console.log(data);
            	var season_id = data;
            	$.get(url + '/' + season_id, function (data){
		    		console.log(data);
		    		var season = "<tr id='season" + data.id + "'><th scope='row'>" + data.Season_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
                    season += "<button class='btn btn-warning season_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    season += "<button class='btn btn-danger season_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
                    $("#frmSeason").trigger("reset");
                 	$("#seasons-list").append(season);
		    	})
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });

		$('#project_name').val(); 

	});

	$("#seasons-list").on("click", ".season_delete", function(){
		var SeasonID = $(this).val();
		var ask = "<tr id='ask" + SeasonID + "'><th scope='row' style='color:red;'>Уверены?</th><td class='center'><div class='btn-group btn-group-sm'><button class='btn btn-warning delete_accept" + SeasonID + "' value='" + SeasonID + "'>Да</button>";
        ask +="<button class='btn btn-danger not_delete_accept" + SeasonID + "' value='" + SeasonID + "'>Нет</button></div></td></tr>";
		$("#season" + SeasonID).replaceWith(ask);
			$("#seasons-list").on("click", ".not_delete_accept" + SeasonID, function(){
				$.get(url + '/' + SeasonID, function (data){
					var season = "<tr id='season" + data.id + "'><th scope='row'>" + data.Season_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
                    season += "<button class='btn btn-warning season_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    season += "<button class='btn btn-danger season_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#ask" + SeasonID).replaceWith(season);
		    	})
			});
			$("#seasons-list").on("click", ".delete_accept" + SeasonID, function(){
				$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
	            }
	        })


	        $.ajax({
	            type: "DELETE",
	            url: url + '/delete/' + SeasonID,
	            success: function (data) {
	            	console.log(data);
	                $("#ask" + SeasonID).remove();
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
		});
		

	});

	$("#seasons-list").on("click", ".season_edit", function(){
		var SeasonID = $(this).val();
		var counter = 0;
		$.get(url + '/' + SeasonID, function (data){
			var ask = "<tr id='ask" + SeasonID + "'><th scope='row'><input type='text' class='form-control' id='season_special_name" + SeasonID + "' name='project_special_name' placeholder='Название сезона' value='" + data.Season_name + "'></th><td class='center'><div class='btn-group btn-group-sm'><button class='btn btn-warning accept_edit" + SeasonID + "' value='" + SeasonID + "'>Да</button>";
        	ask +="<button class='btn btn-danger not_accept_edit" + SeasonID + "' value='" + SeasonID + "'>Нет</button></div></td></tr>";		
			$("#season" + SeasonID).replaceWith(ask);
		})
		
		$("#seasons-list").on("click", ".not_accept_edit" + SeasonID, function(){
				$.get(url + '/' + SeasonID, function (data){
					var season = "<tr id='season" + data.id + "'><th scope='row'>" + data.Season_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
                    season += "<button class='btn btn-warning season_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    season += "<button class='btn btn-danger season_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#ask" + SeasonID).replaceWith(season);
		    	})
			});
			$("#seasons-list").on("click", ".accept_edit" + SeasonID, function(){
				SeasonID = $(this).val();

				var length_input = $("#season_special_name" + SeasonID).val().length; /*Защита от ввода пустой формы*/
		        if(length_input < 1){
		            $("#season_special_name" + SeasonID).addClass("warning_input");
		            return false;
		        }else{
		             $("#season_special_name" + SeasonID).removeClass("warning_input");
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
					Season_name: $("#season_special_name" + SeasonID).val(),	
				};

	        $.ajax({
	            type: "PUT",
	            url: url + '/change/' + SeasonID,
	            data: dataForm,
	            dataType: 'json',
	            success: function (data) {
	            	console.log(data);
					var season = "<tr id='season" + data.id + "'><th scope='row'>" + data.Season_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
                    season += "<button class='btn btn-warning season_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
                    season += "<button class='btn btn-danger season_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
		    		$("#ask" + SeasonID).replaceWith(season);
	                
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
		});
	});

});