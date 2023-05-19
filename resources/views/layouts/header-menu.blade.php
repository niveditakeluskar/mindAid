    <div class="main-header">
            <div class="logo">
                <img src="{{asset('assets/images/logo.png')}}" alt="">
            </div>

            <div class="menu-toggle">
                <div></div>
                        <div></div>
                <div></div>
            </div>			

            <div class="d-flex align-items-center">
				<a href='<?php echo url('').'/dashboard'; ?>'> <img src="{{'/images/home.png'}}" class="home-icon d-sm-inline-block" alt="" aria-expanded="false" height='27'></a>
            </div>

            <div style="margin: auto"></div>

            <div class="header-part-right">	 
                <!-- Full screen toggle -->
                <i class="i-Full-Screen header-icon text-muted d-sm-inline-block" data-fullscreen></i>
                <!-- Grid menu Dropdown -->
                <!-- Notificaiton -->
                <div class="dropdown">
                    <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="badge badge-primary">3</span>
                        <i class="i-Bell text-muted header-icon"></i>
                    </div>
                    <!-- Notification dropdown -->
                    <div class="dropdown-menu dropdown-menu-right notification-dropdown rtl-ps-none" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">
                        <div class="dropdown-item d-flex">
                            <div class="notification-icon">
                                <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                            </div>
                            <div class="notification-details flex-grow-1">
                                <p class="m-0 d-flex align-items-center">
                                    <span>New message</span>
                                    <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                                    <span class="flex-grow-1"></span>
                                    <!-- <span class="text-small text-muted ml-auto">10 sec ago</span>-->
                                </p>
                                <p class="text-small text-muted m-0">Welcome to RCare</p>
                            </div>
                        </div>
                     </div>
                </div>
                <!-- Notificaiton End -->
				
                <!-- User avatar dropdown -->
                <!-- <div class="dropdown">
                    <div  class="user col align-self-end">
                        @if((Session('profile_img') !== NULL) && !empty(Session('profile_img')))
                            <img src="{{'/images/usersRcare/'.(Session('profile_img'))}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @else
                            <img src="{{asset('assets/images/faces/avatar.png')}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        @endif
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <i class="i-Lock-User mr-1 text-muted"></i> <?php echo Session::get('f_name'). ' '.Session::get('l_name') ; ?>
                            </div>
                            <!-- <a class="dropdown-item">Account settings</a> -->
                            <!-- <a class="dropdown-item">Billing history</a> - ->
                            <a class="dropdown-item" href="{{route('logout')}}">Sign out</a>
                        </div>
                    </div>
                </div> -->
                <!-- User avatar dropdown -->
                <div class="dropdown">
                    <div  class="user col align-self-end">
                        <img src="{{asset('assets/images/faces/1.jpg')}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <i class="i-Lock-User mr-1"></i> Timothy Carlson
                            </div>
                            <a class="dropdown-item">Account settings</a>
                            <a class="dropdown-item">Billing history</a>
                            <a class="dropdown-item" href="{{route('logout')}}">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- header top menu end -->
