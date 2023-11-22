    <div class="main-header">
        <div class="logo">
            <img src="{{asset('assets/images/logo.png')}}" alt="">
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route("work.list") }}"> <img src="{{'/images/home.png'}}" class="home-icon d-sm-inline-block" alt="" aria-expanded="false" height='27'></a>
            <div class="form-row" style="color: #fff; background-color: transparent; font-weight: bold;">
                <?php
                $base_url = url('/');
                if ($base_url == "http://rcareproto2.d-insights.global") {
                    echo "<div class='ml-2'>|</div><div class='ml-2'>Dev Server</div>";
                } elseif ($base_url == "https://rcarestaging.d-insights.global") {
                    echo "<div class='ml-2'>|</div><div class='ml-2'>Staging Server</div>";
                }
                ?>
            </div>
        </div>
        <div style="margin: auto">@include('Theme::layouts_2.horizontal-bar')</div>
        <div class="header-part-right">
            <!-- Full screen toggle -->
            <a href="{{ route("message.log") }}"> <em class="nav-icon mr-2 i-Envelope"><mark class="message-notification">0</mark></em></a>
            <em class="i-Full-Screen header-icon text-muted d-sm-inline-block" data-fullscreen></em>
            <!-- User avatar dropdown -->
            <div class="dropdown">
                <div class="user col align-self-end">
                    @if((Session('profile_img') != NULL) && !empty(Session('profile_img')) && file_exists('/images/usersRcare/'.Session('profile_img')))
                    <img src="{{'/images/usersRcare/'.(Session('profile_img'))}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @else
                    <img src="{{asset('assets/images/faces/avatar.png')}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @endif

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-header">
                            <em class="i-Lock-User mr-1" style="color:#69aac2 !important;"></em> <?php echo Session::get('f_name') . ' ' . Session::get('l_name'); ?>
                        </div>
                        <em class="i-Over-Time" style="color: #3f829a;padding-left: 23px;"></em><span class="cmtotaltimespent" data-toggle="tooltip" data-placement="right" title="" data-original-title="Total Time spent/Total No. of patients" style="margin-left: 8px; "></span>
                        <a class="dropdown-item" href="{{route('logout')}}" id="sign-out-btn">Sign out</a>
                        @if( (Session('parentrole') != NULL) && !empty(Session('parentrole')) && (Session('role') != '6'))
                        <a href="#" title="Login As Parentdeveloper">
                            <button type="button" id="loginasparentdeveloper" class="btn btn-primary" onclick="util.loginAction('<?php echo Session::get('parentuserid') ?>')">
                                Login As ParentDeveloper</button></a>
                        @endif

                        <label class="checkbox checkbox-primary" style="margin-left: 19px;">
                            <input type="checkbox" name="test" id="dark-checkbox" <?php if ($themeMode == 'dark-theme') {
                                                                                        echo "checked";
                                                                                    } ?>>
                            <span>Enable Dark Mode</span>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- header top menu end -->