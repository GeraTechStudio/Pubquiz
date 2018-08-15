$(function() {

	var url_preloader = $("#preloader").val();
	//SVG Fallback
	if(!Modernizr.svg) {
		$("img[src*='svg']").attr("src", function() {
			return $(this).attr("src").replace(".svg", ".png");
		});
	};

	$.ajaxSetup({
	    headers: {
	    	'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
		}
	})
	
	
	$(".mobile-mnu").after("<div id='my-menu'>");
	$(".main-menu-navigation-heat__header").clone().appendTo("#my-menu");
	$("#my-menu").find("ul").removeClass("main-menu-navigation-heat__header");
	
	$("#my-menu").mmenu({
		extensions : ['widescreen', 'theme-white', 'effect-menu-slide', 'pagedim-black', "position-back","position-bottom"],
		"counters": true,
               "iconbar": {
                  "add": true,
                  "top": [
                     "<a href='/'><i class='fa fa-home'></i></a>",
                     "<a href='/home'><i class='fa fa-user'></i></a>"
                  ],
                  "bottom": [
                     "<a href='#/'><i class='fa fa-twitter'></i></a>",
                     "<a href='#/'><i class='fa fa-facebook'></i></a>",
                     "<a href='#/'><i class='fa fa-linkedin'></i></a>"
                  ]
               },
		navbar: {
			title:"Меню"
		}

	});

	$.ajax({
		type: "GET",
		url: '/checkAuth',
		success: function (data) {
			if(data == "Yes"){
				$(".logout").remove();
				$(".mm-iconbar__top").append("<a class='logout' href='/logout'><i class='fas fa-sign-out-alt'></i></a>");
				$(".mm-iconbar__top").on('click', '.logout', function(){
					event.preventDefault();
					document.getElementById('logout-form').submit();
				});
			}else{
				$(".logout").remove();
			}
		}
	});


	
    	var position = 0, scrollTop = 0, scrollBottom = 0;
    	var stopPosition = true;
		 	window.onscroll = function() {
			  var scrolled = window.pageYOffset || document.documentElement.scrollTop;
				  if(stopPosition==true){
				  	$("#menu").removeClass('fixed_hide');
				  }
				  else{
				  	$("#menu").addClass('fixed_hide');
				  }
			  		if(scrolled > scrollBottom){
			  			scrollBottom = scrolled;
			  			scrollTop = 0;
			  			position = 0;
			  			stopPosition = false;
			  		}
			  		else{
			  			if(scrollBottom > scrolled && scrolled > 110){
			  				scrollTop = scrolled;
			  				stopPosition = true;
			  				position = 1;
			  			}
			  		}
			  		if(scrollBottom-scrollTop <=200 && scrollBottom-scrollTop >=50 && position == 1){
			  			scrollBottom=scrolled+50;
			  		}
			  		if(scrolled <=110){
			  			stopPosition = true;
			  			scrollBottom = 0;
			  		}
	  	if(scrolled == 0){
		  	$("#menu").removeClass('fixed_hide');
	  	}
	}



	$(function () {
      var element = $("#hid"), display;
      $(window).scroll(function () {
        display = $(this).scrollTop() <= 100;
        display != element.css('opacity') && element.stop().animate({ 'opacity': display, 'z-index' : display }, 0);
      });
    });


	

		


	$(".mobile-mnu").click(function(){
		$("#my-menu").data("mmenu").open();
		var thiss = $(this).find(".toggle-mnu");
	 	thiss.toggleClass("on");
	  	$(".main-mnu").slideToggle();
	 	 return false;
	});






	

	//Chrome Smooth Scroll
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch(err) {

	};

	$(".slider").owlCarousel({
		loop:true,
		items:1,
		itemElement:"slide-wrap",
		nav:true,
	});
	$(".owl-prev").html("<i class='fas fa-angle-left'></i>");
	$(".owl-next").html("<i class='fas fa-angle-right'></i>");
	$(".owl-dots").addClass("hidden-xs hidden-sm");
	$("img, a").on("dragstart", function(event) { event.preventDefault(); });


	$('.main-box-list-game h3').matchHeight();

	$("#tel").mask("+380(99) 99-99-999");

	$(".main-menu-navigation-heat__header").on('click', '.get_corporate_game', function(){
		$("#Corporate_game").remove();
		var modal = "<div class='modal fade' id='Corporate_game' tabindex='-1' role='dialog' aria-labelledby='Corporate_gameLabel' aria-hidden='true'><div class='modal-dialog' style='z-index: 1000000000000000;margin: 110px auto;'><div class='modal-content'><div class='modal-header'> <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>×</span></button> <h4 class='modal-title' id='profileAdminLabel'>Заказать Корпоративную Игру</h4> </div> <div class='modal-body'> <form id='frmCorporate' name='frmCorporate' class='form-horizontal' novalidate=''> <div class='form-group error'> <label for='corporate_name' class='col-sm-3 control-label'>Имя</label> <div class='col-sm-9'> <input type='text' class='form-control has-error' id='corporate_name' name='corporate_name' placeholder='Ваше Имя' autofocus> </div> </div> <div class='form-group'> <label for='corporate_email' class='col-sm-3 control-label'>Почта</label> <div class='col-sm-9'> <input type='text' class='form-control' id='corporate_email' name='email' placeholder='Ваша Почта' autofocus> </div> </div> <div class='form-group'> <label for='corporate_tel' class='col-sm-3 control-label'>Телефон</label> <div class='col-sm-9'> <input id='corporate_tel' type='corporate_tel' class='form-control' name='corporate_tel' value='' placeholder='Ваш Номер Телефона' required='' autofocus=''> </div> </div> </form> </div> <div class='modal-footer'> <button type='button' class='btn btn-primary accept_corporate' >Подать Заявку</button> </div> </div> </div> </div>";
		$("main").append(modal);
		$("#corporate_tel").mask("+380(99) 99-99-999");
		$('#Corporate_game').modal('show');

		$(".modal-footer").on('click', '.accept_corporate', function(){

			var length_name = $("#corporate_name").val().length;
	        if(length_name < 1){
	            corporate_name.style.border =  "1px solid #ff1212"; corporate_name.placeholder = "Заполните строку!";
	        }else{
	            corporate_name.style.border =  "1px solid #ccc"; corporate_name.placeholder = "Ваше Имя";
	        }

	        var length_email = $("#corporate_email").val().length;
	        if(length_email < 1){
	            corporate_email.style.border =  "1px solid #ff1212"; corporate_email.placeholder = "Заполните строку!";
	        }else{
	            corporate_email.style.border =  "1px solid #ccc"; corporate_email.placeholder = "Ваша Почта";
	        }

	        var length_tel = $("#corporate_tel").val().length;
	        if(length_tel < 1){
	            corporate_tel.style.border =  "1px solid #ff1212"; corporate_tel.placeholder = "Заполните строку!";
	        }else{
	            corporate_tel.style.border =  "1px solid #ccc"; corporate_tel.placeholder = "Ваш Номер Телефона";
	        }

	        if(length_name == 0 || length_email == 0 || length_tel == 0){
            	return false;
	        }
	        else{
	        	
	            var dataForm = {
	                Corporate_name: $("#corporate_name").val(),
	                Corporate_email: $("#corporate_email").val(),
	                Corporate_tel: $("#corporate_tel").val(),
	            }
                $.ajax({
                    type: "POST",
                    url: '/corporateApply',
                    data: dataForm,
                    dataType: 'json',
                    beforeSend: function(){
                    	var preload_gif = "<div class='preload' style='position: relative;padding: 73.5px; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 2147483647; background-image: url(" + url_preloader + "); background-repeat: no-repeat; background-position: center center;'></div>";
			            $("#Corporate_game .modal-footer").remove();
			            $("#Corporate_game .modal-body").empty().append(preload_gif);
			        },
                    success: function (data) {
                        console.log(data);
                        var preload_gif = "<div class='corporate_alert' style='position: relative;padding: 10px 10px; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 2147483647;'><h1 style='text-align:center;'>" + data.name + ", Спасибо за заявку<i class='fas fa-smile'></i>!</h1><h2 style='text-align:center;'>Наш Менеджер свяжется с вами в ближайшее время!</h2></div>";
			            $("#Corporate_game .modal-body").empty().append(preload_gif);
			            setTimeout(function() { $('#Corporate_game').modal('hide') }, 3000);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        var preload_gif = "<div class='corporate_alert' style='position: relative;padding: 10px 10px; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 2147483647;'><h1 style='text-align:center;'>Упс!<i class='fas fa-frown'></i>!</h1><h2 style='text-align:center;'>Произошла Ошибка</h2></div>";
			            $("#Corporate_game .modal-body").empty().append(preload_gif);
			            setTimeout(function() { $('#Corporate_game').modal('hide') }, 3000);
                    } 
                
                });	
            }    
		});
	})
	$(".preloader").fadeOut();


});
