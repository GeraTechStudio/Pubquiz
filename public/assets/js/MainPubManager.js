$(document).ready(function(){
	var url = '/admin/Location/Manipulate';

	$(".pubs_box").on("click",".edit-pub", function(){

		if($("#buffer").val() != "empty"){
            var dataForm = {pub_img: $("#buffer").val(),};
             $.ajax({
                type: "DELETE",
                url: "/admin/Location/Manager/delete_img",
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
                url: "/admin/Location/Manager/delete_img",
                data: dataForm,
                dataType: 'json',            
            });
        }


		var PubID = $(this).val();
		var selects = "";
		Location_name.style.border =  "1px solid #ccc"; Location_name.placeholder = "Назовите Локацию";
		Location_address.style.border =  "1px solid #ccc"; Location_address.placeholder = "Адрес Локации";
		Location_map.style.border =  "1px solid #ccc"; Location_map.placeholder = "Ссылка на карту Google";
		empty_zone.style.border =  "none"; empty_zone.style.background =  "#6da047";
		Location_description.style.border =  "1px solid #ccc"; Location_description.placeholder = "Описание Паба";

		$.get(url + '/' + PubID, function(data){
			$("#Location_name").val(data.Location_name); /*Upload data*/
			$("#Location_address").val(data.Location_address); /*Upload data*/
			$("#Location_map").val(data.Location_map); /*Upload data*/
			$("#Location_description").val(data.Location_description); /*Upload data*/
			$("#Pub_id").val(PubID);
			$("#pub_img").val(data.img_name);
			$("#Location_type").empty();
			$("#pub_color").val(data.color);
			$("#pub_img_path").val(data.Location_img);
			var type_of_pub = data.Location_type;
			var name = data.Location_name;
				
			$.get(url + '/type_of_pub', function(data){
				var type_delete = 0;
	            selects = "<option value='" + type_of_pub + "' selected >" + type_of_pub + "</option>";
	            data.forEach(function(item,i,data){
	            	if(type_of_pub != item.Type_name){
	            		selects += "<option value='" + item.Type_name + "'>" + item.Type_name + "</option>";
	            	}
	            	else{
	            		type_delete++;
	            	}
	            });
	            if(type_delete <= 0){
	            	var recreateType = confirm("Видимо Вы удалили тип локации, который принадлежал " + name + " ! Желаете ли вы воссоздать тип локации (это позволит вновь использовать его для поиск по фильтру)? ");
	            	if(recreateType){
	            		$.ajaxSetup({
				            headers: {
				                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
				            }
				        })
	            		var formData = {
					            project_name: type_of_pub,
					        }

	            		$.ajax({
				            type: "POST",
				            url: '/admin/Location/Type/add',
				            data: formData,
				            dataType: 'json',
				            success: function (data) {
				            	console.log(data);
				            	var type_id = data;
				            	$.get('/admin/Location/Type/' + type_id, function (data){
						    		console.log(data);
						    		alert(data.Type_name);
						    		var type = "<tr id='type" + data.id + "'><th scope='row'>" + data.Type_name + "</th><td class='center'><div class='btn-group btn-group-sm'>";
				                    type += "<button class='btn btn-warning type_edit' value='" + data.id + "'><i class='fas fa-cog'></i></button>";
				                    type += "<button class='btn btn-danger type_delete' value='" + data.id + "'><i class='fas fa-trash-alt'></i></button></div></td></tr>";
				                 	$("#types-list").append(type);
						    	})
				            },
				            error: function (data) {
				                console.log('Error:', data);
				            }
				        });
	            	}
					
	            }
	            
	            $('#Location_type').append(selects); /*Upload data*/              
        	});

        	var img = "<div class='img_box img_box-true' style='background-image: url(" + data.Location_img +"); background-position: center center;'> </div>"
			$(".img_box").replaceWith(img);

			PubManagerCreateLabel.textContent = "Редактировать Локацию";

			$("#btn_create_pub").val("change");
			document.getElementById("btn_create_pub").innerHTML = "Изменить";
			$('#PubManagerCreate').modal('show');
		});
	});


	$(".modal-footer").on('click', '#btn_create_pub', function(){

		if($(this).val() == "change"){

			$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                }
            })
			var PubID = $("#Pub_id").val();

			var dataForm = {
				Location_name: $("#Location_name").val(),
                Location_address: $("#Location_address").val(),
                map: $("#Location_map").val(),
                Location_img: $("#pub_img_path").val(),
                img_name: $("#pub_img").val(),
                buffer: $("#buffer").val(),
                Location_type: $("#Location_type").val(),
                Location_description: $("#Location_description").val(),
                color: $("#pub_color").val(),	
			};
			console.log(dataForm);
            $.ajax({
                type: "PUT",
                url: url + '/change/' + PubID,
                data: dataForm,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    $("#buffer").val("empty");
                    $("#pub_img").val(data.img_name);
                    $("#pub_img_path").val(data.Location_img);
                    $("#Pub_id").val(data.id);
                    $(".pub" + data.id).empty();
                    var Pub = "<div class='pub_box_background' style='background-image: url(" + data.Location_img + ");'>";
                    Pub +="<div class='full_screen'><button class='show_pub' value='" + data.id + "'><i class='fas fa-eye'></i></button></div><div class='pub_box_manager_panel'><h2>" + data.Location_name +"</h2>";
                    Pub +="<div class='manager_button'><button class='btn btn-warning edit-pub' value='" + data.id + "'>Редактировать<i class='fas fa-cog'></i></button>"
                    Pub +="<button class='btn btn-danger delete-pub' value='" + data.id + "'>Удалить <i class='fas fa-trash-alt'></i></button></div></div><div class='pub_box_manager_type'><h2 id='pub_type" + data.id + "'>" + data.Location_type + "</h2></div></div>";
                    $(".pub" + data.id).append(Pub);
                    $('#PubManagerCreate').modal('hide');

                },
                error: function (data) {
                    console.log('Error:', data);
                    $("html").empty().append(data.responseText);
                } 
            
            });
		}

	});

	$(".pubs_box").on("click",".delete-pub", function(){
		var PubID = $(this).val();
		$("#Pub_delete_id").val(PubID);
		$('#Delete_pub').modal('show');
		
		
	});
	
	$("#Delete_pub").on("click","#btn_delete", function(){
		var PubID = $("#Pub_delete_id").val();
		$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                }
            })
          $.ajax({
             type: "DELETE",
             url: url + "/delete_pub/" + PubID,
             success: function (data) {
                    console.log(data);
                    var block = $(".pub"+ PubID).parent(".col-md-4");
                    block.remove();
                    if(!$("div").is(".pub_box")){
                    	var warning = "<h2 id='warning'>Вы еще не создали Локацию</h2>";
                    	$("#add_pubs").append(warning);
                    }
                    $('#Delete_pub').modal('hide');	
                },
                error: function (data) {
                    console.log('Error:', data);
                }             
         });
		
	});


	$("#Delete_pub").on("click","#btn_cancel", function(){
		$('#Delete_pub').modal('hide');
		
	});
	

	$("#add_pubs").on("click",".show_pub", function(){
		var PubID = $(this).val();
		var count = 0;
		$.get("/admin/Location/Manager/get_pub/" + PubID, function(data){
			console.log(data);
			PubName.textContent = data.Location_name;
			PubMap.textContent = "Местоположение";
			var img = "<div class='pub_img_box' style='background-image: url(" + data.Location_img +"); background-position: center center;'> </div>"
            $(".pub_img_box").replaceWith(img);
            document.getElementById("pub_info_content_type").innerHTML = data.Location_type + " <span id='Pub_span_name'></span>"
            document.getElementById("Pub_span_name").innerHTML = data.Location_name;
            pub_info_content_address.textContent = data.Location_address;
            pub_desc.textContent = data.Location_description;
            document.getElementById("map_url").innerHTML = data.Location_map;
            $('#ShowPub').modal('show');
		});		
		
	});


	$(function() {
		var array = [];
		$.get('/admin/Location/Manipulate/get/all_elements', function(data){
			var allow = true;
			 for(var i=0; i<data.Location.length; i++){
			 	var id = true;
			 	var name = true;
			 	var address = true;
			 	var type = true;
			 	for(var j=0; j<array.length; j++){
			 		console.log(array[j] != data.Location[i].id);
			 		if(array[j] == data.Location[i].id){
			 			id = false;
			 		}
			 		if(array[j] == data.Location[i].Location_name){
			 			name = false;
			 		}
			 		if(array[j] == data.Location[i].Location_address){
			 			address = false;
			 		}
			 		if(array[j] == data.Location[i].Location_type){
			 			type = false;
			 		}
			 	}
			 	if(allow == true){
				 	array.push(data.Location[i].id);
				 	array.push(data.Location[i].Location_name);
				 	array.push(data.Location[i].Location_address);
				 	array.push(data.Location[i].Location_type);
				 	allow = false;
				}else{
					if(id == true){
			 			array.push(data.Location[i].id);
			 		}
			 		if(name == true){
			 			array.push(data.Location[i].Location_name);
			 		}
			 		if(address == true){
			 			array.push(data.Location[i].Location_address);
			 		}
			 		if(type == true){
			 			array.push(data.Location[i].Location_type);
			 		}
			 	}
			 }
			 console.log(array);
			$("#searcher").autocomplete({ //на какой input:text назначить результаты списка
				source: array
			});
		
		});
	});

	$(".sercher_block").on("click", ".search", function(){
		var search_name = $("#searcher").val();
		var search_array =[];
			$.get('/admin/Location/Manipulate/get/all_elements', function(data){
				for(var i=0; i<data.Location.length; i++){
				if(data.Location[i].id == search_name || data.Location[i].Location_address == search_name || data.Location[i].Location_name == search_name || data.Location[i].Location_type == search_name){
					search_array.push(data.Location[i].id);
				}	
			} 

			$.ajaxSetup({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
	                }
	          })
				var FormData = {
					array: search_array,
				}
	          $.ajax({
	            type: "GET",
	            url: '/admin/Location/Manipulate/get/elements',
	            data: FormData,
				dataType: 'json',
	             success: function (data) {
	             	$("#add_pubs").empty();
	                var to = data.i *2;   
	                    for(var i=0; i<to; i++){
	                    	if(i%2!=0){
	                    		var Pub = "<div class='col-md-4'><div class='pub_box pub" + data.stack[i].id + "'><div class='pub_box_background' style='background-image: url(" + data.stack[i].Location_img + ");'>";
	                            Pub +="<div class='full_screen'><button class='show_pub' value='" + data.stack[i].id + "'><i class='fas fa-eye'></i></button></div><div class='pub_box_manager_panel'><h2>" + data.stack[i].Location_name +"</h2>";
	                            Pub +="<div class='manager_button'><button class='btn btn-warning edit-pub' value='" + data.id + "'>Редактировать<i class='fas fa-cog'></i></button>"
	                            Pub +="<button class='btn btn-danger delete-pub' value='" + data.stack[i].id + "'>Удалить <i class='fas fa-trash-alt'></i></button></div></div><div class='pub_box_manager_type'><h2 id='pub_type" + data.stack[i].id + "'>" + data.stack[i].Location_type + "</h2></div></div></div></div>";
                            	$("#add_pubs").append(Pub);
	                    	}
							
						}
						if(data.i == 0){
							var warning = "<h2 id='warning'>Поиск ничего не нашел!</h2>";
                    		$("#add_pubs").append(warning);	
						} 

	                },
	                error: function (data) {
	                    console.log('Error:', data);
	                }             
	         });	
		

		
		});	 
	});

		
	$(".welcome_location").on("click", ".update_pubs", function(){
		$.get('/admin/Location/Manipulate/get/getAllPubs', function(data){
			$("#add_pubs").empty();
			var count = 0;
		 	for(var i=0; i<data.length; i++){
				var Pub = "<div class='col-md-4'><div class='pub_box pub" + data[i].id + "'><div class='pub_box_background' style='background-image: url(" + data[i].Location_img + ");'>";
				Pub +="<div class='full_screen'><button class='show_pub' value='" + data[i].id + "'><i class='fas fa-eye'></i></button></div><div class='pub_box_manager_panel'><h2>" + data[i].Location_name +"</h2>";
				Pub +="<div class='manager_button'><button class='btn btn-warning edit-pub' value='" + data[i].id + "'>Редактировать<i class='fas fa-cog'></i></button>"
				Pub +="<button class='btn btn-danger delete-pub' value='" + data[i].id + "'>Удалить <i class='fas fa-trash-alt'></i></button></div></div><div class='pub_box_manager_type'><h2 id='pub_type" + data[i].id + "'>" + data[i].Location_type + "</h2></div></div></div></div>";
				$("#add_pubs").append(Pub);
				count++;
			}
			if(count==0){
				var warning = "<h2 id='warning'>Вы еще не создали Локацию</h2>";
                $("#add_pubs").append(warning);
			} 
		});
	});
});

	


		