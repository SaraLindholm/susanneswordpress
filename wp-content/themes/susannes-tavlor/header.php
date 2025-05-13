<?php
include 'helper.php';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
