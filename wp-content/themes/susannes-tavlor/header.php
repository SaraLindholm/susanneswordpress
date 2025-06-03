<?php
include 'helper.php';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <title><?php bloginfo('name'); ?></title>

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <header id="wrap">
  <div class="container">
    <div class="logo-wrapper">
      <a class="logo" href="<?php echo home_url(); ?>">
        <?php if (has_custom_logo()) {
          the_custom_logo();
        } else {
          bloginfo('name');
        } ?>
      </a>
    </div>
    <nav id="nav">
      <?php
      wp_nav_menu([
        'theme_location' => 'main_menu',
        'menu_class' => 'menu',
        'container' => false,
      ]);
      ?>
    </nav>
  </div>
</header>
