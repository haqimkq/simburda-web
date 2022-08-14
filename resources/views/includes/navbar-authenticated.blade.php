{{-- <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-store fixed-top navbar-fixed-top" data-aos="fade-down">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
        <img src="images/logo.svg" alt="" />
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
        aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('/')) ? 'active': '' }}" href="{{ route('home') }}">Home </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('categories')) ? 'active': '' }}" href="{{ route('categories') }}">Categories</a>
          </li>
        </ul>

        <!-- Desktop Menu -->
        <ul class="navbar-nav d-none d-lg-flex">
          <li class="nav-item dropdown">
            <a 
              class="nav-link"
              href="#" 
              id="navbarDropdown" 
              role="button" 
              data-toggle="dropdown"
            >
              <img src="images/icon-user.png" alt="" class="rounded-circle mr-2 profile-picture" />
              Hi, Angga
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="dashboard.html">Dashboard</a>
              <a class="dropdown-item" href="dashboard-account.html">Settings</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/">Logout</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link d-inline-block mt-2" href="#">
              <img src="/images/icon-cart-empty.svg" alt="" />
            </a>
          </li>
        </ul>
        
        <!-- Mobile Menu -->
        <ul class="navbar-nav d-block d-lg-none">
          <li class="nav-item">
            <a class="nav-link" href="#">
              Hi, Angga
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link d-inline-block" href="#">
              Cart
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav> --}}
