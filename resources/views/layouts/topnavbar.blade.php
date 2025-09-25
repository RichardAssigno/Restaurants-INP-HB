<header class="topnavbar-wrapper">
    <!-- START Top Navbar-->
    <nav class="navbar topnavbar">
        <!-- START navbar header-->
        <div class="navbar-header"><a class="navbar-brand" href="#/">

                <div class="brand-logo"><img class="img-fluid" src="{{asset("assets/img/logo7069.png")}}" alt="App Logo"></div>
                <div class="brand-logo-collapsed"><img class="img-fluid" src="{{asset("assets/img/logo7069.png")}}" alt="App Logo"></div>


            </a></div><!-- END navbar header-->
        <!-- START Left navbar-->
        <ul class="navbar-nav mr-auto flex-row">
            <li class="nav-item">
                <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops--><a class="nav-link d-none d-md-block d-lg-block d-xl-block" href="#" data-trigger-resize="" data-toggle-state="aside-collapsed"><em class="fas fa-bars"></em></a><!-- Button to show/hide the sidebar on mobile. Visible on mobile only.--><a class="nav-link sidebar-toggle d-md-none" href="#" data-toggle-state="aside-toggled" data-no-persist="true"><em class="fas fa-bars"></em></a>
            </li><!-- START User avatar toggle-->
            <li class="nav-item d-none d-md-block">
                <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops--><a class="nav-link" id="user-block-toggle" href="#user-block" data-toggle="collapse"><em class="icon-user"></em></a>
            </li><!-- END User avatar toggle-->
            <!-- START lock screen-->
            {{--<li class="nav-item d-none d-md-block"><a class="nav-link" href="" title="Lock screen"><em class="icon-lock"></em></a></li>--}}<!-- END lock screen-->
        </ul><!-- END Left navbar-->
        <!-- START Right Navbar-->
        <ul class="navbar-nav flex-row">
            <!-- Search icon-->
            <li class="nav-item"><a class="nav-link" href="#" data-search-open=""><em class="icon-magnifier"></em></a></li><!-- Fullscreen (only desktops)-->
            <li class="nav-item d-none d-md-block"><a class="nav-link" href="#" data-toggle-fullscreen=""><em class="fas fa-expand"></em></a></li><!-- START Alert menu-->
            {{--<li class="nav-item dropdown dropdown-list"><a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-toggle="dropdown"><em class="icon-bell"></em><span class="badge badge-danger">11</span></a><!-- START Dropdown menu-->
                <div class="dropdown-menu dropdown-menu-right animated flipInX">
                    <div class="dropdown-item">
                        <!-- START list group-->
                        <div class="list-group">
                            <!-- list item-->
                            <div class="list-group-item list-group-item-action">
                                <div class="media">
                                    <div class="align-self-start mr-2"><em class="fab fa-twitter fa-2x text-info"></em></div>
                                    <div class="media-body">
                                        <p class="m-0">New followers</p>
                                        <p class="m-0 text-muted text-sm">1 new follower</p>
                                    </div>
                                </div>
                            </div><!-- list item-->
                            <div class="list-group-item list-group-item-action">
                                <div class="media">
                                    <div class="align-self-start mr-2"><em class="fas fa-envelope fa-2x text-warning"></em></div>
                                    <div class="media-body">
                                        <p class="m-0">New e-mails</p>
                                        <p class="m-0 text-muted text-sm">You have 10 new emails</p>
                                    </div>
                                </div>
                            </div><!-- list item-->
                            <div class="list-group-item list-group-item-action">
                                <div class="media">
                                    <div class="align-self-start mr-2"><em class="fas fa-tasks fa-2x text-success"></em></div>
                                    <div class="media-body">
                                        <p class="m-0">Pending Tasks</p>
                                        <p class="m-0 text-muted text-sm">11 pending task</p>
                                    </div>
                                </div>
                            </div><!-- last list item-->
                            <div class="list-group-item list-group-item-action"><span class="d-flex align-items-center"><span class="text-sm">More notifications</span><span class="badge badge-danger ml-auto">14</span></span></div>
                        </div><!-- END list group-->
                    </div>
                </div><!-- END Dropdown menu-->
            </li>--}}<!-- END Alert menu-->

            <!-- START Offsidebar button-->
            <li class="nav-item"><a class="nav-link" href="#" data-toggle-state="offsidebar-open" data-no-persist="true"><em class="icon-settings"></em></a></li><!-- END Offsidebar menu-->

            <li class="nav-item dropdown dropdown-list ">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret " href="#" data-toggle="dropdown">
                    <span class="badge badge-soft-light">
                        <!-- User picture-->
                            <div class="user-block-picture">
                                <div class="user-block-status">
                                    <img class="img-thumbnail rounded-circle" src="{{asset("assets/img/user/02.jpg")}}" alt="Avatar" width="60" height="60">
                                </div>
                            </div>

                    </span></a><!-- START Dropdown menu-->
                <div class="dropdown-menu dropdown-menu-right animated flipInX">
                    <div class="dropdown-item">
                        <!-- START list group-->
                        <div class="list-group">
                            <!-- list item-->
                            <div class="list-group-item list-group-item-action no-border">
                                <div class="media">
                                    <a  class="text-decoration-none link-custom media" href="">
                                        <div class="align-self-start mr-2"><i class="fas fa-user-alt  text-black-50"></i></div>
                                        <div class="media-body">
                                            <p class="m-0">Mon compte</p>
                                        </div>
                                    </a>
                                </div>
                            </div><!-- list item-->

                            <!-- list item-->
                            <div class="list-group-item list-group-item-action no-border">
                                <div class="media">
                                    <a  class="text-decoration-none link-custom media" href="">
                                        <div class="align-self-start mr-2"><i class="fas fa-lock  text-black-50"></i></div>
                                        <div class="media-body">
                                            <p class="m-0">Verrouiller mon écran</p>
                                        </div>
                                    </a>
                                </div>
                            </div><!-- list item-->

                            <!-- list item-->
                            <div class="list-group-item list-group-item-action no-border">
                                <div class="media">
                                    <a  class="text-decoration-none link-custom media" class="dropdown-item " href="javascript:void();"
                                        onclick="event.preventDefault(); document.getElementById('logout').submit();">
                                        <div class="align-self-start mr-2"><i class="fas fa-sign-out-alt  text-black-50"></i></div>
                                        <div class="media-body">
                                            <p class="m-0">Me déconnecter</p>
                                        </div>
                                    </a>
                                    <form id="logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div><!-- list item-->

                        </div><!-- END list group-->
                    </div>
                </div><!-- END Dropdown menu-->
            </li><!-- END user profil-->
        </ul><!-- END Right Navbar-->
        <!-- START Search form-->
        <form class="navbar-form" role="search" action="javascript:void(0);">
            <div class="form-group text-center">
                <input class="form-control text-center" type="text" placeholder="Saisir le texte ici pour rechercher ...">
                <div class="fas fa-times navbar-form-close" data-search-dismiss=""></div>
            </div>
        </form>

    </nav><!-- END Top Navbar-->




</header>


<div class="row mb-2 resultat text-center" style="display: none">
    <div class="container" id="resultat"></div>
</div>



