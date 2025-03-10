<!DOCTYPE html>
<html lang="en">

<head>
  <?php

  include $path . '/views/components/autoload.php';

  #region session and header
  if (isset($_SESSION['user'])) {
    include $path . '/views/components/header-online.php';
  } else {
    include $path . '/views/components/header-offline.php';
  }
  #endregion
  ?>
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .b-example-divider {
      width: 100%;
      height: 3rem;
      background-color: rgba(0, 0, 0, .1);
      border: solid rgba(0, 0, 0, .15);
      border-width: 1px 0;
      box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .b-example-vr {
      flex-shrink: 0;
      width: 1.5rem;
      height: 100vh;
    }

    .bi {
      vertical-align: -.125em;
      fill: currentColor;
    }

    .nav-scroller {
      position: relative;
      z-index: 2;
      height: 2.75rem;
      overflow-y: hidden;
    }

    .nav-scroller .nav {
      display: flex;
      flex-wrap: nowrap;
      padding-bottom: 1rem;
      margin-top: -1px;
      overflow-x: auto;
      text-align: center;
      white-space: nowrap;
      -webkit-overflow-scrolling: touch;
    }

    .btn-bd-primary {
      --bd-violet-bg: #712cf9;
      --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

      --bs-btn-font-weight: 600;
      --bs-btn-color: var(--bs-white);
      --bs-btn-bg: var(--bd-violet-bg);
      --bs-btn-border-color: var(--bd-violet-bg);
      --bs-btn-hover-color: var(--bs-white);
      --bs-btn-hover-bg: #6528e0;
      --bs-btn-hover-border-color: #6528e0;
      --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
      --bs-btn-active-color: var(--bs-btn-hover-color);
      --bs-btn-active-bg: #5a23c8;
      --bs-btn-active-border-color: #5a23c8;
    }

    .bd-mode-toggle {
      z-index: 1500;
    }

    .bd-mode-toggle .dropdown-menu .active .bi {
      display: block !important;
    }

    .form-signin {
      max-width: 330px;
      padding: 1rem;
    }

    .form-signin .form-floating:focus-within {
      z-index: 2;
    }

    .form-signin input[type="email"] {
      margin-bottom: -1px;
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
      margin-bottom: 10px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
    }
  </style>
</head>

<body>

  <main class="form-signin w-100 m-auto">
    <form action="/?page=login" method="POST">
      <input type="hidden" name="method" value="login">

      <h1 class="h3 mb-3 fw-normal">Connectez-vous</h1>

      <?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <div class="form-floating">
        <input type="email" class="form-control" id="email" name="email" placeholder="example@les3mousqueton.fr">
        <label for="email">adresse mail</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="password" name="password" placeholder="mot de passe">
        <label for="password">mot de passe</label>
      </div>

      <div class="g-recaptcha" data-sitekey="<?php echo $_ENV['CAPTCHA_KEY'] ?>"></div>

      <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>

    </form>
  </main>



  <!-- google captcha -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <?php
  include $path . '/views/components/footer.php';
  ?>
</body>

</html>