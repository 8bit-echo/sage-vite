<header class="site-header">
    <nav class="primary"> {!! App::custom_logo() !!}

      <button class="mobile-button" id="mobile-nav-open" onclick="document.querySelector('.mobile-menu-wrap').classList.toggle('active')">&#9776;</button>
      
      <div class="mobile-menu-wrap">
        <button class="mobile-button" id="mobile-nav-close" onclick="document.querySelector('.mobile-menu-wrap').classList.toggle('active')">&#10005;</button>
      @if (has_nav_menu('primary_navigation')) {!! wp_nav_menu(['theme_location' => 'primary_navigation']) !!}
      @endif
    </div>

      
    </nav>

    <nav class="utility">
      @if (has_nav_menu('utility_navigation')) {!! wp_nav_menu(['theme_location' => 'utility_navigation']) !!}
      @endif
    </nav>
</header>
