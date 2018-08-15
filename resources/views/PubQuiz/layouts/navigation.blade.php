<div class="main-menu-heat__header hidden-sm hidden-xs">
                                <ul class="main-menu-navigation-heat__header">
                                    <li><a href="{{ url('/') }}">Главная</a></li>
                                    <li><a href="{{ route('rules') }}">Правила</a></li>
                                    <li><a href="{{ route('GameCalendar') }}">Календарь игр</a></li>
                                    <li><a href="{{ route('rating') }}">Рейтинг</a></li>
                                    <li><a href="#get_corporate_game" class="get_corporate_game">Корпоративные игры</a></li>
                                    <li><a href="#alert" class="alert">Магазин</a></li>
                                    <li><a href="{{ route('contacts') }}">Контакты</a></li>
                                </ul>
                            </div>
                            <div class="navbar-header hidden-lg hidden-md">             
                                <button type="button" class="navbar-toggle_menu mobile-mnu">
                                    <span class="sr-only">Открыть навигацию</span>
                                    <span class="icon-bars first"></span>
                                    <span class="icon-bars"></span>
                                    <span class="icon-bars"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <script type="text/javascript">
            $(".alert").click(function(){
                alert('Раздел В Стадии Разработки!')
            });
        </script>     