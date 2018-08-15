<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Активация регистрации нового ползователя</title>
    <style type="text/css">
    	html{
    		display: flex;
    		flex-direction: column;
    		min-height:100%; 
    	}
    	body{
    		margin:0;
    		padding:0;
    		flex:auto;
    	}
    	.body{
    		margin:15px;
    		border:10px solid rgba(49, 73, 92, 0.76);
    		position: relative;
    	}
    	.mail_header{
    		background-color: #3f95a4;
		    width: 100%;
		    height: 80px;
		    text-align: center;
		    position: absolute;
		    top:0;
		    left: 0;
		    right: 0;
    	}
		.mail_header h1{
    			color: #fff;
			    font-size: 4em;
			    letter-spacing: 10px;
			    line-height: 75px;
			    margin: 0;
    		}
    	.box_hi{
    		position: relative;
    		top:60px;
    	}

    	.box_hi h1{
			    color: #627483;
			    font-size: 2.5em;
			    margin: 30px 0 40px 110px;
    	}

    	.box_hi p{
			margin: 30px 170px 40px 110px;
		    font-size: 1.7em;
		    font-family: "TimesNewRoman";
		    letter-spacing: 0px;
		    text-align: justify;
		    line-height: 30px;
    	}
    	.box_hi p a{
    		color: #627483;
    		text-decoration: none;
    	}
    	.btn{
    		background-color: #3f95a4;
		    padding: 10px 20px;
		    border-radius: 12px;
		    color: #fff;
		    text-decoration: none;
		    font-size: 1.3em;
		    display: inline-block;
			margin: 0px 0px 25px;
		    transition: 1s all ease;
    	}
    	.btn:hover{
    		color: #000000a3;
    		background: #3f95a4bd;
    	}
    	footer{
    		position: absolute;
    		bottom: 0;
    		right:0;
    		left: 0;
			padding: 30px 0 0;
    		height: 50px;
    		background-color:#d7d7d7;
    		text-align: center;
    		font-size: 1.5em;
    	}
    </style>
</head>
<body>
	<div class="body">
		<header class="mail_header">
			<h1>PubQuiz</h1>
		</header>
		<div class="box_hi">
			<h1>Приветствую {{$user->name}},</h1>
				<p>Мы очень рады, что Вы присоединились к нам! Вы успешно создали аккаунт на <a href="pubquiz.me.">pubquiz.me.</a>! Вам нужно подтвердить свой адрес электронной почты и активировать свою учетную запись, нажав кнопку ниже.</p>
		</div>
	    <p style="text-align: center;">
	        <a class= "btn" href='{{ url("register/confirm/{$user->token}") }}'>Подтвердить</a>
	    </p>
	    <footer>
			pubquiz.me © 2018 Все права защищены.
	    </footer>
    </div>
</body>
</html>