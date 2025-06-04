<?php
/*
Plugin Name: Plugin for Tavlor
Description: A plugin to display artwork posts with a custom layout.
*/

// Registrera en  taxonomi för kategorier för tavlor
function tavel_register_kategori_taxonomy() {
	$labels = array(
		'name'              => _x( 'Tavla Kategorier', 'taxonomy general name' ),
		'singular_name'     => _x( 'Tavla Kategori', 'taxonomy singular name' ),
		'search_items'      => __( 'Sök Kategorier' ),
		'all_items'         => __( 'Alla Kategorier' ),
		'edit_item'         => __( 'Redigera Kategori' ),
		'update_item'       => __( 'Uppdatera Kategori' ),
		'add_new_item'      => __( 'Lägg till ny Kategori' ),
		'new_item_name'     => __( 'Nytt Kategorinamn' ),
		'menu_name'         => __( 'Kategorier' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'tavla-kategori' ),
	);

	register_taxonomy( 'tavla_kategori', array( 'tavla' ), $args );
}
add_action( 'init', 'tavel_register_kategori_taxonomy' );

function tavel_post_type() {

	$labels = array(
		'name'                  => _x( 'Tavlor', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Tavla', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Tavlor', 'text_domain' ),
		'name_admin_bar'        => __( 'Tavlor', 'text_domain' ),
		'archives'              => __( 'Arkiv', 'text_domain' ),
		'attributes'            => __( 'Attribute', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'Alla Tavlor', 'text_domain' ),
		'add_new_item'          => __( 'Lägg till Ny Tavla', 'text_domain' ),
		'add_new'               => __( 'Lägg till', 'text_domain' ),
		'new_item'              => __( 'Ny Tavla', 'text_domain' ),
		'edit_item'             => __( 'Redigera tavla', 'text_domain' ),
		'update_item'           => __( 'Uppdatera Tavla', 'text_domain' ),
		'view_item'             => __( 'Visa tavla', 'text_domain' ),
		'view_items'            => __( 'Visa tavlor', 'text_domain' ),
		'search_items'          => __( 'Sök tavla', 'text_domain' ),
		'not_found'             => __( 'Hittades ej', 'text_domain' ),
		'not_found_in_trash'    => __( 'Hittades ej i Papperskorgen', 'text_domain' ),
		'featured_image'        => __( 'Huvudbild', 'text_domain' ),
		'set_featured_image'    => __( 'Huvudbild', 'text_domain' ),
		'remove_featured_image' => __( 'Ta bort Huvudbild', 'text_domain' ),
		'use_featured_image'    => __( 'Ange som Huvudbild', 'text_domain' ),
		'insert_into_item'      => __( 'Applicera till Tavla', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uppladdad till Tavlan', 'text_domain' ),
		'items_list'            => __( '', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Tavla', 'text_domain' ),
		'description'           => __( 'Sida för Tavlor', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail', 'comments', 'custom-fields' ),
		'taxonomies'            => array( 'tavla_kategori' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 2,
		'menu_icon'             => 'dashicons-art',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'tavla', $args );

}
add_action( 'init', 'tavel_post_type', 0 );
