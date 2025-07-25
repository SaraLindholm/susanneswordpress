<?php // phpcs:disable Universal.Files.SeparateFunctionsFromOO.Mixed
/**
 * ACF Admin Notices
 *
 * Functions and classes to manage admin notices.
 *
 * @date    10/1/19
 * @since   ACF 5.7.10
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register notices store.
acf_register_store( 'notices' );

/**
 * ACF_Admin_Notice
 *
 * Class used to create an admin notice.
 *
 * @date    10/1/19
 * @since   ACF 5.7.10
 */
if ( ! class_exists( 'ACF_Admin_Notice' ) ) :
	/**
	 * Class responsible for handling admin notices.
	 */
	class ACF_Admin_Notice extends ACF_Data {

		/**
		 * Storage for data.
		 *
		 * @var array
		 */
		public $data = array(

			/** Text displayed in notice. @type string */
			'text'        => '',

			/** The type of notice (warning, error, success, info). @type string */
			'type'        => 'info',

			/** If the notice can be dismissed. @type bool */
			'dismissible' => true,

			/** If the dismissed state should be persisted to ACF user preferences. @type bool */
			'persisted'   => false,
		);

		/**
		 * Renders the notice HTML.
		 *
		 * @date    27/12/18
		 * @since   ACF 5.8.0
		 *
		 * @return  void
		 */
		public function render() {
			$notice_text    = $this->get( 'text' );
			$notice_type    = $this->get( 'type' );
			$is_dismissible = $this->get( 'dismissible' );
			$is_persisted   = $this->get( 'persisted' );

			printf(
				'<div class="acf-admin-notice notice notice-%s %s" data-persisted="%s" data-persist-id="%s">%s</div>',
				esc_attr( $notice_type ),
				$is_dismissible ? 'is-dismissible' : '',
				$is_persisted ? 'true' : 'false',
				esc_attr( md5( $notice_text ) ),
				acf_esc_html( wpautop( acf_punctify( $notice_text ) ) )
			);
		}
	}

endif; // class_exists check

/**
 * Instantiates and returns a new model.
 *
 * @date    23/12/18
 * @since   ACF 5.8.0
 *
 * @param   array $data Optional data to set.
 * @return  ACF_Admin_Notice
 */
function acf_new_admin_notice( $data = false ) {

	// Create notice.
	$instance = new ACF_Admin_Notice( $data );

	// Register notice.
	acf_get_store( 'notices' )->set( $instance->cid, $instance );

	// Return notice.
	return $instance;
}

/**
 * Renders all admin notices HTML.
 *
 * @date    10/1/19
 * @since   ACF 5.7.10
 *
 * @return  void
 */
function acf_render_admin_notices() {

	// Get notices.
	$notices = acf_get_store( 'notices' )->get_data();

	// Loop over notices and render.
	if ( $notices ) {
		foreach ( $notices as $notice ) {
			$notice->render();
		}
	}
}

// Render notices during admin action.
add_action( 'admin_notices', 'acf_render_admin_notices', 99 );

/**
 * Creates and returns a new notice.
 *
 * @date        17/10/13
 * @since       ACF 5.0.0
 *
 * @param   string  $text        The admin notice text.
 * @param   string  $type        The type of notice (warning, error, success, info).
 * @param   boolean $dismissible Is this notification dismissible (default true) (since 5.11.0).
 * @param   boolean $persisted   Store once a notice has been dismissed per user and prevent showing it again. (since ACF 6.1.0).
 * @return  ACF_Admin_Notice
 */
function acf_add_admin_notice( $text = '', $type = 'info', $dismissible = true, $persisted = false ) {
	return acf_new_admin_notice(
		array(
			'text'        => $text,
			'type'        => $type,
			'dismissible' => $dismissible,
			'persisted'   => $persisted,
		)
	);
}
