<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand" href="#">{{ $titlePage }}</a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
    <span class="sr-only">Toggle navigation</span>
    <span class="navbar-toggler-icon icon-bar"></span>
    <span class="navbar-toggler-icon icon-bar"></span>
    <span class="navbar-toggler-icon icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">

      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('home') }}">
            <i class="material-icons">dashboard</i>
            <p class="d-lg-none d-md-block">
              {{ __('Stats') }}
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">notifications</i>
            <span class="notification" id="unread_notification_count">{{auth()->user()->unreadNotifications->count()}}</span>
            <p class="d-lg-none d-md-block">
              {{ __('Some Actions') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">

                <div id="notification_container">
                    @if(auth()->user()->is_admin)
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <div class="dropdown-item" role="alert" style="width:700px" data-id="{{ $notification->id }}">
                                [{{ $notification->created_at }}] User {{ $notification->data['message'] }}
                                <div style="width:250px;max-width:500px; display: inline-block">
                                    <a href="#" class="dropdown-item float-right " >
                                    </a></div>
                                <div style="width: 100px"><button class="btn-info pull-left mark-as-read" data-id="{{ $notification->id }}">Mark as read</button></div>
                            </div>

                            @if($loop->last)
                                <a href="#" id="mark-all">
                                    <P class="dropdown-item text-danger">Mark all as read</P>
                                </a>
                            @endif
                        @empty
                            There are no new notifications
                        @endforelse
                    @endif
                </div>

          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">person</i>
            <p class="d-lg-none d-md-block">
              {{ __('Account') }}
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
            <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Log out') }}</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>


