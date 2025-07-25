<?php
/**
 * The Flexible Content Field.
 *
 * @package wordpress/secure-custom-fields
 */

// phpcs:disable PEAR.NamingConventions.ValidClassName
if ( ! class_exists( 'acf_field_flexible_content' ) ) :

	/**
	 * The Flexible Content Field class.
	 */
	class acf_field_flexible_content extends acf_field {



		/**
		 * This function will setup the field type data
		 *
		 * @type    function
		 * @date    5/03/2014
		 * @since   ACF 5.0.0
		 */
		public function initialize() {

			// vars
			$this->name          = 'flexible_content';
			$this->label         = __( 'Flexible Content', 'secure-custom-fields' );
			$this->category      = 'layout';
			$this->description   = __( 'Allows you to define, create and manage content with total control by creating layouts that contain subfields that content editors can choose from.', 'secure-custom-fields' ) . ' ' . __( 'We do not recommend using this field in ACF Blocks.', 'secure-custom-fields' );
			$this->preview_image = acf_get_url() . '/assets/images/field-type-previews/field-preview-flexible-content.png';
			$this->doc_url       = 'https://developer.wordpress.org/secure-custom-fields/features/fields/flexible-content/';
			$this->tutorial_url  = 'https://developer.wordpress.org/secure-custom-fields/features/fields/flexible-content/flexible-content-tutorial/';
			$this->pro           = true;
			$this->supports      = array( 'bindings' => false );
			$this->defaults      = array(
				'layouts'      => array(),
				'min'          => '',
				'max'          => '',
				'button_label' => __( 'Add Row', 'secure-custom-fields' ),
			);

			// ajax
			$this->add_action( 'wp_ajax_acf/fields/flexible_content/layout_title', array( $this, 'ajax_layout_title' ) );
			$this->add_action( 'wp_ajax_nopriv_acf/fields/flexible_content/layout_title', array( $this, 'ajax_layout_title' ) );

			// filters
			$this->add_filter( 'acf/prepare_field_for_export', array( $this, 'prepare_any_field_for_export' ) );
			$this->add_filter( 'acf/clone_field', array( $this, 'clone_any_field' ), 10, 2 );
			$this->add_filter( 'acf/validate_field', array( $this, 'validate_any_field' ) );

			// field filters
			$this->add_field_filter( 'acf/get_sub_field', array( $this, 'get_sub_field' ), 10, 3 );
			$this->add_field_filter( 'acf/prepare_field_for_export', array( $this, 'prepare_field_for_export' ) );
			$this->add_field_filter( 'acf/prepare_field_for_import', array( $this, 'prepare_field_for_import' ) );
		}


		/**
		 * Admin scripts enqueue for field.
		 *
		 * @type    function
		 * @date    16/12/2015
		 * @since   ACF 5.3.2
		 *
		 * @return  void
		 */
		public function input_admin_enqueue_scripts() {

			// localize
			acf_localize_text(
				array(

					// identifiers
					'layout'  => __( 'layout', 'secure-custom-fields' ),
					'layouts' => __( 'layouts', 'secure-custom-fields' ),
					'Fields'  => __( 'Fields', 'secure-custom-fields' ),

					// min / max
					'This field requires at least {min} {label} {identifier}' => __( 'This field requires at least {min} {label} {identifier}', 'secure-custom-fields' ),
					'This field has a limit of {max} {label} {identifier}' => __( 'This field has a limit of {max} {label} {identifier}', 'secure-custom-fields' ),

					// popup badge
					'{available} {label} {identifier} available (max {max})' => __( '{available} {label} {identifier} available (max {max})', 'secure-custom-fields' ),
					'{required} {label} {identifier} required (min {min})' => __( '{required} {label} {identifier} required (min {min})', 'secure-custom-fields' ),

					// field settings
					'Flexible Content requires at least 1 layout' => __( 'Flexible Content requires at least 1 layout', 'secure-custom-fields' ),
				)
			);
		}


		/**
		 * This function will fill in the missing keys to create a valid layout
		 *
		 * @type    function
		 * @date    3/10/13
		 * @since   ACF 1.1.0
		 *
		 * @param   array $layout The layout array to validate.
		 * @return  array $layout The validated layout array.
		 */
		public function get_valid_layout( $layout = array() ) {

			// parse
			$layout = wp_parse_args(
				$layout,
				array(
					'key'        => uniqid( 'layout_' ),
					'name'       => '',
					'label'      => '',
					'display'    => 'block',
					'sub_fields' => array(),
					'min'        => '',
					'max'        => '',
				)
			);

			// return
			return $layout;
		}


		/**
		 * Load a field.
		 *
		 * @since   ACF 3.6
		 *
		 * @param   array $field the field array holding all the field options.
		 *
		 * @return  array $field the field array holding all the field options.
		 */
		public function load_field( $field ) {

			// bail early if no field layouts
			if ( empty( $field['layouts'] ) ) {
				return $field;
			}

			// vars
			$sub_fields = acf_get_fields( $field );

			// loop through layouts, sub fields and swap out the field key with the real field
			foreach ( array_keys( $field['layouts'] ) as $i ) {

				// extract layout
				$layout = acf_extract_var( $field['layouts'], $i );

				// validate layout
				$layout = $this->get_valid_layout( $layout );

				// append sub fields
				if ( ! empty( $sub_fields ) ) {
					foreach ( array_keys( $sub_fields ) as $k ) {

						// check if 'parent_layout' is empty
						if ( empty( $sub_fields[ $k ]['parent_layout'] ) ) {

							// parent_layout did not save for this field, default it to first layout
							$sub_fields[ $k ]['parent_layout'] = $layout['key'];
						}

						// append sub field to layout,
						if ( $sub_fields[ $k ]['parent_layout'] == $layout['key'] ) { // phpcs:ignore Universal.Operators.StrictComparisons -- @todo Confirm types used here.
							$layout['sub_fields'][] = acf_extract_var( $sub_fields, $k );
						}
					}
				}

				// append back to layouts
				$field['layouts'][ $i ] = $layout;
			}

			// return
			return $field;
		}


		/**
		 * This function will return a specific sub field.
		 *
		 * @type    function
		 * @date    29/09/2016
		 * @since   ACF 5.4.0
		 *
		 * @param   int        $sub_field Sub field.
		 * @param   int|string $id the ID.
		 * @param   array      $field Field array.
		 * @return  int
		 */
		public function get_sub_field( $sub_field, $id, $field ) {

			// Get active layout.
			$active = get_row_layout();

			// Loop over layouts.
			if ( $field['layouts'] ) {
				foreach ( $field['layouts'] as $layout ) {

					// Restrict to active layout if within a have_rows() loop.
					if ( $active && $active !== $layout['name'] ) {
						continue;
					}

					// Check sub fields.
					if ( $layout['sub_fields'] ) {
						$sub_field = acf_search_fields( $id, $layout['sub_fields'] );
						if ( $sub_field ) {
							break;
						}
					}
				}
			}

			// return
			return $sub_field;
		}


		/**
		 * Create the HTML interface for your field
		 *
		 * @type    action
		 * @since   ACF 3.6
		 * @date    23/01/13
		 *
		 * @param   array $field An array holding all the field's data.
		 * @return  void
		 */
		public function render_field( $field ) {

			// defaults
			if ( empty( $field['button_label'] ) ) {
				$field['button_label'] = $this->defaults['button_label'];
			}

			// sort layouts into names
			$layouts = array();

			foreach ( $field['layouts'] as $k => $layout ) {
				$layouts[ $layout['name'] ] = $layout;
			}

			// vars
			$div = array(
				'class'    => 'acf-flexible-content',
				'data-min' => $field['min'],
				'data-max' => $field['max'],
			);

			// empty
			if ( empty( $field['value'] ) ) {
				$div['class'] .= ' -empty';
			}

			// no value message
			// translators: %s the button label used for adding a new layout.
			$no_value_message = __( 'Click the "%s" button below to start creating your layout', 'secure-custom-fields' );
			$no_value_message = apply_filters( 'acf/fields/flexible_content/no_value_message', $no_value_message, $field );
			$no_value_message = sprintf( $no_value_message, $field['button_label'] );

			?>
			<div <?php echo acf_esc_attrs( $div ); ?>>

				<?php acf_hidden_input( array( 'name' => $field['name'] ) ); ?>

				<div class="no-value-message">
					<?php echo acf_esc_html( $no_value_message ); ?>
				</div>

				<div class="clones">
					<?php foreach ( $layouts as $layout ) : ?>
						<?php $this->render_layout( $field, $layout, 'acfcloneindex', array() ); ?>
					<?php endforeach; ?>
				</div>

				<div class="values">
					<?php
					if ( ! empty( $field['value'] ) ) {
						foreach ( $field['value'] as $i => $value ) {

							// validate
							if ( ! is_array( $value ) ) {
								continue;
							}

							if ( empty( $layouts[ $value['acf_fc_layout'] ] ) ) {
								continue;
							}

							// render
							$this->render_layout( $field, $layouts[ $value['acf_fc_layout'] ], $i, $value );
						}
					}
					?>
				</div>

				<div class="acf-actions">
					<a class="acf-button button button-primary" href="#" data-name="add-layout"><?php echo acf_esc_html( $field['button_label'] ); ?></a>
				</div>

				<?php
				echo '<script type="text-html" class="tmpl-popup"><ul>';
				foreach ( $layouts as $layout ) {
					$atts = array(
						'href'        => '#',
						'data-layout' => $layout['name'],
						'data-min'    => $layout['min'],
						'data-max'    => $layout['max'],
					);
					printf( '<li><a %s>%s</a></li>', acf_esc_attrs( $atts ), acf_esc_html( $layout['label'] ) );
				}
				echo '</ul></script>';
				?>
			</div>
			<?php
		}


		/**
		 * Description
		 *
		 * @type    function
		 * @date    19/11/2013
		 * @since   ACF 5.0.0
		 *
		 * @param   array $field Fields.
		 * @param array $layout Layout.
		 * @param int   $i row number.
		 * @param mixed $value Value.
		 * @return  void
		 */
		public function render_layout( $field, $layout, $i, $value ) {

			// vars
			$order      = 0;
			$el         = 'div';
			$sub_fields = $layout['sub_fields'];
			$id         = ( 'acfcloneindex' === $i ) ? 'acfcloneindex' : "row-$i";
			$prefix     = $field['name'] . '[' . $id . ']';

			// div
			$div = array(
				'class'       => 'layout',
				'data-id'     => $id,
				'data-layout' => $layout['name'],
				'data-max'    => $layout['max'],
				'data-label'  => $layout['label'],
			);

			// clone
			if ( is_numeric( $i ) ) {
				$order = $i + 1;
			} else {
				$div['class'] .= ' acf-clone';
			}

			// display
			if ( 'table' === $layout['display'] ) {
				$el = 'td';
			}

			// title
			$title = $this->get_layout_title( $field, $layout, $i, $value );

			// remove row
			reset_rows();

			?>
			<div <?php echo acf_esc_attrs( $div ); ?>>

				<?php
				acf_hidden_input(
					array(
						'name'  => $prefix . '[acf_fc_layout]',
						'value' => $layout['name'],
					)
				);
				?>

				<div class="acf-fc-layout-handle" title="<?php esc_attr_e( 'Drag to reorder', 'secure-custom-fields' ); ?>" data-name="collapse-layout"><?php echo acf_esc_html( $title ); ?></div>

				<div class="acf-fc-layout-controls">
					<a class="acf-icon -plus small light acf-js-tooltip" href="#" data-name="add-layout" title="<?php esc_attr_e( 'Add layout', 'secure-custom-fields' ); ?>"></a>
					<a class="acf-icon -duplicate small light acf-js-tooltip" href="#" data-name="duplicate-layout" title="<?php esc_attr_e( 'Duplicate layout', 'secure-custom-fields' ); ?>"></a>
					<a class="acf-icon -minus small light acf-js-tooltip" href="#" data-name="remove-layout" title="<?php esc_attr_e( 'Remove layout', 'secure-custom-fields' ); ?>"></a>
					<a class="acf-icon -collapse small -clear acf-js-tooltip" href="#" data-name="collapse-layout" title="<?php esc_attr_e( 'Click to toggle', 'secure-custom-fields' ); ?>"></a>
				</div>

				<?php if ( ! empty( $sub_fields ) ) : ?>

					<?php if ( 'table' === $layout['display'] ) : ?>
						<table class="acf-table">

							<thead>
								<tr>
									<?php
									foreach ( $sub_fields as $sub_field ) :

										// Set prefix to generate correct "for" attribute on <label>.
										$sub_field['prefix'] = $prefix;

										// Prepare field (allow sub fields to be removed).
										$sub_field = acf_prepare_field( $sub_field );
										if ( ! $sub_field ) {
											continue;
										}

										// Define attrs.
										$attrs              = array();
										$attrs['class']     = 'acf-th';
										$attrs['data-name'] = $sub_field['_name'];
										$attrs['data-type'] = $sub_field['type'];
										$attrs['data-key']  = $sub_field['key'];

										if ( $sub_field['wrapper']['width'] ) {
											$attrs['data-width'] = $sub_field['wrapper']['width'];
											$attrs['style']      = 'width: ' . $sub_field['wrapper']['width'] . '%;';
										}

										?>
										<th <?php echo acf_esc_attrs( $attrs ); ?>>
											<?php acf_render_field_label( $sub_field ); ?>
											<?php acf_render_field_instructions( $sub_field ); ?>
										</th>
									<?php endforeach; ?>
								</tr>
							</thead>

							<tbody>
								<tr class="acf-row">
								<?php else : ?>
									<div class="acf-fields
									<?php
									if ( 'row' === $layout['display'] ) :
										?>
		-left<?php endif; ?>">
									<?php endif; ?>

									<?php

									// loop though sub fields
									foreach ( $sub_fields as $sub_field ) {

										// add value
										if ( isset( $value[ $sub_field['key'] ] ) ) {

											// this is a normal value
											$sub_field['value'] = $value[ $sub_field['key'] ];
										} elseif ( isset( $sub_field['default_value'] ) ) {

											// no value, but this sub field has a default value
											$sub_field['value'] = $sub_field['default_value'];
										}

										// update prefix to allow for nested values
										$sub_field['prefix'] = $prefix;

										// render input
										acf_render_field_wrap( $sub_field, $el );
									}

									?>

									<?php if ( 'table' === $layout['display'] ) : ?>
								</tr>
							</tbody>
						</table>
					<?php else : ?>
			</div>
		<?php endif; ?>

	<?php endif; ?>

	</div>
			<?php
		}

		/**
		 * Renders the flexible content field layouts in the field group editor.
		 *
		 * @since ACF 3.6
		 * @date  23/01/13
		 *
		 * @param array $field An array holding all the field's data.
		 */
		public function render_field_settings( $field ) {
			$layout_open = apply_filters( 'acf/fields/flexible_content/layout_default_expanded', false );

			// Load default layout.
			if ( empty( $field['layouts'] ) ) {
				$layout_open      = true;
				$field['layouts'] = array(
					array(),
				);
			}

			$field_settings_class = $layout_open ? 'open' : '';
			$toggle_class         = $layout_open ? 'open' : 'closed';
			$field_settings_style = $layout_open ? '' : 'display: none;';

			// loop through layouts
			foreach ( $field['layouts'] as $layout ) {

				// get valid layout
				$layout = $this->get_valid_layout( $layout );

				// vars
				$layout_prefix = "{$field['prefix']}[layouts][{$layout['key']}]";

				?>
		<div class="acf-field acf-field-setting-fc_layout" data-name="fc_layout" data-setting="flexible_content" data-layout-label="<?php echo esc_attr( $layout['label'] ); ?>" data-layout-name="<?php echo esc_attr( $layout['name'] ); ?>" data-id="<?php echo esc_attr( $layout['key'] ); ?>">
			<div class="acf-label acf-field-settings-fc_head">
				<div class="acf-fc_draggable">
					<label class="acf-fc-layout-label reorder-layout"><?php esc_attr_e( 'Layout', 'secure-custom-fields' ); ?></label>
				</div>

				<div class="acf-fc-layout-name copyable">
					<span class="layout-name"></span>
				</div>

				<ul class="acf-bl acf-fl-actions">
					<li><button class="acf-btn acf-btn-tertiary acf-btn-sm acf-field-setting-fc-delete"><i class="acf-icon acf-icon-trash delete-layout " href="#" title="<?php esc_attr_e( 'Delete Layout', 'secure-custom-fields' ); ?>"></i></button></li>
					<li><button class="acf-btn acf-btn-tertiary acf-btn-sm acf-field-setting-fc-duplicate"><i class="acf-icon -duplicate duplicate-layout" href="#" title="<?php esc_attr_e( 'Duplicate Layout', 'secure-custom-fields' ); ?>"></i></button></li>
					<li class="acf-fc-add-layout"><button class="add-layout acf-btn acf-btn-primary add-field" href="#" title="<?php esc_attr_e( 'Add New Layout', 'secure-custom-fields' ); ?>"><i class="acf-icon acf-icon-plus"></i><?php esc_html_e( 'Add Layout', 'secure-custom-fields' ); ?></button></li>
					<li><button type="button" class="acf-toggle-fc-layout" aria-expanded="true"></li>
					<li><span class="toggle-indicator  <?php echo esc_attr( $toggle_class ); ?>" aria-hidden="true"></span></li>
				</ul>
			</div>
			<div class="acf-input acf-field-layout-settings <?php echo esc_attr( $field_settings_class ); ?>" style="<?php echo esc_attr( $field_settings_style ); ?>">
				<?php

				acf_hidden_input(
					array(
						'id'    => acf_idify( $layout_prefix . '[key]' ),
						'name'  => $layout_prefix . '[key]',
						'class' => 'layout-key',
						'value' => $layout['key'],
					)
				);

				?>
				<ul class="acf-fc-meta acf-bl">
					<li class="acf-fc-meta-label acf-fc-meta-left">
						<?php

						acf_render_field(
							array(
								'type'    => 'text',
								'name'    => 'label',
								'class'   => 'layout-label',
								'prefix'  => $layout_prefix,
								'value'   => $layout['label'],
								'prepend' => __( 'Label', 'secure-custom-fields' ),
							)
						);

						?>
					</li>
					<li class="acf-fc-meta-name acf-fc-meta-right copyable input-copyable">
						<?php

						acf_render_field(
							array(
								'type'       => 'text',
								'name'       => 'name',
								'class'      => 'layout-name',
								'input-data' => array( '1p-ignore' => 'true' ),
								'prefix'     => $layout_prefix,
								'value'      => $layout['name'],
								'prepend'    => __( 'Name', 'secure-custom-fields' ),
							)
						);

						?>
					</li>
					<li class="acf-fc-meta-display acf-fc-meta-left">
						<div class="acf-input-prepend"><?php esc_html_e( 'Layout', 'secure-custom-fields' ); ?></div>
						<div class="acf-input-wrap">
							<?php

							acf_render_field(
								array(
									'type'    => 'select',
									'name'    => 'display',
									'prefix'  => $layout_prefix,
									'value'   => $layout['display'],
									'class'   => 'acf-is-prepended',
									'choices' => array(
										'table' => __( 'Table', 'secure-custom-fields' ),
										'block' => __( 'Block', 'secure-custom-fields' ),
										'row'   => __( 'Row', 'secure-custom-fields' ),
									),
								)
							);

							?>
						</div>
					</li>
					<li class="acf-fc-meta-min">
						<?php

						acf_render_field(
							array(
								'type'    => 'text',
								'name'    => 'min',
								'prefix'  => $layout_prefix,
								'value'   => $layout['min'],
								'prepend' => __( 'Min', 'secure-custom-fields' ),
							)
						);

						?>
					</li>
					<li class="acf-fc-meta-max">
						<?php

						acf_render_field(
							array(
								'type'    => 'text',
								'name'    => 'max',
								'prefix'  => $layout_prefix,
								'value'   => $layout['max'],
								'prepend' => __( 'Max', 'secure-custom-fields' ),
							)
						);

						?>
					</li>
				</ul>
				<div class="acf-input-sub">
					<?php

					// vars
					$args = array(
						'fields'      => $layout['sub_fields'],
						'parent'      => $field['ID'],
						'is_subfield' => true,
					);

					acf_get_view( 'acf-field-group/fields', $args );

					?>
				</div>
			</div>
		</div>
				<?php
			}
		}

		/**
		 * Renders the field settings used in the "Presentation" tab.
		 *
		 * @since ACF 6.0
		 *
		 * @param array $field The field settings array.
		 * @return void
		 */
		public function render_field_presentation_settings( $field ) {

			// min
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Minimum Layouts', 'secure-custom-fields' ),
					'instructions' => '',
					'type'         => 'number',
					'name'         => 'min',
				)
			);

			// max
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Maximum Layouts', 'secure-custom-fields' ),
					'instructions' => '',
					'type'         => 'number',
					'name'         => 'max',
				)
			);

			// add new row label
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Button Label', 'secure-custom-fields' ),
					'instructions' => '',
					'type'         => 'text',
					'name'         => 'button_label',
				)
			);
		}


		/**
		 * This filter is applied to the $value after it is loaded from the db
		 *
		 * @type    filter
		 * @since   ACF 3.6
		 *
		 * @param  mixed $value   The value found in the database.
		 * @param  mixed $post_id The post_id from which the value was loaded.
		 * @param  array $field   The field array holding all the field options.
		 * @return $value
		 */
		public function load_value( $value, $post_id, $field ) {
			// bail early if no value
			if ( empty( $value ) || empty( $field['layouts'] ) ) {
				return $value;
			}

			// value must be an array
			$value = acf_get_array( $value );

			// vars
			$rows = array();

			// sort layouts into names
			$layouts = array();
			foreach ( $field['layouts'] as $k => $layout ) {
				$layouts[ $layout['name'] ] = $layout['sub_fields'];
			}

			// loop through rows
			foreach ( $value as $i => $l ) {

				// append to $values
				$rows[ $i ]                  = array();
				$rows[ $i ]['acf_fc_layout'] = $l;

				// bail early if layout doesn't contain sub fields
				if ( empty( $layouts[ $l ] ) ) {
					continue;
				}

				// get layout
				$layout = $layouts[ $l ];

				// loop through sub fields
				foreach ( array_keys( $layout ) as $j ) {

					// get sub field
					$sub_field = $layout[ $j ];

					// bail early if no name (tab)
					if ( acf_is_empty( $sub_field['name'] ) ) {
						continue;
					}

					// update full name
					$sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}";

					// get value
					$sub_value = acf_get_value( $post_id, $sub_field );

					// add value
					$rows[ $i ][ $sub_field['key'] ] = $sub_value;
				}
			}

			return $rows;
		}


		/**
		 * This filter is applied to the $value after it is loaded from the db and before it is returned to the template
		 *
		 * @type  filter
		 * @since ACF 3.6
		 *
		 * @param  mixed   $value       The value which was loaded from the database.
		 * @param  mixed   $post_id     The $post_id from which the value was loaded.
		 * @param  array   $field       The field array holding all the field options.
		 * @param  boolean $escape_html Should the field return a HTML safe formatted value.
		 * @return mixed   $value       The modified value.
		 */
		public function format_value( $value, $post_id, $field, $escape_html = false ) {

			// bail early if no value
			if ( empty( $value ) || empty( $field['layouts'] ) ) {
				return false;
			}

			// sort layouts into names
			$layouts = array();
			foreach ( $field['layouts'] as $k => $layout ) {
				$layouts[ $layout['name'] ] = $layout['sub_fields'];
			}

			// loop over rows
			foreach ( array_keys( $value ) as $i ) {

				// get layout name
				$l = $value[ $i ]['acf_fc_layout'];

				// bail early if layout doesn't exist
				if ( empty( $layouts[ $l ] ) ) {
					continue;
				}

				// get layout
				$layout = $layouts[ $l ];

				// loop through sub fields
				foreach ( array_keys( $layout ) as $j ) {

					// get sub field
					$sub_field = $layout[ $j ];

					// bail early if no name (tab)
					if ( acf_is_empty( $sub_field['name'] ) ) {
						continue;
					}

					// extract value
					$sub_value = acf_extract_var( $value[ $i ], $sub_field['key'] );

					// update $sub_field name
					$sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}";

					// format value
					$sub_value = acf_format_value( $sub_value, $post_id, $sub_field, $escape_html );

					// append to $row
					$value[ $i ][ $sub_field['_name'] ] = $sub_value;
				}
			}

			// return
			return $value;
		}


		/**
		 * Validates a flexible content field value.
		 *
		 * @type    function
		 * @date    11/02/2014
		 * @since   ACF 5.0.0
		 *
		 * @param   bool   $valid  The validation status.
		 * @param   mixed  $value  The value to validate.
		 * @param   array  $field  The field array.
		 * @param   string $input  The input element's name attribute.
		 * @return  mixed  The validation result.
		 */
		public function validate_value( $valid, $value, $field, $input ) {

			// vars
			$count = 0;

			// check if is value (may be empty string)
			if ( is_array( $value ) ) {

				// remove acfcloneindex
				if ( isset( $value['acfcloneindex'] ) ) {
					unset( $value['acfcloneindex'] );
				}

				// count
				$count = count( $value );
			}

			// validate required
			if ( $field['required'] && ! $count ) {
				$valid = false;
			}

			// validate min
			$min = (int) $field['min'];
			if ( $min && $count < $min ) {

				// vars
				$error      = __( 'This field requires at least {min} {label} {identifier}', 'secure-custom-fields' );
				$identifier = _n( 'layout', 'layouts', $min, 'secure-custom-fields' );

				// replace
				$error = str_replace( '{min}', $min, $error );
				$error = str_replace( '{label}', '', $error );
				$error = str_replace( '{identifier}', $identifier, $error );

				// return
				return $error;
			}

			// find layouts
			$layouts = array();
			foreach ( array_keys( $field['layouts'] ) as $i ) {

				// vars
				$layout = $field['layouts'][ $i ];

				// add count
				$layout['count'] = 0;

				// append
				$layouts[ $layout['name'] ] = $layout;
			}

			// validate value
			if ( $count ) {

				// loop rows
				foreach ( $value as $i => $row ) {
					// ensure row is an array
					if ( ! is_array( $row ) ) {
						continue;
					}

					// get layout
					$l = $row['acf_fc_layout'];

					// bail if layout doesn't exist
					if ( ! isset( $layouts[ $l ] ) ) {
						continue;
					}

					// increase count
					++$layouts[ $l ]['count'];

					// bail if no sub fields
					if ( empty( $layouts[ $l ]['sub_fields'] ) ) {
						continue;
					}

					// loop sub fields
					foreach ( $layouts[ $l ]['sub_fields'] as $sub_field ) {

						// get sub field key
						$k = $sub_field['key'];

						// bail if no value
						if ( ! isset( $value[ $i ][ $k ] ) ) {
							continue;
						}

						// validate
						acf_validate_value( $value[ $i ][ $k ], $sub_field, "{$input}[{$i}][{$k}]" );
					}
					// end loop sub fields
				}
				// end loop rows
			}

			// validate layouts
			foreach ( $layouts as $layout ) {

				// validate min / max
				$min   = (int) $layout['min'];
				$count = $layout['count'];
				$label = $layout['label'];

				if ( $min && $count < $min ) {

					// vars
					$error      = __( 'This field requires at least {min} {label} {identifier}', 'secure-custom-fields' );
					$identifier = _n( 'layout', 'layouts', $min, 'secure-custom-fields' );

					// replace
					$error = str_replace( '{min}', $min, $error );
					$error = str_replace( '{label}', '"' . $label . '"', $error );
					$error = str_replace( '{identifier}', $identifier, $error );

					// return
					return $error;
				}
			}

			// return
			return $valid;
		}


		/**
		 * This function will return a specific layout by name from a field
		 *
		 * @since   ACF 5.5.8
		 *
		 * @param  string $name  The layout name.
		 * @param  array  $field The field to load the layout from.
		 * @return array|false
		 */
		public function get_layout( $name, $field ) {

			// bail early if no layouts
			if ( ! isset( $field['layouts'] ) ) {
				return false;
			}

			// loop
			foreach ( $field['layouts'] as $layout ) {

				// match
				if ( $layout['name'] === $name ) {
					return $layout;
				}
			}

			// return
			return false;
		}


		/**
		 * This function will delete a value row
		 *
		 * @since   ACF 5.5.8
		 *
		 * @param   integer $i        The index of the row to delete.
		 * @param   array   $field    The field array containing all settings.
		 * @param   mixed   $post_id  The post ID where the value is saved.
		 * @return  boolean
		 */
		public function delete_row( $i, $field, $post_id ) {

			// vars
			$value = acf_get_metadata_by_field( $post_id, $field );

			// bail early if no value
			if ( ! is_array( $value ) || ! isset( $value[ $i ] ) ) {
				return false;
			}

			// get layout
			$layout = $this->get_layout( $value[ $i ], $field );

			// bail early if no layout
			if ( ! $layout || empty( $layout['sub_fields'] ) ) {
				return false;
			}

			// loop
			foreach ( $layout['sub_fields'] as $sub_field ) {

				// modify name for delete
				$sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}";

				// delete value
				acf_delete_value( $post_id, $sub_field );
			}

			// return
			return true;
		}

		/**
		 * This function will update a value row
		 *
		 * @since   ACF 5.5.8
		 *
		 * @param   array   $row      The row array to update.
		 * @param   integer $i        The index of the row to update.
		 * @param   array   $field    The field array containing all settings.
		 * @param   mixed   $post_id  The post ID where the value is saved.
		 * @return  boolean
		 */
		public function update_row( $row, $i, $field, $post_id ) {
			// bail early if no layout reference
			if ( ! is_array( $row ) || ! isset( $row['acf_fc_layout'] ) ) {
				return false;
			}

			// get layout
			$layout = $this->get_layout( $row['acf_fc_layout'], $field );

			// bail early if no layout
			if ( ! $layout || empty( $layout['sub_fields'] ) ) {
				return false;
			}

			foreach ( $layout['sub_fields'] as $sub_field ) {
				$value = null;

				if ( array_key_exists( $sub_field['key'], $row ) ) {
					$value = $row[ $sub_field['key'] ];
				} elseif ( array_key_exists( $sub_field['name'], $row ) ) {
					$value = $row[ $sub_field['name'] ];
				} else {
					// Value does not exist.
					continue;
				}

				// modify name for save
				$sub_field['name'] = "{$field['name']}_{$i}_{$sub_field['name']}";

				// update field
				acf_update_value( $value, $post_id, $sub_field );
			}

			return true;
		}

		/**
		 * This filter is applied to the $value before it is updated in the db
		 *
		 * @type    filter
		 * @since   ACF 3.6
		 *
		 * @param   mixed $value   The value which will be saved in the database.
		 * @param   mixed $post_id The post_id of which the value will be saved.
		 * @param   array $field   The field array holding all the field options.
		 * @return  mixed $value   The modified value
		 */
		public function update_value( $value, $post_id, $field ) {

			// bail early if no layouts
			if ( empty( $field['layouts'] ) ) {
				return $value;
			}

			// vars
			$new_value = array();
			$old_value = acf_get_metadata_by_field( $post_id, $field );
			$old_value = is_array( $old_value ) ? $old_value : array();

			// update
			if ( ! empty( $value ) ) {
				$i = -1;

				// remove acfcloneindex
				if ( isset( $value['acfcloneindex'] ) ) {
					unset( $value['acfcloneindex'] );
				}

				// loop through rows
				foreach ( $value as $row ) {
					++$i;

					// bail early if no layout reference
					if ( ! is_array( $row ) || ! isset( $row['acf_fc_layout'] ) ) {
						continue;
					}

					// delete old row if layout has changed
					if ( isset( $old_value[ $i ] ) && $old_value[ $i ] !== $row['acf_fc_layout'] ) {
						$this->delete_row( $i, $field, $post_id );
					}

					// update row
					$this->update_row( $row, $i, $field, $post_id );

					// append to order
					$new_value[] = $row['acf_fc_layout'];
				}
			}

			// vars
			$old_count = empty( $old_value ) ? 0 : count( $old_value );
			$new_count = empty( $new_value ) ? 0 : count( $new_value );

			// remove old rows
			if ( $old_count > $new_count ) {

				// loop
				for ( $i = $new_count; $i < $old_count; $i++ ) {
					$this->delete_row( $i, $field, $post_id );
				}
			}

			// save false for empty value
			if ( empty( $new_value ) ) {
				$new_value = '';
			}

			// return
			return $new_value;
		}


		/**
		 * Deletes a layout from a flexible content field.
		 *
		 * @type    function
		 * @date    1/07/2015
		 * @since   ACF 5.2.3
		 *
		 * @param   int    $post_id The post ID.
		 * @param   string $key    The field key.
		 * @param   array  $field  The field array.
		 * @return  void
		 */
		public function delete_value( $post_id, $key, $field ) {

			// vars
			$old_value = acf_get_metadata_by_field( $post_id, $field['name'] );
			$old_value = is_array( $old_value ) ? $old_value : array();

			// bail early if no rows or no sub fields
			if ( empty( $old_value ) ) {
				return;
			}

			// loop
			foreach ( array_keys( $old_value ) as $i ) {
				$this->delete_row( $i, $field, $post_id );
			}
		}


		/**
		 * This filter is applied to the $field before it is saved to the database
		 *
		 * @type    filter
		 * @since   ACF 3.6
		 *
		 * @param  array $field The field array holding all the field options.
		 * @return array $field The modified field
		 */
		public function update_field( $field ) {

			// loop
			if ( ! empty( $field['layouts'] ) ) {
				foreach ( $field['layouts'] as &$layout ) {
					unset( $layout['sub_fields'] );
				}
			}

			// return
			return $field;
		}


		/**
		 * Deletes a field and its sub fields.
		 *
		 * @type    function
		 * @date    4/04/2014
		 * @since   ACF 5.0.0
		 *
		 * @param   array $field The field array to delete.
		 * @return  void
		 */
		public function delete_field( $field ) {

			if ( ! empty( $field['layouts'] ) ) {

				// loop through layouts
				foreach ( $field['layouts'] as $layout ) {

					// loop through sub fields
					if ( ! empty( $layout['sub_fields'] ) ) {
						foreach ( $layout['sub_fields'] as $sub_field ) {
							acf_delete_field( $sub_field['ID'] );
						}
						// foreach
					}
					// if
				}
				// foreach
			}
			// if
		}


		/**
		 * This filter is applied to the $field before it is duplicated and saved to the database
		 *
		 * @type    filter
		 * @date    23/01/13
		 * @since   ACF 3.6
		 *
		 * @param   array $field The field array holding all the field options.
		 * @return  array The modified field.
		 */
		public function duplicate_field( $field ) {

			// vars
			$sub_fields = array();

			if ( ! empty( $field['layouts'] ) ) {

				// loop through layouts
				foreach ( $field['layouts'] as $layout ) {

					// extract sub fields
					$extra = acf_extract_var( $layout, 'sub_fields' );

					// merge
					if ( ! empty( $extra ) ) {
						$sub_fields = array_merge( $sub_fields, $extra );
					}
				}
				// foreach
			}

			// save field to get ID
			$field = acf_update_field( $field );

			// duplicate sub fields
			acf_duplicate_fields( $sub_fields, $field['ID'] );

			return $field;
		}


		/**
		 * Output the layout title for an AJAX response.
		 *
		 * @since ACF 5.3.2
		 */
		public function ajax_layout_title() {

			$options = acf_parse_args(
				$_POST, // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified elsewhere.
				array(
					'post_id'   => 0,
					'i'         => 0,
					'field_key' => '',
					'nonce'     => '',
					'layout'    => '',
					'value'     => array(),
				)
			);

			// load field
			$field = acf_get_field( $options['field_key'] );
			if ( ! $field ) {
				die();
			}

			// vars
			$layout = $this->get_layout( $options['layout'], $field );
			if ( ! $layout ) {
				die();
			}

			// title
			$title = $this->get_layout_title( $field, $layout, $options['i'], $options['value'] );

			// echo
			echo acf_esc_html( $title );
			die;
		}


		/**
		 * Get a layout title for a field.
		 *
		 * @param  array   $field  The field array.
		 * @param  array   $layout The layout array.
		 * @param  integer $i      The order number of the layout.
		 * @param  array   $value  The value of the layout.
		 * @return string The layout title, optionally filtered.
		 */
		public function get_layout_title( $field, $layout, $i, $value ) {

			// vars
			$rows       = array();
			$rows[ $i ] = $value;

			// add loop
			acf_add_loop(
				array(
					'selector' => $field['name'],
					'name'     => $field['name'],
					'value'    => $rows,
					'field'    => $field,
					'i'        => $i,
					'post_id'  => 0,
				)
			);

			// vars
			$title = $layout['label'];

			// filters
			$title = apply_filters( 'acf/fields/flexible_content/layout_title', $title, $field, $layout, $i );
			$title = apply_filters( 'acf/fields/flexible_content/layout_title/name=' . $field['_name'], $title, $field, $layout, $i );
			$title = apply_filters( 'acf/fields/flexible_content/layout_title/key=' . $field['key'], $title, $field, $layout, $i );

			// remove loop
			acf_remove_loop();

			// prepend order
			$order = is_numeric( $i ) ? $i + 1 : 0;
			$title = '<span class="acf-fc-layout-order">' . $order . '</span> ' . acf_esc_html( $title );

			return $title;
		}


		/**
		 * Updates clone field settings based on the original field.
		 *
		 * @type    function
		 * @date    28/06/2016
		 * @since   ACF 5.3.8
		 *
		 * @param   array $field       The field array.
		 * @param   array $clone_field The clone field array.
		 * @return  array The modified field.
		 */
		public function clone_any_field( $field, $clone_field ) {

			// remove parent_layout
			// - allows a sub field to be rendered as a normal field
			unset( $field['parent_layout'] );

			// attempt to merger parent_layout
			if ( isset( $clone_field['parent_layout'] ) ) {
				$field['parent_layout'] = $clone_field['parent_layout'];
			}

			// return
			return $field;
		}


		/**
		 * Handles preparing the field for export.
		 *
		 * @since   ACF 5.0.0
		 *
		 * @param  array $field The whole field array.
		 * @return array The export ready field array.
		 */
		public function prepare_field_for_export( $field ) {

			// loop
			if ( ! empty( $field['layouts'] ) ) {
				foreach ( $field['layouts'] as &$layout ) {
					$layout['sub_fields'] = acf_prepare_fields_for_export( $layout['sub_fields'] );
				}
			}

			// return
			return $field;
		}

		/**
		 * Prepares any field for export by removing unnecessary data.
		 *
		 * @since ACF 5.0.0
		 *
		 * @param array $field The field array.
		 * @return array The prepared field.
		 */
		public function prepare_any_field_for_export( $field ) {

			// remove parent_layout
			unset( $field['parent_layout'] );

			// return
			return $field;
		}


		/**
		 * Prepares the field for import.
		 *
		 * @type    function
		 * @date    11/03/2014
		 * @since   ACF 5.0.0
		 *
		 * @param   array $field The field array to prepare.
		 * @return  array The prepared field.
		 */
		public function prepare_field_for_import( $field ) {

			// Bail early if no layouts
			if ( empty( $field['layouts'] ) ) {
				return $field;
			}

			// Storage for extracted fields.
			$extra = array();

			// Loop over layouts.
			foreach ( $field['layouts'] as &$layout ) {

				// Ensure layout is valid.
				$layout = $this->get_valid_layout( $layout );

				// Extract sub fields.
				$sub_fields = acf_extract_var( $layout, 'sub_fields' );

				// Modify and append sub fields to $extra.
				if ( $sub_fields ) {
					foreach ( $sub_fields as $i => $sub_field ) {

						// Update attributes
						$sub_field['parent']        = $field['key'];
						$sub_field['parent_layout'] = $layout['key'];
						$sub_field['menu_order']    = $i;

						// Append to extra.
						$extra[] = $sub_field;
					}
				}
			}

			// Merge extra sub fields.
			if ( $extra ) {
				array_unshift( $extra, $field );
				return $extra;
			}

			// Return field.
			return $field;
		}


		/**
		 * This function will add compatibility for the 'column_width' setting.
		 *
		 * Unsure of reason for function name.
		 *
		 * @type    function
		 * @date    30/1/17
		 * @since   ACF 5.5.6
		 *
		 * @param   array $field Adds column width.
		 * @return  array
		 */
		public function validate_any_field( $field ) {

			// width has changed
			if ( isset( $field['column_width'] ) ) {
				$field['wrapper']['width'] = acf_extract_var( $field, 'column_width' );
			}

			// return
			return $field;
		}


		/**
		 * This function will translate field settings
		 *
		 * @type    function
		 * @date    8/03/2016
		 * @since   ACF 5.3.2
		 *
		 * @param   array $field The field array containing translation strings.
		 * @return  array The translated field array.
		 */
		public function translate_field( $field ) {

			// translate
			$field['button_label'] = acf_translate( $field['button_label'] );

			// loop
			if ( ! empty( $field['layouts'] ) ) {
				foreach ( $field['layouts'] as &$layout ) {
					$layout['label'] = acf_translate( $layout['label'] );
				}
			}

			// return
			return $field;
		}

		/**
		 * Additional validation for the flexible content field when submitted via REST.
		 *
		 * @param  bool  $valid The current validity boolean.
		 * @param  mixed $value The value of the field being validated.
		 * @param  array $field The field array containing all settings.
		 * @return bool|WP_Error Returns true if valid, WP_Error if validation fails.
		 */
		public function validate_rest_value( $valid, $value, $field ) {
			$param = sprintf( '%s[%s]', $field['prefix'], $field['name'] );
			$data  = array(
				'param' => $param,
				'value' => $value,
			);

			if ( ! is_array( $value ) && is_null( $value ) ) {
				/* translators: 1: Submitted value */
				$error = sprintf( __( '%s must be of type array or null.', 'secure-custom-fields' ), $param );
				return new WP_Error( 'rest_invalid_param', $error, $param );
			}

			$layouts_to_update = array_count_values( array_column( $value, 'acf_fc_layout' ) );

			foreach ( $field['layouts'] as $layout ) {
				$num_layouts = isset( $layouts_to_update[ $layout['name'] ] ) ? $layouts_to_update[ $layout['name'] ] : 0;

				if ( '' !== $layout['min'] && $num_layouts < (int) $layout['min'] ) {
					$error = sprintf(
						/* translators: 1: Field name, 2: Minimum number of layouts, 3: Layout name */
						_n(
							'%1$s must contain at least %2$s %3$s layout.',
							'%1$s must contain at least %2$s %3$s layouts.',
							$layout['min'],
							'secure-custom-fields'
						),
						$param,
						number_format_i18n( $layout['min'] ),
						$layout['name']
					);

					return new WP_Error( 'rest_invalid_param', $error, $data );
				}

				if ( '' !== $layout['max'] && $num_layouts > (int) $layout['max'] ) {
					$error = sprintf(
						/* translators: 1: field name, 2: minimum number of layouts, 3: layout name */
						_n(
							'%1$s must contain at most %2$s %3$s layout.',
							'%1$s must contain at most %2$s %3$s layouts.',
							$layout['max'],
							'secure-custom-fields'
						),
						$param,
						number_format_i18n( $layout['max'] ),
						$layout['name']
					);

					return new WP_Error( 'rest_invalid_param', $error, $data );
				}
			}

			return $valid;
		}

		/**
		 * Return the schema array for the REST API.
		 *
		 * @param  array $field The field array containing all settings.
		 * @return array The schema array for REST API.
		 */
		public function get_rest_schema( array $field ) {
			$schema = array(
				'type'     => array( 'array', 'null' ),
				'required' => ! empty( $field['required'] ),
				'items'    => array(
					'oneOf' => array(),
				),
			);

			// Loop through layouts building up a schema for each.
			foreach ( $field['layouts'] as $layout ) {
				$layout_schema = array(
					'type'       => 'object',
					'properties' => array(
						'acf_fc_layout' => array(
							'type'     => 'string',
							'required' => true,
							// By using a pattern match against the layout name, data sent in must match an available
							// layout on the flexible field. If it doesn't, a 400 Bad Request response will result.
							'pattern'  => '^' . $layout['name'] . '$',
						),
					),
				);

				foreach ( $layout['sub_fields'] as $sub_field ) {
					$sub_field_schema = acf_get_field_rest_schema( $sub_field );
					if ( $sub_field_schema ) {
						$layout_schema['properties'][ $sub_field['name'] ] = $sub_field_schema;
					}
				}

				$schema['items']['oneOf'][] = $layout_schema;
			}

			if ( ! empty( $field['min'] ) ) {
				$schema['minItems'] = (int) $field['min'];
			}

			if ( ! empty( $field['max'] ) ) {
				$schema['maxItems'] = (int) $field['max'];
			}

			return $schema;
		}

		/**
		 * Apply basic formatting to prepare the value for default REST output.
		 *
		 * @param  mixed          $value   The field value to format.
		 * @param  integer|string $post_id The post ID where the value is saved.
		 * @param  array          $field   The field array containing all settings.
		 * @return array|mixed The formatted value.
		 */
		public function format_value_for_rest( $value, $post_id, array $field ) {
			if ( empty( $value ) || ! is_array( $value ) || empty( $field['layouts'] ) ) {
				return null;
			}

			// Create a map of layout sub fields mapped to layout names.
			foreach ( $field['layouts'] as $layout ) {
				$layouts[ $layout['name'] ] = $layout['sub_fields'];
			}

			// Loop through each layout and within that, each sub field to process sub fields individually.
			foreach ( $value as &$layout ) {
				$name = $layout['acf_fc_layout'];

				if ( empty( $layouts[ $name ] ) ) {
					continue;
				}

				foreach ( $layouts[ $name ] as $sub_field ) {

					// Bail early if the field has no name (tab).
					if ( acf_is_empty( $sub_field['name'] ) ) {
						continue;
					}

					// Extract the sub field 'field_key'=>'value' pair from the $layout and format it.
					$sub_value = acf_extract_var( $layout, $sub_field['key'] );
					$sub_value = acf_format_value_for_rest( $sub_value, $post_id, $sub_field );

					// Add the sub field value back to the $layout but mapped to the field name instead
					// of the key reference.
					$layout[ $sub_field['name'] ] = $sub_value;
				}
			}

			return $value;
		}
	}


	// initialize
	acf_register_field_type( 'acf_field_flexible_content' );
endif; // class_exists check