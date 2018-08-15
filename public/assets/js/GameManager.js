$(document).ready(function() {
    var url = "/admin/game";

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
      }
    })  

    $('#calendar').fullCalendar({
        defaultView: 'month',
        height: 650,
        locale: 'ru',
         customButtons: {
          myCustomButton: {
            text: 'Ближайшая игра!',
            click: function() {
              $.get(url + '/closer_game', function(data){
                if(data.id == "None"){
                  alert("Ближайшая игра не найдена! Возможно игра в режиме <Редактирования>!");
                }else{
                  document.location.href = data.url + '/' + data.id;
                }
              });
            }
          }
        },
        header: {
          left: 'myCustomButton',
        center: 'title',
        right:  'today prev,next',
        },  
    });

    var object = $(".fc-bg table tbody tr");

    for(var i=0; i<=5; i++){
      $(object[i]).addClass('row' + i);
    }


    // console.log();

    // return false;
    $(".fc-bg table tbody tr").css('display', 'flex');
    $(".fc-bg").parent().css('height', '100px');
    $(".fc-bg table tbody tr").css('height', '100%');
    $(".fc-bg table tbody tr td").css('width', 'calc(100%)');
    $(".fc-day-grid-container").height(600);
    

    /*First Load*/
    var create_game = "<div class='box_container'><button class='box_add_game'>Создать игру</button></div>"
    $(".fc-day").append(create_game);

    $.get(url + '/get/data', function(data){
      console.log(data);
      var memory_addition_cont = 0;
      var memory_addition_last = 0;
      for(var i=0; i<data.closer_game.length; i++){
        if(data.closer_game[i].game_date == memory_addition_cont){
            var height_row = $('.fc-day[data-date="' + data.closer_game[i].game_date + '"]').parent().parent().parent().parent().parent().height();
            $('.fc-day[data-date="' + data.closer_game[i].game_date + '"]').parent().parent().parent().parent().parent().height(height_row + 23);
            $(".fc-day-grid-container").height($(".fc-day-grid-container").height() + 23);

            if(data.closer_game[i].confirmed == 0){
              $('.fc-day[data-date="' + data.closer_game[i].game_date + '"] .cl-game').append("<a href='" + data.url + '/' + data.closer_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.closer_game[i].project_color + "; background-color: " + data.closer_game[i].project_color + "; text-decoration:underline;'>!!! " + data.closer_game[i].game_time + " - " + data.closer_game[i].game_name +  " !!!</a>");
            }else{
              $('.fc-day[data-date="' + data.closer_game[i].game_date + '"] .cl-game').append("<a href='" + data.url + '/' + data.closer_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.closer_game[i].project_color + "; background-color: " + data.closer_game[i].project_color + ";'>" + data.closer_game[i].game_time + " - " + data.closer_game[i].game_name +  "</a>");
            }

        }else{
            memory_addition_cont = data.closer_game[i].game_date;
            if(data.closer_game[i].confirmed == 0){
              $('.fc-day[data-date="' + data.closer_game[i].game_date + '"]').append("<div class='cl-game'><a href='" + data.url + '/' + data.closer_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.closer_game[i].project_color + "; background-color: " + data.closer_game[i].project_color + "; text-decoration:underline;'>!!! " + data.closer_game[i].game_time + " - " + data.closer_game[i].game_name +  " !!!</a></div>");
            }else{
              $('.fc-day[data-date="' + data.closer_game[i].game_date + '"]').append("<div class='cl-game'><a href='" + data.url + '/' + data.closer_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.closer_game[i].project_color + "; background-color: " + data.closer_game[i].project_color + ";'>" + data.closer_game[i].game_time + " - " + data.closer_game[i].game_name +  "</a></div>");
            }
        }
      }

      console.log(data.last_game);
      for(var i=0; i<data.last_game.length; i++){
        if(data.last_game[i].game_date == memory_addition_last){
            $('.fc-day[data-date="' + data.last_game[i].game_date + '"] .cl-game').append("<a href='" + data.url + '/' + data.last_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.last_game[i].project_color + "; background-color: " + data.last_game[i].project_color + "; opacity:0.3;'>" + data.last_game[i].game_time + " - " + data.last_game[i].game_name +  "</a>");
            var height_row = $('.fc-day[data-date="' + data.last_game[i].game_date + '"]').parent().parent().parent().parent().parent().height();
            $('.fc-day[data-date="' + data.last_game[i].game_date + '"]').parent().parent().parent().parent().parent().height(height_row + 23);
            $(".fc-day-grid-container").height($(".fc-day-grid-container").height() + 23);
        }else{
           memory_addition_last = data.last_game[i].game_date;
           $('.fc-day[data-date="' + data.last_game[i].game_date + '"]').append("<div class='cl-game'><a href='" + data.url + '/' + data.last_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.last_game[i].project_color + "; background-color: " + data.last_game[i].project_color + "; opacity:0.3;'>" + data.last_game[i].game_time + " - " + data.last_game[i].game_name +  "</a></div>"); 
        }
      }
      
    }); 



    
    

    /*Next updating*/
    $('.fc-right').click(function(){
      $(".fc-day").empty();
      $(".fc-bg table tbody tr").css('display', 'flex');
      $(".fc-bg").parent().css('height', '100px');
      $(".fc-bg table tbody tr").css('height', '100%');
      $(".fc-bg table tbody tr td").css('width', 'calc(100%)');
      $(".fc-day-grid-container").height(600);

      var create_game = "<div class='box_container'><button class='box_add_game'>Создать игру</button></div>"
      $(".fc-day").append(create_game);
      
      $.get(url + '/get/data', function(data){
        console.log(data);
        var memory_addition_cont = 0;
        var memory_addition_last = 0;
        for(var i=0; i<data.closer_game.length; i++){
          if(data.closer_game[i].game_date == memory_addition_cont){
              var height_row = $('.fc-day[data-date="' + data.closer_game[i].game_date + '"]').parent().parent().parent().parent().parent().height();
              $('.fc-day[data-date="' + data.closer_game[i].game_date + '"]').parent().parent().parent().parent().parent().height(height_row + 23);
              $(".fc-day-grid-container").height($(".fc-day-grid-container").height() + 23);

              if(data.closer_game[i].confirmed == 0){
                $('.fc-day[data-date="' + data.closer_game[i].game_date + '"] .cl-game').append("<a href='" + data.url + '/' + data.closer_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.closer_game[i].project_color + "; background-color: " + data.closer_game[i].project_color + "; text-decoration:underline;'>!!! " + data.closer_game[i].game_time + " - " + data.closer_game[i].game_name +  " !!!</a>");
              }else{
                $('.fc-day[data-date="' + data.closer_game[i].game_date + '"] .cl-game').append("<a href='" + data.url + '/' + data.closer_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.closer_game[i].project_color + "; background-color: " + data.closer_game[i].project_color + ";'>" + data.closer_game[i].game_time + " - " + data.closer_game[i].game_name +  "</a>");
              }

          }else{
              memory_addition_cont = data.closer_game[i].game_date;
              if(data.closer_game[i].confirmed == 0){
                $('.fc-day[data-date="' + data.closer_game[i].game_date + '"]').append("<div class='cl-game'><a href='" + data.url + '/' + data.closer_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.closer_game[i].project_color + "; background-color: " + data.closer_game[i].project_color + "; text-decoration:underline;'>!!! " + data.closer_game[i].game_time + " - " + data.closer_game[i].game_name +  " !!!</a></div>");
              }else{
                $('.fc-day[data-date="' + data.closer_game[i].game_date + '"]').append("<div class='cl-game'><a href='" + data.url + '/' + data.closer_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.closer_game[i].project_color + "; background-color: " + data.closer_game[i].project_color + ";'>" + data.closer_game[i].game_time + " - " + data.closer_game[i].game_name +  "</a></div>");
              }
          }
        }

        console.log(data.last_game);
        for(var i=0; i<data.last_game.length; i++){
          if(data.last_game[i].game_date == memory_addition_last){
              $('.fc-day[data-date="' + data.last_game[i].game_date + '"] .cl-game').append("<a href='" + data.url + '/' + data.last_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.last_game[i].project_color + "; background-color: " + data.last_game[i].project_color + "; opacity:0.3;'>" + data.last_game[i].game_time + " - " + data.last_game[i].game_name +  "</a>");
              var height_row = $('.fc-day[data-date="' + data.last_game[i].game_date + '"]').parent().parent().parent().parent().parent().height();
              $('.fc-day[data-date="' + data.last_game[i].game_date + '"]').parent().parent().parent().parent().parent().height(height_row + 23);
              $(".fc-day-grid-container").height($(".fc-day-grid-container").height() + 23);
          }else{
             memory_addition_last = data.last_game[i].game_date;
             $('.fc-day[data-date="' + data.last_game[i].game_date + '"]').append("<div class='cl-game'><a href='" + data.url + '/' + data.last_game[i].id + "' class='cl-game-link' style='border:1px solid" + data.last_game[i].project_color + "; background-color: " + data.last_game[i].project_color + "; opacity:0.3;'>" + data.last_game[i].game_time + " - " + data.last_game[i].game_name +  "</a></div>"); 
          }
        }
        
      }); 
    })


    $('#calendar').on('click','.box_add_game',function(){

        $("#Game_creation").trigger("reset");
        $('#Season_selection').empty();
        $('#Project_Selection').empty();

        var gameDate = $(this).parent().parent().attr('data-date');
        $("#game_date").val(gameDate);


         $.get(url + '/get/Resources', function(data){
          var empty_project = true, empty_season = true;
          var season_selects = "", project_selects = "";
          console.log(data);
            for(var i=0; i<data.projects.length; i++){
              empty_project = false;
               project_selects += "<option value='" + data.projects[i].id + "'>" + data.projects[i].Project_name + "</option>";  
            }

            for(var i=0; i<data.seasons.length; i++){
              empty_season=false;
              season_selects += "<option value='" + data.seasons[i].id + "'>" + data.seasons[i].Season_name + "</option>";
            }
            console.log(empty_project, empty_season);

            if(empty_project == false){
              $('#Project_Selection').append(project_selects);
            }
            else{
              alert("На данный момент, проект еще не создан!")
            }

            if(empty_season == false){
              $('#Season_selection').append(season_selects);
            }
            else{
              alert("На данный момент, сезон еще не создан!")
            }

            if(empty_season == false && empty_project==false){
              $('#Create_game').modal('show');
            }

          });
    });


    $("#Create_game").on('click', '#btn_create_game', function(){
      
      var length_name = $("#Game_name").val().length;
        if(length_name < 1){
            Game_name.style.border =  "1px solid #ff1212"; Game_name.placeholder = "Заполните строку!";
            return false;
        }else{
            Game_name.style.border =  "1px solid #ccc"; Game_name.placeholder = "Назовите Локацию";
        }


      var formData = {
        Game_name: $('#Game_name').val(),
        Game_data: $('#game_date').val(),
        Game_project: $('#Project_Selection').val(),
        Game_season: $('#Season_selection').val(),
      }

      $.ajax({
          type: "POST",
          url: url + '/post/GameBuffer',
          data: formData,
          dataType: 'json',
          success: function (data) {
            document.location.href = $('#url').val() + '/admin/game/creation/' + data.id;
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });
    
    });


});