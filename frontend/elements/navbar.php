<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-success">
  <a class="navbar-brand" href="">MMDb</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php
        $nav_links = array(
          'Home' => './index.php',
          'Browse' => './browse.php'
        );
        foreach ($nav_links as $name => $url) {
          echo '<li class="nav-item'. (($currentPage == $name) ? ' active' : '') . '">'
              .'<a class="nav-link" href="'. $url .'">' . $name . '</a></li>';
        }
      ?>
    </ul>
    <ul class="navbar-nav">
      <button type="button" id="logout-button" class="btn btn-dark d-none" onclick="logout()">Log Out</button>
      <div class="btn-group">
        <button type="button" id="login-button" class="btn btn-dark" data-toggle="modal" data-target="#loginModal">Log In</button>
        <button type="button" id="register-button" class="btn btn-dark" data-toggle="modal" data-target="#registerModal">Register</button>
      </div>
    </ul>
  </div>
</nav>
