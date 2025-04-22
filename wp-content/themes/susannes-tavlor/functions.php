<?php

function susannes_tavlor_register_menus() {
  register_nav_menus([
    'main_menu' => 'Huvudmeny',
  ]);
}
add_action('after_setup_theme', 'susannes_tavlor_register_menus');

function susannes_tavlor_enqueue_scripts() {
  // Lägg till Bootstrap CSS från CDN
  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css', false, '4.3.1', 'all');

  // Lägg till ditt eget tema CSS
  wp_enqueue_style('theme-style', get_template_directory_uri() . '/css/style.css');

  // Lägg till Bootstrap JS och dess beroende jQuery (måste vara i rätt ordning)
  wp_enqueue_script('jquery');
  wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'), '4.3.1', true);
}
add_action('wp_enqueue_scripts', 'susannes_tavlor_enqueue_scripts');
