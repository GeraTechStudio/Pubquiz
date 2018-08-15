$(document).ready(function(){
	var url = "/home";
	var user_id = $("#user_id").val();
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
        }
    })

        $("#user_avatar").change(function(){
            var Avatar = new FormData();
            Avatar.append("Avatar", this.files[0]);
            Avatar.append("user_id", user_id);
                $.ajax({
                    type: "POST",
                    url: url + '/upload/Avatar',
                    data: Avatar,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        console.log(data);
                        var img = "<div class='home_user_box-img' style='background-image: url(" + data.img_path +"); background-position: center center;'> </div>"
                        $(".home_user_box-img").replaceWith(img);
                        var mini_img = "<span class='round user_avatar' style='background-image: url(" + data.img_path +");'></span><span class='hidde'>.</span>";
                        $(".round").replaceWith(mini_img);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    } 
                
                });
        });

        $(".home_main_box").on('click', '#edit_profile_user', function(){
	        $.get(url + '/getUser/' + user_id, function (data){
	            console.log(data);
	            $('#login_user').val(data.login);
	            $('#name_user').val(data.name);
	            $('#email_user').val(data.email);
	            $('#tel_user').val(data.tel);
	            $('#profileUser').modal('show');
	        })
        });

        $("#btn-save_user").click(function (e) {

	        var length = $('#password_user').val().length;
	        if(length==0){

	        }
	        else{if(length < 6 || length > 30){
	            if(length  < 6){
	                msg_ask_user.style.display = "none"; answer_user.style.color = 'red'; answer_user.textContent = 'Минимум 6 символов';
	            }
	            if(length > 30){
	                msg_ask_user.style.display = "none"; answer_user.style.color = 'red'; answer_user.textContent = 'Максмум 30 символов';
	            }
	            return false;
	        }}


	        var formData = {
	            user_id: $('#user_id').val(),
	            login: $('#login_user').val(),
	            name: $('#name_user').val(),
	            email: $('#email_user').val(),
	            password: $('#password_user').val(),
	            tel: $('#tel_user').val(),
	        }

	        $.ajax({
	            type: "PUT",
	            url: url + '/change/' + user_id,
	            data: formData,
	            dataType: 'json',
	            success: function (data) {
	                console.log(data);
	                user_name.textContent = $('#name_user').val();
	                $('#frmUsers').trigger("reset");
	                msg_ask_user.style.display = "none";
	                answer_user.textContent = '';
	                $('#profileUser').modal('hide');
	            },
	            error: function (data) {
	                console.log('Error:', data);
	            }
	        });
    })

    password_user.addEventListener('keyup', function(evt){
      let length = this.value.length
      if (length < 6){msg_ask_user.style.display = "inline-block"; answer_user.style.color = 'red'; answer_user.textContent = 'Недопустимый';}
      else if (length == 6) {msg_ask_user.style.display = "inline-block"; answer_user.style.color = 'red'; answer_user.textContent = 'Слабый';}
      else if (length > 8  && length < 12) {msg_ask_user.style.display = "inline-block"; answer_user.style.color = '#e6d112'; answer_user.textContent = 'Номальный';}
      else if (length > 15 && length < 31) {msg_ask_user.style.display = "inline-block"; answer_user.style.color = 'green'; answer_user.textContent = 'Сложный';}
      else if (length > 30){msg_ask_user.style.display = "inline-block"; answer_user.style.color = 'red'; answer_user.textContent = 'Недопустимый';}
    })
});