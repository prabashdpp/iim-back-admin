<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="https://investinmoi.com/" class="simple-text logo-normal">
      {{ __('Invest in Moi') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
        <li class="nav-item{{ $activePage == 'investors' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('investors') }}">
                <i class="material-icons">group</i>
                <p>{{ __('Investors') }}</p>
            </a>
        </li>

        <li class="nav-item{{ $activePage == 'commissions' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('commissions') }}">
                <i class="material-icons">local_atm</i>
                <p>{{ __('Commissions') }}</p>
            </a>
        </li>

        <li class="nav-item{{ $activePage == 'requests' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('requests') }}">
                <i class="material-icons">support_agent</i>
                <p>{{ __('Requests') }}</p>
            </a>
        </li>

        <li class="nav-item{{ $activePage == 'gold' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('gold') }}">
                <i class="material-icons">account_balance</i>
                <p>{{ __('Gold') }}</p>
            </a>
        </li>





        <li class="nav-item {{ ( $activePage == 'reports-summary') ? ' active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
                <i class="material-icons">insert_chart_outlined</i>
                <p>{{ __('Reports') }}
                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse hide" id="laravelExample">
                <ul class="nav">
                    <li class="nav-item{{ $activePage == 'reports-summary' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('reports.summary') }}">
                            <i class="material-icons">insert_chart_outlined</i>
                            <span class="sidebar-normal">{{ __('Reports Summary') }} </span>
                        </a>
                    </li>
                    <li class="nav-item{{ $activePage == 'reports-users' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('reports.users') }}">
                            <i class="material-icons">insert_chart_outlined</i>
                            <span class="sidebar-normal">{{ __('User Report') }} </span>
                        </a>
                    </li>
                    <li class="nav-item{{ $activePage == 'reports-invests' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('reports.invests') }}">
                            <i class="material-icons">insert_chart_outlined</i>
                            <span class="sidebar-normal">{{ __('Investment Report') }} </span>
                        </a>
                    </li>
                    <li class="nav-item{{ $activePage == 'reports-requests' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('reports.requests') }}">
                            <i class="material-icons">insert_chart_outlined</i>
                            <span class="sidebar-normal">{{ __('Requests Report') }} </span>
                        </a>
                    </li>
                    <li class="nav-item{{ $activePage == 'reports-payments' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('reports.payments') }}">
                            <i class="material-icons">insert_chart_outlined</i>
                            <span class="sidebar-normal"> {{ __('Payment Report') }} </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

      <li class="nav-item{{ $activePage == 'typography' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('typography') }}">
          <i class="material-icons">library_books</i>
            <p>{{ __('Typography') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'icons' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('icons') }}">
          <i class="material-icons">bubble_chart</i>
          <p>{{ __('Icons') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'map' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('map') }}">
          <i class="material-icons">location_ons</i>
            <p>{{ __('Maps') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('notifications') }}">
          <i class="material-icons">notifications</i>
          <p>{{ __('Notifications') }}</p>
        </a>
      </li>

    </ul>
  </div>
</div>
