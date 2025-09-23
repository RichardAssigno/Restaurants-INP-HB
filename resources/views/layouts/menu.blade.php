<aside class="aside-container">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
        <nav class="sidebar" data-sidebar-anyclick-close="">
            <!-- START sidebar nav-->
            <ul class="sidebar-nav">

                <!-- START user info-->
                <li class="has-user-block">
                    <div class="collapse" id="user-block">
                        <div class="item user-block">
                            <!-- User picture-->
                            <div class="user-block-picture">
                                <div class="user-block-status"><img class="img-thumbnail rounded-circle"
                                                                    src="{{asset("assets/img/user/02.jpg")}}"
                                                                    alt="Avatar" width="60" height="60">
                                    <div class="circle bg-success circle-lg"></div>
                                </div>
                            </div><!-- Name and Job-->
                            @php $user = session()->get("admin") @endphp
                            <div class="user-block-info">
                                <span
                                    class="user-block-name">{{ $user->nom ?? 'Invité' }} {{ $user->prenoms ?? 'Invité' }}</span>
                                <span class="user-block-role">{{ $user->email ?? 'Invité' }}</span></div>
                        </div>
                    </div>
                </li><!-- END user info-->


                <!-- Iterates over all sidebar items-->
                <li class="nav-heading "><span data-localize="sidebar.heading.HEADER">Menu de Navigation</span></li>

                <li class=" "><a href="{{route("tableaudebord.index")}}" title="Tableau de bord" >
                        <em class="icon-speedometer"></em><span
                            data-localize="sidebar.nav.DASHBOARD">Tableau de bord</span>
                    </a>

                </li>

                @canany(['Utilisateurs.Voir les Administrateurs', 'Utilisateurs.Voir les Permissions', 'Utilisateurs.Voir les Roles'])
                    <li class=" "><a href="#user" title="Utilisateurs" data-toggle="collapse">
                            <em class="icon-user"></em><span data-localize="sidebar.nav.USERS">Utilisateurs</span>
                        </a>
                        <ul class="sidebar-nav sidebar-subnav collapse " id="user">
                            <li class="sidebar-subnav-header">Utilisateurs</li>
                                @can("Utilisateurs.Voir les Administrateurs")
                                <li class=" "><a href="{{route("comptes.index")}}" title="aministrateurs"><span data-localize="sidebar.nav.ADMINS">Aministrateurs</span></a></li>
                            @endcan
                            @can("Utilisateurs.Voir les Permissions")
                                <li class=" "><a href="{{route("roles.index")}}" title="roles"><span data-localize="sidebar.nav.ROLES">Roles</span></a></li>
                            @endcan
                            @can("Utilisateurs.Voir les Roles")
                                <li class=" "><a href="{{route("permissions.index")}}" title="autorisations"><span data-localize="sidebar.nav.AUTORISATIONS">Autorisations</span></a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <li class=" "><a href="{{route("compterestau.index")}}" title="Compte Restaurant" >
                        <em class="icon-speedometer"></em><span
                            data-localize="sidebar.nav.COMPTE">Compte Restaurant</span>
                    </a>

                </li>

                @can("Facturations.Voir les Facturations")
                    <li class=" "><a href="{{route("facturations.index")}}" title="Facturation">
                            <em class="fa fa-gavel"></em><span data-localize="sidebar.nav.JURY">Facturation</span>
                        </a>
                    </li>
                @endcan

                @canany(['Directeurs d\'Ecole', 'Sous-Directeurs des Etudes', 'Sous-Directeurs des Cours', 'Inspecteurs de Filières'])
                    <li class=" ">
                        <a href="#responsabilites" title="Gestion des Responsabilité" data-toggle="collapse"><em
                                class="fas fa-graduation-cap"></em><span>Responsabilités</span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="responsabilites">
                            <li class="sidebar-subnav-header">Attribution</li>
                            @can('Attribution Responsable')
                                <li class=" "><a href="{{route('responsabilites.index')}}"
                                                 title="Attribution de responsabilité dans l'école"><span>Attribution</span></a>
                                </li>
                            @endcan
                            @can('Liste responsables')
                                <li class=" "><a href="{{route("responsabilites.listeresponsabiltepersonne")}}"
                                                 title="Mettre fin à la responsabilité dans l'école"><span>Fin Responsabilité</span></a>
                                </li>
                            @endcan

                        </ul>
                    </li>

                @endcanany
                @canany(['Attribution de cours','Bilan enseignements', 'Gestion des séances', 'Emplois du temps', 'Bilan des séances', 'Decharges', 'Suivi par période'])

                    <li class=" ">
                        <a href="#enseignement" title="Gestion des Bourses" data-toggle="collapse"><em
                                class="fas fa-book-open"></em><span>Enseignements</span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="enseignement">
                            <li class="sidebar-subnav-header">Gerer les enseignements</li>
                            @can('Attribution de cours')
                                <li class=" "><a href="{{route("gestionsCours.index")}}"
                                                 title="Gestion des cours"><span>Attribution de cours</span></a>
                                </li>
                            @endcan
                            @can('Gestions de cours')
                                <li class=" "><a href="{{route("editionsCours.index")}}" title="Edition des cours"><span>Edition de cours</span></a>

                            @endcan
                            @can('Emplois du temps')
                                <li class=" "><a href="{{route("edt.index")}}" title="Emplois du temps"><span>Emplois du temps</span></a>
                                </li>
                            @endcan
                            @can('Bilan des séances')
                                <li class=" "><a href="{{ route("bilanSeances.index") }}" title="Bilan des séances"><span>Bilan des séances</span></a>
                                </li>
                            @endcan
                            @can('Decharges')
                                <li class=" "><a href="{{route("decharges.index")}}" title="Decharges"><span>Decharges</span></a></li>
                            @endcan
                            @can('Suivi par période')
                                <li class=" "><a href="{{route("suiviparperiode.index")}}" title="Suivi par periode"><span>Suivi par période</span></a>
                                </li>
                            @endcan


                        </ul>
                    </li>

                @endcanany
                @canany(['Nouveau stage','liste soutenances','liste stages','stages liste'])

                    <li class=" ">
                        <a href="#stages" title="Gestion des stages" data-toggle="collapse"><em
                                class="fas fa-book-open"></em><span>Stages </span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="stages">
                            <li class="sidebar-subnav-header">Gerer stages</li>
                            @can('Nouveau stage')
                                <li class=" ">
                                    <a href="{{route("stages.index")}}"
                                       title="Gestion des stages">
                                        <span>Ajout de stage</span>
                                    </a>
                                </li>
                            @endcan

                            @can('liste stages')
                                <li class=" ">
                                    <a href="{{route('stages.listestages')}}"
                                       title="Liste des stages"><span>Liste stages</span>
                                    </a>
                                </li>
                            @endcan

                            @can('liste soutenances')
                                <li class=" ">
                                    <a href="{{route("stages.listesoutenances")}}"
                                       title="Gestion des stages">
                                        <span>Liste des soutenances</span>
                                    </a>
                                </li>
                            @endcan


                        </ul>
                    </li>

                @endcanany
                @canany(['Gestion des modules','Version de Programme', 'Liste des Programmes', 'Nouveau Programme', ])

                    <li class=" ">
                        <a href="#programmes" title="Gestion des Bourses" data-toggle="collapse"><em
                                class="fas fa-pen"></em><span>Programmes</span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="programmes">
                            <li class="sidebar-subnav-header">Programmes</li>
                            @can('Nouveau Programme')

                                <li class=" "><a href="{{route("nouveauProgramme.index")}}"
                                                 title="Nouvel étudiant"><span>Nouveau Programme</span></a></li>

                            @endcan
                            @can('Liste des Programmes')

                                <li class=" "><a href="{{route("nouveauProgramme.listeProgramme")}}"
                                                 title="Nouvel étudiant"><span>Liste des Programmes</span></a></li>

                            @endcan
                            @can('Version de Programme')
                                <li class=" "><a href="/" title="Version de Programme"><span>Version de Programme</span></a>
                                </li>
                            @endcan
                            @can('Gestion des modules')
                                <li class=" "><a href="/"
                                                 title="Gestion des modules"><span>Gestion des modules</span></a></li>
                            @endcan

                        </ul>
                    </li>

                @endcanany

                @canany(['Classes', 'Affectations', 'Délégués de classe'])
                    <li class=" ">
                        <a href="#classes" title="Gestion des Bourses" data-toggle="collapse"><em
                                class="fas fa-chalkboard-teacher"></em><span>Gestion des Classes</span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="classes">
                            <li class="sidebar-subnav-header">Gestion des classes</li>
                            @can('Classes')
                                <li class=" "><a href="{{ route('classes.index')}}" title="Ajouter une Nouvelle classe"><span>Classes</span></a>
                                </li>
                            @endcan
                            @can('Listes')
                                <li class=" "><a href="{{ route('classes.listes')}}" title="Afficher les listes"><span>Liste</span></a>
                                </li>
                            @endcan
                            @can('Affectations')
                                <li class=" "><a href="{{ route('repartitionetudiants.index')}}"
                                                 title="Affecter des étudiants à la classe"><span>Affectations</span></a>
                                </li>
                            @endcan
                            @can('Délégués de classe')
                                <li class=" "><a href="{{route('repartitionetudiants.affectationchef')}}"
                                                 title="Ajouter des délégués de classe"><span>Délégués de classe</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>

                @endcan
            </ul><!-- END sidebar nav-->
        </nav>
    </div><!-- END Sidebar (left)-->
</aside><!-- offsidebar-->
