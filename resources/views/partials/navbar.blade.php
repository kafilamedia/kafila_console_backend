<nav class="navbar is-dark is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="container is-fluid">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <span class="icon is-medium">
                    <i class="fas fa-desktop"></i>
                </span>
                <span class="has-text-weight-bold">Ujian PSB Online</span>
            </a>

            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false"
                data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                @if (Auth::user()->hasRole(['admin']))
                    <a href="{{ route('admin.dashboard') }}" class="navbar-item">
                        <span class="icon">
                            <i class="fas fa-home"></i>
                        </span>
                        <span style="margin-left: 5px;">Beranda</span>
                    </a>

                    <a href="{{ route('admin.students.index') }}" class="navbar-item">
                        <span class="icon">
                            <i class="fas fa-users"></i>
                        </span>
                        <span style="margin-left: 5px;">Siswa</span>
					</a>

					<a href="{{ route('admin.cbt.index') }}" class="navbar-item">
                        <span class="icon">
                            <i class="fas fa-folder-open"></i>
                        </span>
                        <span style="margin-left: 5px;">Cbt</span>
                    </a>
                @endif

                @if (Auth::user()->hasRole(['siswa']))
                    <a href="{{ route('siswa.agreement.index') }}" class="navbar-item">
                        <span class="icon">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span style="margin-left: 5px;">Ujian</span>
                    </a>
                @endif
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        @if (Auth::guest())
                            <a class="button is-info">
                                <span class="icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </span>
                                <span>{{ __('Login') }}</span>
                            </a>
                        @endif
                        @if (Auth::check())
                            <a class="button">
                                <span class="icon">
                                    <i class="fas fa-user-circle"></i>
                                </span>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <a class="button is-danger" onclick="event.preventDefault();
  document.getElementById('logout-form').submit();">
                                <span class="icon">
                                    <i class="fas fa-sign-out-alt"></i>
                                </span>
                                <span>{{ __('Logout') }}</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
