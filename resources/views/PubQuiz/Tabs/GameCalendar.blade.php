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
                    <a href="#">Календарь Игр</a>
                  </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10">
                <div id='calendar'></div>
            </div>

        </div>
    </div>
        
</main>
<script src="{{asset('assets/libs/fullcalendar/js/moment.min.js')}}"></script>
<script src="{{asset('assets/libs/fullcalendar/js/fullcalendar.min.js')}}"></script>
<script src="{{asset('assets/libs/fullcalendar/locale/ru.js')}}"></script>
<script src="{{asset('assets/js/UserGameCalendar.js')}}"></script>
 

@endsection
