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
  <nav id="nav">
  <div class="container d-flex justify-content-end">
 <div class="col-xs-6 col-sm-4">
  <!-- TODO fixa så att "loggan" ligger i rött höjd och att den lägger sig en rad ovan för navbaren i smalt lge -->
  <a class="logo" href="<?php echo home_url(); ?>">
              <?php if (has_custom_logo()) {
                the_custom_logo();
              } else { ?>
                <?php bloginfo('name'); ?>
              <?php } ?>
            </a>
            </div>


            <?php
            wp_nav_menu([
              'theme_location' => 'main_menu',
              'menu_class' => 'menu',
              'container' => false,
            ]);
            ?>

      </div>
    </nav>
      <!--    <header id="header">
      <div class="container">
        <div class="row">
          <div class="col-xs-8 col-sm-6">

          </div>

        </div>
      </div>
    </header> -->

          </header>
