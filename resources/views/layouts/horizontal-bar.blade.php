
<div class="horizontal-bar-wrap">
    <div class="header-topnav">
        <div class="container-fluid">
            <div class=" topnav rtl-ps-none" id="" data-perfect-scrollbar data-suppress-scroll-x="true">
                <ul class="menu float-left">
                    <li class="{{ request()->is('dashboard/*') ? 'active' : '' }}">

                        <div>


                            <div>
                                <label class="toggle" for="drop-2">

                                    Dashboard
                                </label>
                                <a href="#">
                                    <i class="nav-icon mr-2 i-Bar-Chart"></i>
                                    Dashboard
                                </a>

                                <input type="checkbox" id="drop-2">
                                <ul>

                                    <li class="nav-item ">
                                        <a class="{{ Route::currentRouteName()=='test' ? 'open' : '' }}" href="{{route('test')}}">
                                            <i class="nav-icon mr-2 i-Clock-3"></i>
                                            <span class="item-name">Version 1</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>

                </ul>


            </div>
        </div>
    </div>

</div>
<!--=============== Horizontal bar End ================-->
