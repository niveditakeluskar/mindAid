<?php 
    if(Session::get('user_type')==1) {
        $menu = mainMenus();
        $submenu = subMenus();
    } else {
        $menu = orgmainMenus();
        $submenu = orgsubMenus();
    }
?>

<div class="horizontal-bar-wrap">
    <div class="header-topnav">
        <div class="container-fluid">
            <div class=" topnav rtl-ps-none" id="" data-perfect-scrollbar data-suppress-scroll-x="true">
                <ul class="menu float-left">
                    <!-- menu started -->
                    @foreach( $menu as $mvalues)
                    <li class="{{ request()->is($mvalues['menu_url']) ? 'active' : '' }} <?php if(request()->is('addorgs') && $mvalues['menu_url'] =='rcareorgs'){ echo "active"; } ?>">
                        <div>
                            <div>
                                <label class="toggle" for="drop-2">
                                    {{$mvalues['menu']}}
                                </label>
                                <a href="<?php echo url('').'/'; ?>{{$mvalues['menu_url']}}">
                                    <i class="nav-icon mr-2 {{$mvalues['icon']}}"></i> {{$mvalues['menu']}}
                                </a><input type="checkbox" id="drop-2">
                                <!-- sub menu started -->
                                <ul>
                                    @foreach($submenu as $smvalue)
                                    <?php if($mvalues['id'] == $smvalue['parent']){ ?>
                                    <li class="nav-item">
                                        <a class="{{ Route::currentRouteName()==$smvalue['menu_url'] ? 'open' : '' }} <?php if($smvalue['menu_url']=='menu#ajaxModel1'){echo 'addmenus';} else if($smvalue['menu_url']=='users#ajaxModel1'){echo 'addusers';} else if($smvalue['menu_url']=='roles#ajaxModel1'){echo 'addroles';} ?>"
                                        href="<?php echo url('').'/'; ?>{{ $smvalue['menu_url'] }}"    
                                        >
                                            <i class="nav-icon mr-2  {{$smvalue['icon']}}"></i>
                                            <span class="item-name">{{$smvalue['menu']}}</span>
                                        </a>
                                    </li>
                                    <?php  } ?>
                                    @endforeach
                                </ul>
                                <!-- end sub menu -->
                            </div>
                        </div>
                    </li>
                    @endforeach
                    <!-- end menu -->
                </ul>
            </div>
        </div>
    </div>

</div>
<!--=============== Horizontal bar End ================-->