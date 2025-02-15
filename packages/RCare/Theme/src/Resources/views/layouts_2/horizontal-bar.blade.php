<?php
if (Session::get('user_type') == 1) {
    $menu = mainMenus();
    $submenu = subMenus();
    $subsubmenu = "";
} else {
    $menu = orgmainMenus();
    $submenu = orgsubMenus();
    $subsubmenu = orgsubsubMenus();
}
?>

<style type="text/css">
    a .disabled-link {
        pointer-events: none;
    }

    a .disabled-link>li {
        color: black;
    }
</style>

<div class="horizontal-bar-wrap">
    <div class="header-topnav">
        <div class="container-fluid">
            <div class=" topnav rtl-ps-none" id="header-main-menu" data-perfect-scrollbar data-suppress-scroll-x="true">
                <ul class="menu float-left">
                    <!-- menu started -->
                    @foreach($menu as $mvalues)
                    <li>
                        <div>
                            <div>
                                <?php if (($mvalues['menu_url'] == '#') && ($mvalues['parent'] == '0')) { ?>
                                    <span class="toggle">{{$mvalues['menu']}}</span>
                                    <a href="#">
                                        <i class="nav-icon mr-2 {{$mvalues['icon']}}"></i>
                                        {{$mvalues['menu']}}
                                    </a>
                                <?php } ?>
                                <ul class="sub-menu">
                                    @foreach($submenu as $smvalue)
                                    <?php if ($mvalues['id'] == $smvalue['parent']) {
                                        if ($smvalue['menu_url'] == '#') {
                                            $class_name = 'disabled-link';
                                            $link = '#';
                                        } else {
                                            $class_name = 'enabled-link';
                                            $link = url('') . '/' . $smvalue['menu_url'];
                                        } ?>
                                        <li class="nav-item">
                                            <a class="{{ $class_name }} sub-menu-link" href="{{ $link }}">
                                                <i class="nav-icon mr-2  {{$smvalue['icon']}}"></i>
                                                <span class="item-name">{{$smvalue['menu']}}</span>
                                            </a>
                                            <?php if ((in_array($smvalue['id'], array_column($subsubmenu, 'parent')))) { ?>
                                                <div class="">
                                                    <ul class="sub-sub-menu">
                                                        <?php foreach ($subsubmenu as $ssmenu) {
                                                            if (($mvalues['id'] == $smvalue['parent']) && ($smvalue['id'] == $ssmenu['parent'])) { ?>
                                                                <li class="sub-nav-item">
                                                                    <a class="sub-sub-menu-link" href="{{ url('').'/'.$ssmenu['menu_url']}}">
                                                                        <i class="sub-nav-icon mr-2 {{$ssmenu['icon']}}"></i>
                                                                        <span class="sub-item-name">{{$ssmenu['menu']}}</span>
                                                                    </a>
                                                                </li>

                                                        <?php }
                                                        } ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </li>
                                    <?php  } ?>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</div>
<!--=============== Horizontal bar End ================-->