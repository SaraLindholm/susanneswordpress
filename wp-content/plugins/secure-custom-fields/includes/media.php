<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'ACF_Media' ) ) :

	class ACF_Media {

		/**
		 * Constructor.
		 *
		 * @date    23/06/12
		 * @since   ACF 5.0.0
		 *
		 * @return  void
		 */
		public function __construct() {

			// Localize media strings.
			add_action( 'acf/enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Save files uploaded from basic `$_FILE` field.
			add_action( 'acf/save_post', array( $this, 'save_files' ), 5, 1 );

			// Hook into Media Upload to run additional logic.
			add_filter( 'wp_handle_upload_prefilter', array( $this, 'handle_upload_prefilter' ), 10, 1 );

			// Hook into Media Modal Query to run additional logic.
			add_action( 'wp_ajax_query-attachments', array( $this, 'wp_ajax_query_attachments' ), -1 );
		}

		/**
		 * Fires when ACF scripts are enqueued.
		 *
		 * @date    27/4/18
		 * @since   ACF 5.6.9
		 *
		 * @return  void
		 */
		public function enqueue_scripts() {
			if ( wp_script_is( 'acf-input' ) ) {
				acf_localize_text(
					array(
						'Select.verb'           => _x( 'Select', 'verb', 'secure-custom-fields' ),
						'Edit.verb'             => _x( 'Edit', 'verb', 'secure-custom-fields' ),
						'Update.verb'           => _x( 'Update', 'verb', 'secure-custom-fields' ),
						'Uploaded to this post' => __( 'Uploaded to this post', 'secure-custom-fields' ),
						'Expand Details'        => __( 'Expand Details', 'secure-custom-fields' ),
						'Collapse Details'      => __( 'Collapse Details', 'secure-custom-fields' ),
						'Restricted'            => __( 'Restricted', 'secure-custom-fields' ),
						'All images'            => __( 'All images', 'secure-custom-fields' ),
					)
				);
				acf_localize_data(
					array(
						'mimeTypeIcon' => wp_mime_type_icon(),
						'mimeTypes'    => get_allowed_mime_types(),
					)
				);
			}
		}

		/**
		 * Uploads attachments found in the basic `$_FILES` array.
		 *
		 * @date    24/10/2014
		 * @since   ACF 5.0.9
		 *
		 * @param   string|integer $post_id The post ID being saved.
		 * @return  void
		 */
		public function save_files( $post_id = 0 ) {
			if ( isset( $_FILES['acf']['name'] ) ) { // phpcs:disable WordPress.Security.NonceVerification.Missing -- Verified upstream.
				acf_upload_files();
			}
		}

		/**
		 * Filters data for the current file being uploaded.
		 *
		 * @date    16/02/2015
		 * @since   ACF 5.1.5
		 *
		 * @param   array $file An array of data for a single file.
		 * @return  array
		 */
		public function handle_upload_prefilter( $file ) {
			$field = $this->get_source_field();
			if ( ! $field ) {
				return $file;
			}

			// Validate the attachment and append any errors.
			$errors = acf_validate_attachment( $file, $field, 'upload' );

			/**
			 * Filters the errors for a file before it is uploaded to WordPress.
			 *
			 * @date    16/02/2015
			 * @since   ACF 5.1.5
			 *
			 * @param   array $errors An array of errors.
			 * @param   array $file An array of data for a single file.
			 * @param   array $field The field array.
			 */
			$errors = apply_filters( "acf/upload_prefilter/type={$field['type']}", $errors, $file, $field );
			$errors = apply_filters( "acf/upload_prefilter/name={$field['_name']}", $errors, $file, $field );
			$errors = apply_filters( "acf/upload_prefilter/key={$field['key']}", $errors, $file, $field );
			$errors = apply_filters( 'acf/upload_prefilter', $errors, $file, $field );

			// Append errors.
			if ( ! empty( $errors ) ) {
				$file['error'] = implode( "\n", $errors );
			}

			// Ensure newly uploaded image contains "preview_size" within the "size" data.
			add_filter( 'image_size_names_choose', array( $this, 'image_size_names_choose' ), 10, 1 );

			// Return.
			return $file;
		}




		/**
		 * Returns the field responsible for the current Media query or upload context.
		 *
		 * @date    21/5/21
		 * @since   ACF 5.9.7
		 *
		 * @return  array| false.
		 */
		private function get_source_field() {
			$field = false;

			// phpcs:disable WordPress.Security.NonceVerification.Missing -- Verified elsewhere.
			// Search for field key within available data.
			// Case 1) Media modal query.
			if ( isset( $_POST['query']['_acfuploader'] ) ) {
				$field_key = sanitize_text_field( $_POST['query']['_acfuploader'] );

				// Case 2) Media modal upload.
			} elseif ( isset( $_POST['_acfuploader'] ) ) {
				$field_key = sanitize_text_field( $_POST['_acfuploader'] );
			}
			// phpcs:enable WordPress.Security.NonceVerification.Missing

			// Attempt to load field.
			// Note the `acf_get_field()` function will return false if not found.
			if ( isset( $field_key ) ) {
				$field = acf_get_field( $field_key );
			}
			return $field;
		}

		/**
		 * Fires during the WP Modal Query AJAX call.
		 *
		 * @date    26/06/2015
		 * @since   ACF 5.2.3
		 *
		 * @return  void
		 */
		function wp_ajax_query_attachments() {
			if ( $this->get_source_field() ) {
				add_filter( 'wp_prepare_attachment_for_js', array( $this, 'wp_prepare_attachment_for_js' ), 10, 3 );
				add_filter( 'image_size_names_choose', array( $this, 'image_size_names_choose' ), 10, 1 );
			} else {
				add_filter( 'wp_prepare_attachment_for_js', array( $this, 'clear_acf_errors_for_core_requests' ), 5, 3 );
			}
		}

		/**
		 * Append acf_errors false for non-acf media library calls to prevent media library caching.
		 *
		 * @date    31/8/21
		 * @since   ACF 5.10.2
		 *
		 * @param   array       $response   Array of prepared attachment data.
		 * @param   WP_Post     $attachment Attachment object.
		 * @param   array|false $meta       Array of attachment meta data, or false if there is none.
		 * @return  array
		 */
		function clear_acf_errors_for_core_requests( $response, $attachment, $meta ) {
			$response['acf_errors'] = false;
			return $response;
		}

		/**
		 * Filters attachment data as it is being prepared for JS.
		 *
		 * @date    21/5/21
		 * @since   ACF 5.9.7
		 *
		 * @param   array       $response   Array of prepared attachment data.
		 * @param   WP_Post     $attachment Attachment object.
		 * @param   array|false $meta       Array of attachment meta data, or false if there is none.
		 * @return  array
		 */
		function wp_prepare_attachment_for_js( $response, $attachment, $meta ) {
			$field = $this->get_source_field();

			// Validate the attachment and append any errors.
			$errors                 = acf_validate_attachment( $response, $field, 'prepare' );
			$response['acf_errors'] = false;
			if ( ! empty( $errors ) ) {
				$response['acf_errors'] = implode( '<br />', $errors );
			}

			// Return.
			return $response;
		}

		/**
		 * Filters the names and labels of the default image sizes.
		 *
		 * @date    21/5/21
		 * @since   ACF 5.9.7
		 *
		 * @param   array $size_names Array of image size labels keyed by their name.
		 * @return  array
		 */
		function image_size_names_choose( $size_names ) {
			$field = $this->get_source_field();

			// Append "preview_size" setting to array of image sizes so WP will include in prepared JS data.
			if ( isset( $field['preview_size'] ) ) {
				$name                = (string) $field['preview_size'];
				$size_names[ $name ] = $name; // Don't worry about size label, it is never used.
			}
			return $size_names;
		}
	}

	// Instantiate.
	acf_new_instance( 'ACF_Media' );
endif; // class_exists check
