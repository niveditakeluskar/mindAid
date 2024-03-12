<?php 
    // $menu = App\Models\Menus::where('parent','=',0)->orderBy('sequence','asc')->get(); 

    // $menu = DB::table('rcare_menu_master')->where('parent', '=', '0')
    // ->where('status', '=', '1')
    // ->whereIn('service_id',function($query){$query->select('service_id')->from('rcare_role_services')->where('role_id', '=','35')
    // ->where('crud', 'LIKE', '%r%');
    // })
    // ->orderBy('sequence','asc')->get();

    $menu = mainMenus();
     print_r($menu);
?>
<?php $submenu = App\Models\Menus::where('parent','!=',0)->get(); ?>

<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul class="navigation-left">
            <!--li class="nav-item {{ request()->is('dashboard/*') ? 'active' : '' }}" data-item="dashboard">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <div class="triangle"></div>
            </li-->
            @foreach( $menu as $mvalues)
           <!-- {{ $mvalues }} -->
            <li class="nav-item {{ request()->is($mvalues['menu_url']) ? 'active' : '' }}  <?php if(request()->is('addorgs') && $mvalues['menu_url'] =='rcareorgs'){ echo "active"; } ?>" data-item="{{ $mvalues['menu'] }}">
                <a class="nav-item-hold <?php if(strpos(request()->is($mvalues['menu_url']),'#') !== false){ echo 'addmenus'; } ?>" href="{{$mvalues['menu_url']}}">
                    <i class="nav-icon {{$mvalues['icon']}}"></i>
                    <span class="nav-text">{{$mvalues['menu']}}</span>
                </a>
                <div class="triangle"></div>
            </li>
            @endforeach
        </ul>
    </div>


    <div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <!-- Submenu Dashboards -->
        
        @foreach($menu as $mvalue)
        <!-- {{ $mvalues }} -->
        <ul class="childNav" data-parent="{{ $mvalue['menu'] }}">
            @foreach($submenu as $smvalue)
            <?php if($mvalue->id == $smvalue->parent){ ?>
                <li class="nav-item ">
                    <a class="{{ Route::currentRouteName()==$smvalue->menu_url ? 'open' : '' }}"
                        href="{{ $smvalue->menu_url }}">
                        <i class="nav-icon i-Clock-3"></i>
                        <span class="item-name">{{ $smvalue->menu }}</span>
                    </a>
                </li>
            <?php  } ?>
            @endforeach
        </ul>
        @endforeach
    </div>
    <div class="sidebar-overlay"></div>
</div>
<!--=============== Left side End ================-->