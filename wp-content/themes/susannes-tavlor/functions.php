<?php

function susannes_tavlor_register_menus() {
  register_nav_menus([
    'main_menu' => 'Huvudmeny',
    'footer_menu' => 'Footermeny',
  ]);
}
add_action('after_setup_theme', 'susannes_tavlor_register_menus');



function susannes_tavlor_setup () {
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');
}
add_action('after_setup_theme', 'susannes_tavlor_setup');
/* custom size  */
function my_custom_image_size() {
  add_image_size('super-large', 1400, 600, false); // false = inte beskära (crop)
}

add_action('after_setup_theme', 'my_custom_image_size');


function susannes_tavlor_enqueue_scripts() {

  // Stöd för Boostrap
  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css', false, '4.3.1', 'all');

  // Font Awesome
wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', false, '6.5.0');

  //EGEN CSS
wp_enqueue_style('theme-style', get_template_directory_uri() . '/css/style.css', [], filemtime(get_template_directory() . '/css/style.css'));

  //  Bootstrap JS och dess beroende jQuery (måste vara i rätt ordning)
wp_enqueue_script('jquery');
wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'), '4.3.1', true);
}

add_action('wp_enqueue_scripts', 'susannes_tavlor_enqueue_scripts');

//påverka längd och style för sammanfattningen av inlägg
add_filter('excerpt_more', function () {
  return '<a class="excerpt-more" style="color: red;" href="' . get_permalink() . '"> [...Läs mer]</a>';
});
