<?php

class Helendo_Nav_Menu_Widget extends WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Sets up a new Custom Menu widget instance.
	 *
	 * @since  3.0.0
	 * @access public
	 */
	public function __construct() {
		$this->defaults = array(
			'title'    => '',
			'nav_menu' => '',
			'display'  => 'vertical',
		);

		parent::__construct(
			'helendo-nav-menu',
			esc_html__( 'Helendo - Navigation', 'helendo' ),
			array(
				'classname'                   => 'helendo-nav-menu widget_nav_menu',
				'description'                 => esc_html__( 'Add a custom menu to your sidebar.', 'helendo' ),
				'customize_selective_refresh' => true,
			)
		);
	}

	/**
	 * Outputs the content for the current Custom Menu widget instance.
	 *
	 * @since  3.0.0
	 * @access public
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Custom Menu widget instance.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		extract( $args );


		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( ! $nav_menu ) {
			return;
		}

		echo wp_kses_post( $before_widget );

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
		}

		$nav_menu_args = array(
			'fallback_cb' => '',
			'menu'        => $nav_menu
		);

		/**
		 * Filters the arguments for the Custom Menu widget.
		 *
		 * @since 4.2.0
		 * @since 4.4.0 Added the `$instance` parameter.
		 *
		 * @param array $nav_menu_args {
		 *                                    An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
		 *
		 * @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
		 * @type mixed $menu Menu ID, slug, or name.
		 * }
		 *
		 * @param WP_Term $nav_menu Nav menu object for the current menu.
		 * @param array $args Display arguments for the current widget.
		 * @param array $instance Array of settings for the current widget.
		 */

		echo '<div class="navigation-wrapper ' . esc_attr( $instance['display'] ) . '">';

		if ( $nav_menu ) {
			wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );
		}

		echo '</div>';

		echo wp_kses_post( $after_widget );
	}

	/**
	 * Handles updating settings for the current Custom Menu widget instance.
	 *
	 * @since  3.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		if ( ! empty( $new_instance['display'] ) ) {
			$instance['display'] = strip_tags( $new_instance['display'] );
		}

		return $instance;
	}

	/**
	 * Outputs the settings form for the Custom Menu widget.
	 *
	 * @since  3.0.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 *
	 * @global WP_Customize_Manager $wp_customize
	 */
	public function form( $instance ) {
		global $wp_customize;
		$title    = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		$display  = isset( $instance['display'] ) ? $instance['display'] : '';

		// Get menus
		$menus = wp_get_nav_menus();

		// If no menus exists, direct the user to go and create some.
		?>
        <p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) {
			echo ' style="display:none" ';
		} ?>>
			<?php
			if ( $wp_customize instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( esc_html__( 'No menus have been created yet. <a href="%s">Create some</a>.', 'helendo' ), esc_attr( $url ) ); ?>
        </p>
        <div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) {
			echo ' style="display:none" ';
		} ?>>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'helendo' ) ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $title ); ?>"/>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"><?php esc_html_e( 'Select Menu:', 'helendo' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>">
                    <option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'helendo' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
                        <option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
                        </option>
					<?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"><?php esc_html_e( 'Display:', 'helendo' ) ?></label>
                <select class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>">
					<?php
					$vertical_selected   = ( $display == 'vertical' ) ? 'selected' : '';
					$horizontal_selected = ( $display == 'horizontal' ) ? 'selected' : ''
					?>
                    <option value='vertical'<?php echo esc_attr( $vertical_selected ); ?>><?php esc_html_e( 'Vertical', 'helendo' ) ?></option>
                    <option value='horizontal'<?php echo esc_attr( $horizontal_selected ); ?>><?php esc_html_e( 'Horizontal', 'helendo' ) ?></option>
                </select>
            </p>

			<?php if ( $wp_customize instanceof WP_Customize_Manager ) : ?>
                <p class="edit-selected-nav-menu" style="<?php if ( ! $nav_menu ) {
					echo 'display: none;';
				} ?>">
                    <button type="button" class="button"><?php esc_html_e( 'Edit Menu', 'helendo' ) ?></button>
                </p>
			<?php endif; ?>
        </div>
		<?php
	}
}
