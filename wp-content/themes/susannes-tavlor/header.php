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

  <div id="wrap">
    <header id="header">
      <div class="container">
        <div class="row">
          <div class="col-xs-8 col-sm-6">
            <!-- Om du vill använda en bild som logotyp, ersätt texten med en bild -->
            <a class="logo" href="<?php echo home_url(); ?>">
              <?php if (has_custom_logo()) {
                the_custom_logo();
              } else { ?>
                <?php bloginfo('name'); ?>
              <?php } ?>
            </a>
          </div>
          <!--<div class="col-sm-6 hidden-xs">
            <?php get_search_form(); ?>
          </div>
          <div class="col-xs-4 text-right visible-xs">
            <div class="mobile-menu-wrap">
              <i class="fa fa-search"></i>
              <i class="fa fa-bars menu-icon"></i>
            </div>
          </div>-->
        </div>
      </div>
    </header>
    <nav id="nav">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <?php
            wp_nav_menu([
              'theme_location' => 'main_menu',
              'menu_class' => 'menu',
              'container' => false,
            ]);
            ?>
          </div>
        </div>
      </div>
    </nav>
  </div>
