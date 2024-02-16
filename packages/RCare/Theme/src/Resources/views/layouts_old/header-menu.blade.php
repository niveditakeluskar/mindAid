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

            <a href="{{ route("org_users_list") }}"> <img src="{{'/images/home.png'}}" class="home-icon d-sm-inline-block" alt="" aria-expanded="false" height='27'></a>
				<!-- <a href='<?php //echo url('').'/dashboard'; ?>'> <img src="{{'/images/home.png'}}" class="home-icon d-sm-inline-block" alt="" aria-expanded="false" height='27'></a> -->
                <!-- Mega menu 
                <div class="dropdown mega-menu d-none d-md-block">
                    <a href="#" class="btn text-muted dropdown-toggle mr-3" id="dropdownMegaMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mega Menu</a>
                    <div class="dropdown-menu text-left" aria-labelledby="dropdownMenuButton">
                        <div class="row m-0">
                            <div class="col-md-4 p-4 bg-img">
                                <h2 class="title">Mega Menu <br> Sidebar</h2>
                                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Asperiores natus laboriosam fugit, consequatur.
                                </p>
                                <p class="mb-4">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Exercitationem odio amet eos dolore suscipit placeat.</p>
                                <button class="btn btn-lg btn-rounded btn-outline-warning">Learn More</button>
                            </div>
                            <div class="col-md-4 p-4">
                                <p class="text-primary text--cap border-bottom-primary d-inline-block">Features</p>
                                <div class="menu-icon-grid w-auto p-0">
                                    <a href="#"><i class="i-Shop-4"></i> Home</a>
                                    <a href="#"><i class="i-Library"></i> UI Kits</a>
                                    <a href="#"><i class="i-Drop"></i> Apps</a>
                                    <a href="#"><i class="i-File-Clipboard-File--Text"></i> Forms</a>
                                    <a href="#"><i class="i-Checked-User"></i> Sessions</a>
                                    <a href="#"><i class="i-Ambulance"></i> Support</a>
                                </div>
                            </div>
                                 <div class="col-md-4 p-4">
                                <p class="text-primary text--cap border-bottom-primary d-inline-block">Components</p>
                                <ul class="links">
                                    <li><a href="accordion.html">Accordion</a></li>
                                    <li><a href="alerts.html">Alerts</a></li>
                                    <li><a href="buttons.html">Buttons</a></li>
                                    <li><a href="badges.html">Badges</a></li>
                                    <li><a href="carousel.html">Carousels</a></li>
                                    <li><a href="lists.html">Lists</a></li>
                                    <li><a href="popover.html">Popover</a></li>
                                    <li><a href="tables.html">Tables</a></li>
                                    <li><a href="datatables.html">Datatables</a></li>
                                    <li><a href="modals.html">Modals</a></li>
                                    <li><a href="nouislider.html">Sliders</a></li>
                                    <li><a href="tabs.html">Tabs</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!- / Mega menu 
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <i class="search-icon text-muted i-Magnifi-Glass1"></i>
                </div>-->
            </div>

            <div style="margin: auto"></div>

            <div class="header-part-right">
				 
                <!-- Full screen toggle -->
                <i class="i-Full-Screen header-icon text-muted d-sm-inline-block" data-fullscreen></i>
                <!-- Grid menu Dropdown -->
<!--                 <div class="dropdown widget_dropdown">
                    <i class="i-Safe-Box text-muted header-icon" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class="menu-icon-grid">
                            <a href="#"><i class="i-Shop-4"></i> Home</a>
                            <a href="#"><i class="i-Library"></i> UI Kits</a>
                            <a href="#"><i class="i-Drop"></i> Apps</a>
                            <a href="#"><i class="i-File-Clipboard-File--Text"></i> Forms</a>
                            <a href="#"><i class="i-Checked-User"></i> Sessions</a>
                            <a href="#"><i class="i-Ambulance"></i> Support</a>
                        </div>
                    </div>
                </div> -->
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
                <div class="dropdown">
                    <div  class="user col align-self-end">
                        @if((Session('profile_img') !== NULL) && !empty(Session('profile_img')) && file_exists('/images/usersRcare/'.Session('profile_img')))
                            <img src="{{'/images/usersRcare/'.(Session('profile_img'))}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @else
                            <img src="{{asset('assets/images/faces/avatar.png')}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        @endif
                       
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <i class="i-Lock-User mr-1 text-muted"></i> <?php echo Session::get('f_name'). ' '.Session::get('l_name') ; ?>
                            </div>
                            <!-- <a class="dropdown-item">Account settings</a> -->
                            <!-- <a class="dropdown-item">Billing history</a> -->
                            <a class="dropdown-item" href="{{route('logout')}}">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- header top menu end -->
