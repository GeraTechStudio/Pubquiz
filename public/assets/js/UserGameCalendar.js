$(document).ready(function(){
	var url = "/Calendar/Games";

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
              $.get(url + '/get/closer_game', function(data){
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

    $(".fc-bg table tbody tr").css('display', 'flex');
    $(".fc-bg").parent().css('height', '100px');
    $(".fc-bg table tbody tr").css('height', '100%');
    $(".fc-bg table tbody tr td").css('width', 'calc(100%)');
    $(".fc-day-grid-container").height(600);

    /*First Load*/
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
});