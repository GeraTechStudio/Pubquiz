@extends(env('THEME').'.mainlayout.mainlayout')

@section('add_style')

    <style type="text/css">
        html{
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }
        body{
            display: flex;
            flex: auto;
            flex-direction: inherit;
            justify-content: space-between;
        }
    </style>
    
@endsection


@section('header')
 {!!$region!!}
@endsection 

@section('navigation')
 {!!$navigation!!} 
@endsection

@section('header_content')  
@endsection

@section('hid')
@endsection



@section('content')
<main class="home_page admin">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10">
                <ol id="breadcrumb">
                  <li>
                    <a href="{{url('/')}}"><i class="fas fa-home"></i>
                    <span class="sr-only">Главная</span></a>
                  </li>
                  <li class="current">
                    <a href="#">Контакты</a>
                  </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10">
                <div class="col-sm-5">
                    <div class="contact_content">
                        <h1>Контакты</h1>
                        <div><h3>Страна:</h3><span>Украина</span></div>
                        <div><h3>Город:</h3><span>Киев</span></div>
                        <div><h3>Телефоны:</h3><span>+38 (044) 232-77-62</span></div>
                        <div><h3>Телефоны:</h3><span>+38 (093)589-09-32</span></div>
                        <div><h3>E-mail:</h3><span>quiz@Pubquiz.me</span></div>
                        <div><h3>ВКонтакте:</h3><span>vk.com/pub.quiz</span></div>
                        <div><h3>facebook:</h3><span>facebook.com/PubQuiz.Me</span></div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="contact_map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d325518.69909605145!2d30.251828163883477!3d50.40169740450293!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4cf4ee15a4505%3A0x764931d2170146fe!2z0JrQuNC10LIsIDAyMDAw!5e0!3m2!1sru!2sua!4v1529488419537" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
</main>

 

@endsection
