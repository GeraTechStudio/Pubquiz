$(document).ready(function() {
    var url = "/admin/game/creation";
    var game_id = $("#id_game").val();
    var game_stage = $("#game_stage").val();
    var game_name = "";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
    	}
    })
    

        if(game_stage == "0"){
            $(".redact").addClass('active');
            var edit_game_content = "<div class='container-fluid'><div class='row'><div class='col-lg-offset-1 col-md-offset-1 col-lg-7 col-md-7'><div class='game_creation_content'>";
            edit_game_content += "<div class='col-sm-5'><form id='game_labels' name='game_labels' class='form-horizontal' novalidate=''><div class='form-group error'><input type='text' class='form-control has-error' id='Game_name' name='Game_name' placeholder='Название Игры' value=''>";
            edit_game_content += "</div><div class='form-group error game_time_zone' style='display: flex'><input type='date' class='form-control has-error' id='game_date'><input type='time' class='form-control has-error' id='game_time' ></div></form><div class='game_img_content'><div class='game_img_box'><div class='size'>[360x400]</div>";
            edit_game_content += "</div><form enctype='multipart/form-data' id='PubManager_img'  name='PubManager_img'><div class='file-upload blue_upload' id='empty_zone'><label id='buf'><input type='file' id='logo_game' name='imgGame' accept='image/*'><span>Выберите файл [360x400]</span></label></div></form></div></div>"
            edit_game_content += "<div class='col-sm-1'></div><div class='col-sm-5'><form id='game_description' name='game_labels' class='form-horizontal' novalidate=''><div class='form-group error'><select class='form-control has-error' id='Game_project' name='Game_project'></select></div><div class='form-group error'><select class='form-control has-error' id='Game_season' name='Game_season'></select></div>";
            edit_game_content += "<div class='form-group error'><textarea class='form-control has-error game_description' placeholder='Описание Игры' id='game_description' value=''></textarea></div></form></div></div></div><div class='col-lg-3 col-md-3'><form id='Game_pubs' name='Game_pubs' class='form-horizontal' novalidate=''><div class='form-group error'><input type='number' class='form-control has-error' id='game_rounds' title='Кол-во Раундов' value='0' min='0'></div><div class='form-group error'><select class='form-control has-error' title='Зачетная Игра?' id='play_type'><option value='0' selected>Зачетная Игра</option><option value='1' selected>Незачетная Игра</option></select></div><div class='form-group error'><select class='form-control has-error' id='All_Pubs' name='All_Pubs'><option value='empty' selected>Выберите Локацию</option>";
            edit_game_content += "</select></div></form><div id='add_pubs'></div></div></div></div>";
            $(".game").empty().append(edit_game_content);

            
                     
        }else{
            if(game_stage == "1"){
                $(".ready").addClass('active');
                var edit_game_content = "<div class='container-fluid'><div class='row'><div class='col-lg-offset-1 col-md-offset-1 col-lg-7 col-md-7'><div class='game_creation_content'>";
                edit_game_content += "<div class='col-sm-5'><form id='game_labels' name='game_labels' class='form-horizontal' novalidate=''><div class='form-group error'><input type='text' class='form-control has-error' id='Game_name' name='Game_name' placeholder='Название Игры' value='' disabled>";
                edit_game_content += "</div><div class='form-group error game_time_zone' style='display: flex'><input type='date' class='form-control has-error' id='game_date' disabled><input type='time' class='form-control has-error' id='game_time' disabled></div></form><div class='game_img_content'><div class='game_img_box'><div class='size'>[360x400]</div>";
                edit_game_content += "</div><form enctype='multipart/form-data' id='PubManager_img'  name='PubManager_img'><div class='file-upload blue_upload' id='empty_zone'><label id='buf' style='cursor: not-allowed'><input type='file' id='logo_game' name='imgGame' accept='image/*' disabled><span>Выберите файл [360x400]</span></label></div></form></div></div>"
                edit_game_content += "<div class='col-sm-1'></div><div class='col-sm-5'><form id='game_description' name='game_labels' class='form-horizontal' novalidate=''><div class='form-group error'><select class='form-control has-error' id='Game_project' name='Game_project' disabled></select></div><div class='form-group error'><select class='form-control has-error' id='Game_season' name='Game_season' disabled></select></div>";
                edit_game_content += "<div class='form-group error'><textarea class='form-control has-error game_description' placeholder='Описание Игры' id='game_description' value='' disabled></textarea></div></form></div></div></div><div class='col-lg-3 col-md-3'><form id='Game_pubs' name='Game_pubs' class='form-horizontal' novalidate=''><div class='form-group error'><input type='number' class='form-control has-error' id='game_rounds' title='Кол-во Раундов' value='0' min='0' disabled></div><div class='form-group error'><select class='form-control has-error' title='Зачетная Игра?' id='play_type' disabled><option value='0'>Зачетная Игра</option><option value='1' selected>Незачетная Игра</option></select></div><div class='form-group error' ><select class='form-control has-error' id='All_Pubs' name='All_Pubs' disabled><option value='empty' selected>Выберите Локацию</option>";
                edit_game_content += "</select></div></form><div id='add_pubs'></div></div></div></div>";
                $(".game").empty().append(edit_game_content);
            }else{
                $(".finish").addClass('active');

                
            }
        }

                $(".game_stages").on('click', '.game_stage',function(){
                    var formData = {
                        game_stage: $(this).val(),
                    }
                    var specialUrl = url + '/update/game_stage/' + game_id;
                    updateData(specialUrl, formData);
                    document.location.href = url + "/" + game_id;
                }); 

                $(".game_stages").on('click', '.delete_game',function(){
                    $("#DeleteGame").modal('show');
                    $("#DeleteTitle").append(game_name);


                    $(".modal-footer").on('click', '#cancel',function(){
                        $("#DeleteGame").modal('hide');
                    });

                    $(".modal-footer").on('click', '#delete',function(){
                        var specialUrl = url + '/deleteGame/' + game_id;
                        $.ajax({
                           type: "DELETE",
                           url: specialUrl,
                           success: function (data) {
                            document.location.href = data; 
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            } 

                        });
                    });
                });

            /*Creation*/

                $.get(url + '/get_buff/' + game_id, function(data){
                    console.log('!!!',data);
                    $("#game_rounds").val(data.game.game_rounds);
                    if(data.game.game_order == 0){
                        var order = "<option value='0' selected >" + "Зачетная Игра" + "</option><option value='1' >" + "Незачетная Игра" + "</option>";
                    }else{
                        var order = "<option value='0' >" + "Зачетная Игра" + "</option><option value='1' selected>" + "Незачетная Игра" + "</option>";
                    }
                    $('#play_type').empty().append(order);

                    if(data.game.game_name != "None"){
                        $("#Game_name").val(data.game.game_name);
                        game_name = data.game.game_name;
                    }
                    if(data.game.game_date != "None"){
                        $("#game_date").val(data.game.game_date);
                    }
                    if(data.game.game_time != "None"){
                        $("#game_time").val(data.game.game_time);
                    }
                    if(data.game.game_img_path != "None"){
                        var img = "<div class='game_img_box img_box-true' style='background-image: url(" + data.game.game_img_path +"); background-position: center center;'> </div>"
                        $(".game_img_box").replaceWith(img);
                    }

                    if(data.project == "None"){
                        var recreateProject = confirm("Видимо Вы удалили Проект, который принадлежал игре " + data.game.game_name + " ! Желаете ли вы воссоздать Проект (это позволит вновь использовать его для индексации)? ");
                        if(recreateProject){
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                                }
                            })
                            var formData = {
                                id_project: data.game.id_project,
                                project_name: data.game.project_name,
                                project_color: data.game.project_color,
                            }
                                    $.ajax({
                                        type: "POST",
                                        url: url + '/recreate/project',
                                        data: formData,
                                        dataType: 'json',
                                        success: function (data) {
                                        },
                                        error: function (data) {
                                            console.log('Error:', data);
                                        }
                                    });
                                }
                            var project_select = "<option value='" + data.game.id_project + "' selected >" + data.game.project_name + "</option>";
                            for(var i=0; i<data.projects.length; i++){
                                project_select += "<option value='" + data.projects[i].id + "'>" + data.projects[i].Project_name + "</option>";
                            }
                            $('#Game_project').append(project_select);      
                    }
                    else{
                        var project_select = "<option value='" + data.game.id_project + "' selected >" + data.game.project_name + "</option>";
                        for(var i=0; i<data.projects.length; i++){
                            project_select += "<option value='" + data.projects[i].id + "'>" + data.projects[i].Project_name + "</option>";
                        }
                        $('#Game_project').append(project_select);
                    }

                    if(data.season == "None"){
                        var recreateSeason = confirm("Видимо Вы удалили Сезон, который принадлежал игре " + data.game.game_name + " ! Желаете ли вы воссоздать Сезон (это позволит вновь использовать его для индексации)? ");
                        if(recreateSeason){
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                                }
                            })
                            var formData = {
                                id_season: data.game.id_season,
                                season_name: data.game.season_name,
                            }
                                    $.ajax({
                                        type: "POST",
                                        url: url + '/recreate/season',
                                        data: formData,
                                        dataType: 'json',
                                        success: function (data) {
                                        },
                                        error: function (data) {
                                            console.log('Error:', data);
                                        }
                                    });
                                }
                                var season_select = "<option value='" + data.game.id_season + "' selected >" + data.game.season_name + "</option>";
                                for(var i=0; i<data.seasons.length; i++){
                                    season_select += "<option value='" + data.seasons[i].id + "'>" + data.seasons[i].Season_name + "</option>";
                                }
                                $('#Game_season').append(season_select);                    
                    }
                    else{
                        var season_select = "<option value='" + data.game.id_season + "' selected >" + data.game.season_name + "</option>";
                        for(var i=0; i<data.seasons.length; i++){
                            season_select += "<option value='" + data.seasons[i].id + "'>" + data.seasons[i].Season_name + "</option>";
                        }
                        $('#Game_season').append(season_select);
                    }

                    if(data.game.game_desc != "None"){
                        $(".game_description").val(data.game.game_desc);
                    }

                    if(data.game.pubs != "None"){
                        var specialUrl = url + '/get/pubCheck/' + game_id;
                        pubCheck(specialUrl);
                    }
                    else{
                        var location_select = "";
                        for(var i=0; i<data.locations.length; i++){
                            location_select += "<option value='" + data.locations[i].id + "'>" + data.locations[i].Location_name + "</option>";
                        }
                        $("#All_Pubs").append(location_select);
                        var Pub = "<div class='pub_offset'></div>";
                        $("#add_pubs").append(Pub);
                    }
                
                });


                $(document).ready( function() {
                    $("#logo_game").change(function(){
                        var imgGame = new FormData();
                        var id_game = $("#id_game").val();
                        imgGame.append("imgGame", this.files[0]);
                        imgGame.append("id_game", id_game);
                            $.ajax({
                                type: "POST",
                                url: url + '/upload',
                                data: imgGame,
                                dataType: 'json',
                                processData: false,
                                contentType: false,
                                success: function (data) {
                                    console.log(data);
                                    var img = "<div class='game_img_box img_box-true' style='background-image: url(" + data.img_path +"); background-position: center center;'> </div>"
                                        $(".game_img_box").replaceWith(img);
                                    
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                } 
                            
                            });


                    

                    });
                });



                $("#Game_name").change(function(){
                    var formData = {
                        Game_name: $("#Game_name").val(),
                    }
                    var specialUrl = url + '/update/game_name/' + game_id;
                    updateData(specialUrl, formData);

                });

                $("#game_date").change(function(){
                    var formData = {
                        Game_date: $("#game_date").val(),
                    }
                    var specialUrl = url + '/update/game_date/' + game_id;
                    updateData(specialUrl, formData);

                });

                $("#game_time").change(function(){
                    var formData = {
                        Game_time: $("#game_time").val(),
                    }
                    var specialUrl = url + '/update/game_time/' + game_id;
                    updateData(specialUrl, formData);

                });

                $("#Game_project").change(function(){
                    var formData = {
                        Game_Project: $("#Game_project").val(),
                    }
                    var specialUrl = url + '/update/game_project/' + game_id;
                    updateData(specialUrl, formData);

                });

                $("#Game_season").change(function(){
                    var formData = {
                        Game_Season: $("#Game_season").val(),
                    }
                    var specialUrl = url + '/update/game_season/' + game_id;
                    updateData(specialUrl, formData);

                });

                $(".game_description").change(function(){
                    var formData = {
                        Game_Desc: $(".game_description").val(),
                    }
                    var specialUrl = url + '/update/game_desc/' + game_id;
                    updateData(specialUrl, formData);

                });

                $("#game_rounds").change(function(){
                    var formData = {
                        Game_rounds: $("#game_rounds").val(),
                    }
                    var specialUrl = url + '/update/game_rounds/' + game_id;
                    updateData(specialUrl, formData);

                });

                $("#play_type").change(function(){
                    var formData = {
                        Game_order: $("#play_type").val(),
                    }
                    var specialUrl = url + '/update/game_order/' + game_id;
                    updateData(specialUrl, formData);

                });
                    
                $("#All_Pubs").change(function(){
                    var Pub_id = $(this).val();
                    $("#All_Pubs option[value='" + Pub_id + "']").remove();

                    var formData = {
                        Game_Pubs: Pub_id,
                    }
                    var specialUrl = url + '/update/game_pubs/' + game_id;
                    updateData(specialUrl, formData);

                    $.get(url + '/get/location/' + Pub_id, function(data){
                        console.log(data);
                        var Pub = "<div class='col-sm-6'><div class='pub_box min_height pub" + data.id + "'><button class='delete_pub' value='"+ data.id +"'>x</button><div class='pub_box_background' style='background-image: url(" + data.Location_img + ");'>";
                        Pub +="<div class='full_screen'><button class='show_pub' value='" + data.id + "'><i class='fas fa-eye'></i></button></div><div class='pub_box_manager_panel'><h2>" + data.Location_name +"</h2>";
                        Pub +="</div><div class='pub_box_manager_type'><h2 id='pub_type" + data.id + "'>" + data.Location_type + "</h2></div></div></div><p class='table_input'><i>Количество Столиков:</i></p><div class='table_inputs'><input type='number' class='form-control has-error table_input table_input" + data.id + "' value='0' min='0'><button class='accept_input' value='" + data.id + "'>Ok</button></div>";
                        $(".pub_offset").append(Pub);
                    });

                });

                $("#add_pubs").on('click', '.accept_input', function(){
                    var formData = {
                        pub_id: $(this).val(),
                        tables_amount: $(".table_input" + $(this).val()).val(),
                    }

                    $.ajax({
                           type: "PUT",
                           url: url + '/change/reserve_tables/' + game_id,
                           data: formData,
                           dataType: 'json',
                           success: function (data) {
                               if(data == "Allow"){
                                   var specialUrl = url + '/update/game_pub/tables/' + game_id;
                                    $.ajax({
                                           type: "PUT",
                                           url: specialUrl,
                                           data: formData,
                                           dataType: 'json',
                                           success: function (data) {
                                           console.log(data);
                                           },
                                           error: function (data) {
                                                console.log('Error:', data);
                                           } 
                                    }); 
                               }else{
                                $(".table_input" + formData.pub_id).val(data[1])
                                alert("Невозможно уменьшить количество столов! Количество команд - превишают количество столиков!");
                               }
                           },
                           error: function (data) {
                                console.log('Error:', data);
                           } 

                    });
                        

                });


                $("#add_pubs").on('click', '.delete_pub',function(){
                    var delete_pub = $(this).val();
                    var specialUrl = url + '/delete/game_pub/' + game_id;
                    var formData = {
                        pub_id: delete_pub,
                    }
                    $.ajax({
                           type: "DELETE",
                           url: specialUrl,
                           data: formData,
                           dataType: 'json',
                           success: function (data) {
                           console.log(data);
                            $(".pub" + delete_pub).parent().remove();
                            if(data.pub != "None"){
                                var location_select = "<option value='" + data.pub.id + "'>" + data.pub.Location_name + "</option>";
                                $("#All_Pubs").append(location_select);
                            }
                           },
                           error: function (data) {
                                console.log('Error:', data);
                           } 

                        });
                });
                function pubCheck(specialUrl){

                        $.ajax({
                           type: "GET",
                           url: specialUrl,
                           success: function (data) {
                            console.log(data);
                           var location_select = "";
                           for(var i=0; i<data.array.length; i++){
                                location_select += "<option value='" + data.locations[data.array[i]].id + "'>" + data.locations[data.array[i]].Location_name + "</option>";
                            } 
                            $("#All_Pubs").append(location_select);
                            /*Вывод Пабов*/
                            var getPubs = url + '/get/pubs/' + game_id; 
                            pubShow(getPubs);

                            },
                            error: function (data) {
                                console.log('Error:', data);
                                pubCheck(specialUrl);
                            } 

                        });
                    }
                    function pubShow(getPubs){
                        $.ajax({
                           type: "GET",
                           url: getPubs,
                           success: function (data) {
                            console.log(data);
                            var Pub = "<div class='pub_offset'>";
                            for(var i=0; i<data.index.length; i++){
                                var index = data.index[i];
                                var location = data.locations[index];
                                if(game_stage == "0"){
                                    Pub += "<div class='col-sm-6'><div class='pub_box min_height pub" + location.id + "'><button class='delete_pub' value='"+ location.id +"'>x</button><div class='pub_box_background' style='background-image: url(" + location.Location_img + ");'>";
                                    Pub +="<div class='full_screen'><button class='show_pub' value='" + location.id + "'><i class='fas fa-eye'></i></button></div><div class='pub_box_manager_panel'><h2>" + location.Location_name +"</h2>";
                                    Pub +="</div><div class='pub_box_manager_type'><h2 id='pub_type" + location.id + "'>" + location.Location_type + "</h2></div></div></div><p class='table_input'><i>Количество Столиков:</i></p><div class='table_inputs'><input type='number' class='form-control has-error table_input table_input" + location.id + "' value='" + data.tables[index] + "' min='0'><button class='accept_input' value='" + location.id + "'>Ok</button></div></div>";  
                                }
                                else{
                                    Pub += "<div class='col-sm-6'><div class='pub_box min_height pub" + location.id + "'><button class='delete_pub' style='cursor:not-allowed' >x</button><div class='pub_box_background' style='background-image: url(" + location.Location_img + ");'>";
                                    Pub +="<div class='full_screen'><button class='show_pub' value='" + location.id + "'><i class='fas fa-eye'></i></button></div><div class='pub_box_manager_panel'><h2>" + location.Location_name +"</h2>";
                                    Pub +="</div><div class='pub_box_manager_type'><h2 id='pub_type" + location.id + "'>" + location.Location_type + "</h2></div></div></div><p class='table_input'><i>Количество Столиков:</i></p><div class='table_inputs'><input type='number' class='form-control has-error table_input table_input" + location.id + "' value='" + data.tables[index] + "' min='0' disabled><button class='accept_input' style='cursor:not-allowed'>Ok</button></div></div>";  
                                }
                            }   
                            Pub += "</div>";
                            $("#add_pubs").append(Pub);
                            },
                            error: function (data) {
                                pubShow(getPubs);
                                console.log('Error:', data);
                            } 

                        });
                    }
                    function updateData(specialUrl, formData){
                        $.ajax({
                           type: "PUT",
                           url: specialUrl,
                           data: formData,
                           dataType: 'json',
                           success: function (data) {
                           console.log(data);  
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            } 

                        });
                    }       

            $(".game").on('change', '.rating', function(){
                var pub_id = $(this).attr('pub-id');
                var rating = new FormData();
                rating.append("rating", this.files[0]);
                rating.append("pub_id", pub_id);
                console.log(rating);
                $.ajax({
                    type: "POST",
                    url: url + '/load_rating/' + game_id,
                    data: rating,
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,                
                    success: function (data) {
                        $(".rating").val("");
                        if(data != "None"){
                           if(data.game_data.game_data != "Error"){
                            var table = "<table class='table'><thead><tr><th>Команда</th>";
                            for(var i=1; i<=data.game_data.round_size; i++){
                               table += "<th>" + i + "</th>"; 
                            }
                            table += "<th>Результат</th><th>Место</th></tr></thead><tbody>";
                            var row = data.game_data.game_data;
                            var rows = row.split(',');
                            var places = 1;
                            var undef_count = 0;
                            console.log(data.absent_index);
                            rows.forEach( function(element, index) {

                                element = element.split('[');element=element[1].split(']');element = element[0]
                                element = element.split('=>');
                                var rating = element[1];
                                element = element[0];
                                element = element.split('(');
                                var command_name = element[0];
                                var rounds = element[1].split(')');
                                rounds = rounds[0];

                                command_name = command_name.split('_');var command_id = command_name[0];
                                command_name = command_name[1];

                                if(rounds != "Author"){
                                    if(command_id != "?"){
                                        table += "<tr><td id='command_" + command_id + "_" + pub_id + "'>" + command_name + "</td>"; 
                                    }else{
                                        table += "<tr style='background-color:red' ><td id='undefined_" + data.absent_index[undef_count] + "_" + pub_id + "'>" + command_name + "</td>";   
                                        undef_count++;
                                    }
                                        rounds = rounds.split(';');

                                        rounds.forEach( function(element, index) {
                                            table += "<td>" + element + "</td>"
                                        });
                                

                                table += "<td>" + rating + "</td><th>" + places + "</th></tr>"
                                places++;
                                }
                            });
                            if(undef_count == 0){
                                $(".btn_absent" + pub_id).remove();    
                                $(".pub" + pub_id).parent().append("<button absent-command='" + undef_count + "' pub-id='" + pub_id + "' class='btn btn-success btn_present btn_absent" + pub_id + " active'>Все команды найдены</button>"); 
                            }else{
                                $(".btn_absent" + pub_id).remove();    
                                $(".pub" + pub_id).parent().append("<button absent-command='" + undef_count + "' pub-id='" + pub_id + "' class='btn btn-danger btn_absent btn_absent" + pub_id + "'>Не найденные команды (" + undef_count + "шт.)</button>"); 
                            }
                            
                            table += "</tbody></table>"
                            $(".table_rating" + pub_id).empty().append(table);
                            
                        
                            var not_found = "";
                            var open_modal = false; 
                            data.absent_index.forEach( function(element, index) {

                                not_found += "<div class='form-group error'> <label class='col-sm-6 control-label found_command found_command" + element + "' index='" + element + "' style='text-align: left;' command-name='" + data.absent_command[element] + "' >" + data.absent_command[element] + "</label> <div class='col-sm-6 find_command" + element + "'> <i class='fas fa-times-circle not_found not_found" + element + "'></i> </div> </div>"
                                open_modal = true;
                            });
                            if(open_modal != false){
                                $("#frmRating").empty().append(not_found);
                                $("#modal_pub_id").val(pub_id);
                                $("#EditRating").modal('show');
                            }
                          }
                        }else{
                            var not_found = "<div class='form-group error'> <label style='text-align:left' class='col-sm-12 control-label'>Что-то пошло не так =( <i class='fas fa-times-circle not_found'></i></label></div>"
                            $("#frmRating").empty().append(not_found);
                            $("#EditRating").modal('show');
                        }

                           
                    },
                    error: function (data) {
                    console.log('Error:', data);
                    $("html").empty().append(data.responseText);
                    } 

                });
            });


            $("#frmRating").on('click', '.found_command', function(){
                var index = $(this).attr('index');
                $(this).removeClass('found_command');
                $(".not_found"+ index).remove();
                $(".find_command"+ index).append("<div style='display:flex;'><input type='name' autocomplete='off' style='margin-top:6px;' class='form-control has-error find_input find_input" + index + "' name='find_input" + index + "' index='" + index + "' placeholder='Поиск Команды'></div>")
            });

            $("#frmRating").on('click', '.find_input', function(){
                var index = $(this).attr('index');
                $.get('/admin/game/get_command', function(data){
                    var array = [];
                    if(data != "None"){
                        for(var i=0; i<data.length; i++){
                            array.push(data[i].command_name);
                        }
                        console.log(array);
                        $(".find_input" + index).autocomplete({ //на какой input:text назначить результаты списка
                            source: array
                        });
                     }else{
                         $(".find_input" + index).replaceWith("Команды не найдены")
                         setTimeout(function() { $(".find_input" + index).replaceWith("<i class='fas fa-times-circle not_found not_found" + index + "'></i>") }, 3000);
                     } 
                });
            });

            $("#frmRating").on('change', '.find_input', function(){
                    var index = $(this).attr('index');
                    var old_command_name = $(".found_command" + index).attr('command-name');
                    var new_command_name = $(".find_input" + index).val();
                    var pub_id = $("#modal_pub_id").val();

                    var dataForm = {
                        old_command_name: old_command_name,
                        new_command_name: new_command_name,
                        pub_id: pub_id,
                    }

                    $.ajax({
                        type: "PUT",
                        url: url + '/update_command_rating/' + game_id,
                        data: dataForm,
                        dataType: 'json',               
                        success: function (data) {
                            console.log(data);
                            var replace = "<label class='col-sm-6 control-label found_command found_command" + index + "' index='" + index + "' style='text-align: left;' command-name='" + new_command_name + "' >" + new_command_name + "</label>";
                            $(".found_command" + index).replaceWith(replace);
                            $(".find_command"+ index).replaceWith('<i class="fas fa-check-circle found"></i>');
                            document.getElementById("undefined_" + index + "_" + pub_id).innerHTML = new_command_name;
                            $("#undefined_" + index + "_" + pub_id).parent().css('background-color', '#f7f7f7');
                            var absent = $(".btn_absent" + pub_id).attr("absent-command");
                            absent--;
                            if(absent == 0){   
                                $(".btn_absent" + pub_id).replaceWith("<button absent-command='" + absent + "' pub-id='" + pub_id + "' class='btn btn-success btn_present btn_absent" + pub_id + " active'>Все команды найдены</button>"); 
                            }else{
                                $(".btn_absent" + pub_id).replaceWith("<button absent-command='" + absent + "' pub-id='" + pub_id + "' class='btn btn-danger btn_absent btn_absent" + pub_id + "'>Не найденные команды (" + absent + "шт.)</button>") 
                            }    
                            
                        },
                        error: function (data) {
                        console.log('Error:', data);
                        // $("html").empty().append(data.responseText);
                        } 

                    });
                });
            
    $(".game").on('click', '.btn_absent', function(){
        var pub_id = $(this).attr("pub-id");
         $.ajax({
            type: "GET",
            url: '/admin/game/get_absent_command/' + game_id + '/' + pub_id,
            success: function(data) {
                var not_found = "";
                data.absent_index.forEach(function(element, index) {
                    console.log(element, '=', index);
                    not_found += "<div class='form-group error'> <label class='col-sm-6 control-label found_command found_command" + element + "' index='" + element + "' style='text-align: left;' command-name='" + data.absent_command[element] + "' >" + data.absent_command[element] + "</label> <div class='col-sm-6 find_command" + element + "'> <i class='fas fa-times-circle not_found not_found" + element + "'></i> </div> </div>"
                });
                $("#frmRating").empty().append(not_found);
                $("#modal_pub_id").val(pub_id);
                $("#EditRating").modal('show');

            },
            error: function(data) {
                console.log('Error:', data);
                $("html").empty().append(data.responseText);
            }

        });
    });



    $("#add_pubs").on("click",".show_pub", function(){
        var PubID = $(this).val();
        $("#shown_pub_id").val(PubID);
        $.ajax({
            type: "GET",
            url: "/admin/game/creation/get_command/" + game_id + "/" + PubID,
            success: function (data) {
                if(data=="NotFound"){
                    $("#list_commands").empty();
                    alert("Ошибка!");
                    return false;
                }
                if(data=="Absent"){
                    $("#list_commands").empty();
                    $("#list_commands").append("<h1 class='absent_command' style='font-size: 1.5em;margin: 10px 0 10px;text-align: center;font-weight: 900;letter-spacing: 0.4px;color: #1D5E6A;'>Команды Отсутствуют!</h1>");
                    return false;
                }else{
                    $("#list_commands").empty();
                    data.forEach( function(element, index) {
                            if(element.command_img_path == "None"){
                                var command_html = "<div class='row command_row cr" + element.id + "' style='margin-top: 20px;'><div class='form-group command" + element.command_name + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
                                if(element.status == "Delete"){
                                    command_html += "<div class='col-sm-5 command_name list_margin'>" + element.command_name + "</div><div class='col-sm-2 list_margin'><span class='free_places'>" + element.places + "/6</span></div><div class='col-sm-3 list_margin'><button class='btn btn-danger cancel_command' value='" + element.id + "'>Cнять заявку</button></div></div></div>";
                                }
                            }
                            else{
                                var command_html = "<div class='row command_row cr" + element.id + "'><div class='form-group command" + element.command_name + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + element.command_img_path + "'/></div></div>";
                                if(element.status == "Delete"){
                                    command_html += "<div class='col-sm-5 command_name '>" + element.command_name + "</div><div class='col-sm-2  '><span class='free_places'>" + element.places + "/6</span></div><div class='col-sm-3  '><button class='btn btn-danger cancel_command  ' value='" + element.id + "'>Cнять заявку</button></div></div></div>";
                                }
                            }
                        
                        $("#list_commands").append(command_html);
                    });    
                }
                console.log(data);
            },
            error: function (data) {
                console.log('Error:', data);
                $("html").empty().append(data.responseText);
            },
        }); 




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


    $(".add_command").on('click', '.add_command_input', function(){
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

    $(".add_command").on('click', '.add_command-btn', function(){
        var command_name = $(".add_command_input").val();
        var pub_id = $("#shown_pub_id").val();
        var dataForm = {
            command_name:command_name,
            Pub_id:pub_id,
        }
        $.ajax({
            type: "POST",
            url: "/admin/game/creation/add_command" + $("#id_game").val(),
            data: dataForm,
            dataType: 'json',
            success: function (data) {
            console.log(data);
                if(data=="Absent"){
                    alert("Такой Команды не существует");
                }else{
                    $(".absent_command").remove();
                    $(".add_command_input").val("");
                    if(data.command_img_path == "None"){
                        var command_html = "<div class='row command_row cr" + data.id + "' style='margin-top: 20px;'><div class='form-group command" + data.command_name + "'><div class='col-sm-2'><div class='command_background'><i class='fas fa-smile'></i></div></div>";
                            command_html += "<div class='col-sm-5 command_name list_margin'>" + data.command_name + "</div><div class='col-sm-2 list_margin'><span class='free_places'>" + 1 + "/6</span></div><div class='col-sm-3 list_margin'><button class='btn btn-danger cancel_command' value='" + data.id + "'>Cнять заявку</button></div></div></div>";
                    }
                    else{
                        var command_html = "<div class='row command_row cr" + data.id + "'><div class='form-group command" + data.command_name + "'><div class='col-sm-2'><img style='width: 100%;margin-top: 10px;height: 40px;' src='" + data.command_img_path + "'/></div></div>";
                            command_html += "<div class='col-sm-5 command_name list_margin'>" + data.command_name + "</div><div class='col-sm-2 list_margin'><span class='free_places'>" + 1 + "/6</span></div><div class='col-sm-3 list_margin'><button class='btn btn-danger cancel_command' value='" + data.id + "'>Cнять заявку</button></div></div></div>";
                    }        
                    $("#list_commands").append(command_html);  
                }  
            },
            error: function (data) {
                console.log('Error:', data);
                $("html").empty().append(data.responseText);
            }
        });
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
                    if(data.table != 0){
                        $(".cr" + formData.command_id).remove();
                    }
                    else{
                        $(".cr" + formData.command_id).remove();
                        var command_html = "<div class='row command_row'><h1 class='absent_command' style='font-size: 1.5em;margin: 10px 0 10px;text-align: center;font-weight: 900;letter-spacing: 0.4px;color: #1D5E6A;'>Команды Отсутствуют!</h1></div>";
                        $("#list_commands").append(command_html);
                    }

                
                    
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
    });




});
