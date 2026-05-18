<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative">
        <a href="#" class="d-block text-decoration-none position-relative">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="logo-icon">
            <span class="logo-text fw-bold text-dark">Trezo</span>
        </a>
        <button class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y" id="sidebar-burger-menu">
            <i data-feather="x"></i>
        </button>
    </div>

    <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
        <ul class="menu-inner">
            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">MAIN</span>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">dashboard</span>
                    <span class="title">Dashboard</span>
                    <span class="count">30</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="#" class="menu-link">eCommerce</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">CRM <span class="new tag">Hot</span></a>
                    </li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Front Pages</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item"><a href="#" class="menu-link">Home</a></li>
                    <li class="menu-item"><a href="#" class="menu-link">Features</a></li>
                    <li class="menu-item"><a href="#" class="menu-link">Our Team</a></li>
                    <li class="menu-item"><a href="#" class="menu-link">FAQ's</a></li>
                    <li class="menu-item"><a href="#" class="menu-link">Contact</a></li>
                </ul>
            </li>

            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">APPS</span>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">format_list_bulleted</span>
                    <span class="title">To Do List</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">date_range</span>
                    <span class="title">Calendar</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{route('manage.kategorite')}}" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">contact_page</span>
                    <span class="title">Kategorite</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{route('manage.stervitjen')}}" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">chat</span>
                    <span class="title">Stervitja</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">mail</span>
                    <span class="title">Email</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item"><a href="#" class="menu-link">Inbox</a></li>
                    <li class="menu-item"><a href="#" class="menu-link">Compose</a></li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="{{--{{route('manage.furnitoret')}}--}}" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">team_dashboard</span>
                    <span class="title">Furnitoret</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">folder_open</span>
                    <span class="title">File Manager</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item"><a href="#" class="menu-link">My Drive</a></li>
                    <li class="menu-item"><a href="#" class="menu-link">Assets</a></li>
                </ul>
            </li>

            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">PAGES</span>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">shopping_cart</span>
                    <span class="title">eCommerce</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item"><a href="#" class="menu-link">Products Grid</a></li>
                    <li class="menu-item"><a href="#" class="menu-link">Products List</a></li>
                </ul>
            </li>

            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">OTHERS</span>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">account_circle</span>
                    <span class="title">My Profile</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">settings</span>
                    <span class="title">Settings</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item"><a href="#" class="menu-link">Account Settings</a></li>
                    <li class="menu-item"><a href="#" class="menu-link">Change Password</a></li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                    <span class="material-symbols-outlined menu-icon">logout</span>
                    <span class="title">Logout</span>
                </a>
                <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
</div>
