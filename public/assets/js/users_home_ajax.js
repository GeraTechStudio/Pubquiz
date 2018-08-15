$(document).ready(function(){
    var url = "/admin/user";

    /*change admin profile*/
    $('#users-list').on("click", ".open-modal", function(){
        var user_id = $(this).val();
        $.get(url + '/' + user_id, function (data){
            console.log(data);
            $('#user_id').val(data.id);
            $('#login_user').val(data.login);
            $('#name_user').val(data.name);
            $('#email_user').val(data.email);
            $('#tel_user').val(data.tel);
            $('#verified_user').val(data.verified);
            $('#profileUser').modal('show');
        })

    });

    $("#btn-save_user").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            }
        })


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

        var user_id = $('#user_id').val();

        var formData = {
            user_id: $('#user_id').val(),
            login: $('#login_user').val(),
            name: $('#name_user').val(),
            email: $('#email_user').val(),
            tel: $('#tel_user').val(),
            password: $('#password_user').val(),
            verified: $('#verified_user').val(),
        }

        $.ajax({
            type: "PUT",
            url: url + '/change/' + user_id,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);

                var user = '<tr id="user' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '" >' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
                + data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
                user +='<td><div class="btn-group btn-group-sm"><button class="btn btn-primary change-status" value="' +  data.id + '"><i class="fas fa-lock"></i></button>' +
                '<button class="btn btn-warning open-modal" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
                '<button class="btn btn-danger delete-admin" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>'
                $("#user" + user_id).replaceWith( user );

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


    /*delete admin*/
    $('#users-list').on("click", ".delete-admin", function(e){
        var user_id = $(this).val();
        $('#Delete_Change_user').modal('show');
        $('#user_id').val(user_id);
        $('#btn_ask_user').val("delete");
        Delete_Change_ask_user.textContent = "Вы действительно хотите удалить пользователя?";
        btn_ask_user.textContent = "Удалить";
    });

    /*change admin status*/
    $('#users-list').on("click", ".change-status", function(e){
        var user_id = $(this).val();
        $('#Delete_Change_user').modal('show');
        $('#user_id').val(user_id);
        $('#btn_ask_user').val("change");
        Delete_Change_ask_user.textContent = "Вы действительно хотите назначить его(ее) на пост администратора?";
        btn_ask_user.textContent = "Назначить";
    });


    $('#Delete_Change_user').on("click", "#btn_ask_user", function(e){
        var user_id = $('#user_id').val();
        var state = $('#btn_ask_user').val();
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                }
            })

        if(state == "delete"){
            $.ajax({
                type: "DELETE",
                url: url + '/delete/' + user_id,

                success: function (data) {
                    console.log(data);
                    $('#Delete_Change_user').modal('hide');
                    $("#user" + user_id).remove();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }

        if(state == "change"){
               
            $.ajax({
                type: "PUT",
                url: url + '/' + user_id,
                success: function (data) {
                    console.log(data);
                    $('#Delete_Change_user').modal('hide');
                    
                    var admin = '<tr id="admin' + data.id + '"><td>' + data.id + '</td><td class="column_login" title="' + data.login + '">' + data.login + '</td><td class="column_name" title="' + data.name + '">' + data.name + '</td><td>' 
                    + data.tel + '</td><td class="column_verified">'+ data.verified + '</td>';
                    admin +='<td><div class="btn-group btn-group-sm"><button class="btn btn-default change-status" value="' +  data.id + '"><i class="fas fa-lock-open"></i></button>' +
                    '<button class="btn btn-warning open-modal" value="' + data.id + '"><i class="fas fa-cog"></i></button>' +
                    '<button class="btn btn-danger delete-admin" value="' + data.id + '"><i class="fas fa-trash-alt"></i></button></div></td></tr>';
                    
                    $('#admins-list').append(admin);
                    $("#user" + user_id).remove();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }     
    });

    /*Cancel*/
    $('#Delete_Change_user').on("click", "#btn_cancel_user", function(e){
        $('#Delete_Change_user').modal('hide');
    });
});