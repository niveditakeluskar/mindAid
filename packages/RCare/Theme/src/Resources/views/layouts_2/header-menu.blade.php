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
            <a href="{{ route("work.list") }}"> <img src="{{'/images/home.png'}}" class="home-icon d-sm-inline-block"
             alt="" aria-expanded="false" height='27'></a>
             <div class="form-row" style="color: #fff; background-color: transparent; font-weight: bold;">
                <?php 
                    $base_url = url('/');
                    if($base_url == "http://rcareproto2.d-insights.global") {
                        echo "<div class='ml-2'>|</div><div class='ml-2'>Dev Server</div>";
                    } elseif($base_url == "https://rcarestaging.d-insights.global") {
                        echo "<div class='ml-2'>|</div><div class='ml-2'>Staging Server</div>";
                    }
                ?>
             </div>
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
                                    <a href="#"><em class="i-Shop-4"></em> Home</a>
                                    <a href="#"><em class="i-Library"></em> UI Kits</a>
                                    <a href="#"><em class="i-Drop"></em> Apps</a>
                                    <a href="#"><em class="i-File-Clipboard-File--Text"></em> Forms</a>
                                    <a href="#"><em class="i-Checked-User"></em> Sessions</a>
                                    <a href="#"><em class="i-Ambulance"></em> Support</a>
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
                    <em class="search-icon text-muted i-Magnifi-Glass1"></em>
                </div>-->
            </div>

            <div style="margin: auto">@include('Theme::layouts_2.horizontal-bar')</div>

            <div class="header-part-right">
				 
                <!-- Full screen toggle -->
                <a href="{{ route("message.log") }}"> <em class="nav-icon mr-2 i-Envelope"><mark class="message-notification">0</mark></em></a>
                <em class="i-Full-Screen header-icon text-muted d-sm-inline-block" data-fullscreen></em>
                <!-- Grid menu Dropdown -->
<!--                 <div class="dropdown widget_dropdown">
                    <em class="i-Safe-Box text-muted header-icon" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></em>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class="menu-icon-grid">
                            <a href="#"><em class="i-Shop-4"></em> Home</a>
                            <a href="#"><em class="i-Library"></em> UI Kits</a>
                            <a href="#"><em class="i-Drop"></em> Apps</a>
                            <a href="#"><em class="i-File-Clipboard-File--Text"></em> Forms</a>
                            <a href="#"><em class="i-Checked-User"></em> Sessions</a>
                            <a href="#"><em class="i-Ambulance"></em> Support</a>
                        </div>
                    </div>
                </div> -->
                <!-- Notificaiton -->
                <!-- <div class="dropdown">
                    <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="badge badge-primary">3</span>
                        <em class="i-Bell text-muted header-icon"></em>
                    </div> -->
                    <!-- Notification dropdown -->
                    <!-- <div class="dropdown-menu dropdown-menu-right notification-dropdown rtl-ps-none" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">
                        <div class="dropdown-item d-flex">
                            <div class="notification-icon">
                                <em class="i-Speach-Bubble-6 text-primary mr-1"></em>
                            </div>
                            <div class="notification-details flex-grow-1">
                                <p class="m-0 d-flex align-items-center">
                                    <span>New message</span>
                                    <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                                    <span class="flex-grow-1"></span> -->
                                    <!-- <span class="text-small text-muted ml-auto">10 sec ago</span>-->
                                <!-- </p>
                                <p class="text-small text-muted m-0">Welcome to RCare</p>
                            </div>
                        </div>
                     </div>
                </div> -->
                <!-- Notificaiton End -->
				
                <!-- User avatar dropdown -->
                <div class="dropdown">
                    <div  class="user col align-self-end">
                        @if((Session('profile_img') != NULL) && !empty(Session('profile_img')) && file_exists('/images/usersRcare/'.Session('profile_img')))
                            <img src="{{'/images/usersRcare/'.(Session('profile_img'))}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @else
                            <img src="{{asset('assets/images/faces/avatar.png')}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        @endif
                       
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <em class="i-Lock-User mr-1" style="color:#69aac2 !important;"></em> <?php echo Session::get('f_name'). ' '.Session::get('l_name') ; ?>
                                
                            </div>
                             <em class="i-Over-Time" style="color: #3f829a;padding-left: 23px;"></em><label for="programs" class="cmtotaltimespent" data-toggle="tooltip" data-placement="right" title="" data-original-title="Total Time spent/Total No. of patients" style="margin-left: 8px; "></label>
                           
                            <!-- <a class="dropdown-item">Billing history</a> -->
                            <a class="dropdown-item" href="{{route('logout')}}" id="sign-out-btn">Sign out</a>
                            @if( (Session('parentrole') != NULL) && !empty(Session('parentrole'))  && (Session('role') != '6'))  
                            <a href="#" title="Login As Parentdeveloper">
                                <button type="button" id ="loginasparentdeveloper"class="btn btn-primary" 
                                onclick="util.loginAction('<?php echo Session::get('parentuserid')?>')">
                                Login As ParentDeveloper</button></a>      
                            @endif
                           
                            <label class="checkbox checkbox-primary" style="margin-left: 19px;">    
                                <input type="checkbox" name="test" 
								id="dark-checkbox" <?php  if($themeMode == 'dark-theme'){echo "checked"; } ?>> 
                                <span>Enable Dark Mode</span>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- header top menu end -->
