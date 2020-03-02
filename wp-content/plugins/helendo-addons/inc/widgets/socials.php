<?php

class Helendo_Social_Links_Widget extends WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $default;

	/**
	 * List of supported socials
	 *
	 * @var array
	 */
	protected $socials;

	/**
	 * Constructor
	 */
	function __construct() {
		$socials = array(
			'facebook'   => esc_html__( 'Facebook', 'helendo' ),
			'twitter'    => esc_html__( 'Twitter', 'helendo' ),
			'googleplus' => esc_html__( 'Google Plus', 'helendo' ),
			'youtube'    => esc_html__( 'Youtube', 'helendo' ),
			'tumblr'     => esc_html__( 'Tumblr', 'helendo' ),
			'linkedin'   => esc_html__( 'Linkedin', 'helendo' ),
			'pinterest'  => esc_html__( 'Pinterest', 'helendo' ),
			'flickr'     => esc_html__( 'Flickr', 'helendo' ),
			'instagram'  => esc_html__( 'Instagram', 'helendo' ),
			'dribbble'   => esc_html__( 'Dribbble', 'helendo' ),
			'skype'      => esc_html__( 'Skype', 'helendo' ),
			'rss'        => esc_html__( 'RSS', 'helendo' )
		);

		$this->socials = apply_filters( 'helendo_social_media', $socials );
		$this->default = array(
			'title' => '',
			'desc'  => '',
		);

		foreach ( $this->socials as $k => $v ) {
			$this->default["{$k}_title"] = $v;
			$this->default["{$k}_url"]   = '';
		}

		parent::__construct(
			'helendo-social-links-widget',
			esc_html__( 'Helendo - Social Links', 'helendo' ),
			array(
				'classname'   => 'helendo-social-links-widget',
				'description' => esc_html__( 'Display links to social media networks.', 'helendo' ),
			),
			array( 'width' => 600 )
		);
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->default );

		echo wp_kses_post( $args['before_widget'] );

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
		}

		if ( $instance['desc'] ) {
			echo '<div class="socials-desc">' . $instance['desc'] . '</div>';
		}

		echo '<ul class="socials-list">';
		foreach ( $this->socials as $social => $label ) {
			if ( ! empty( $instance[$social . '_url'] ) ) {
				echo sprintf(
					'<li><a href="%s" class="share-%s tooltip-enable social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank"><i class="social social_%s"></i></a></li>',
					esc_url( $instance[$social . '_url'] ),
					esc_attr( $social ),
					esc_attr( $instance[$social . '_title'] ),
					esc_attr( $social )
				);
			}
		}
		echo '</ul>';

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->default );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'helendo' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				   value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"><?php esc_html_e( 'Description', 'helendo' ); ?></label>
            <textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"
					  name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>"><?php echo wp_kses( $instance['desc'], wp_kses_allowed_html( 'post' ) ) ?></textarea>
		</p>

		<?php
		foreach ( $this->socials as $social => $label ) {
			printf(
				'<div style="width: 280px; float: left; margin-right: 10px;">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
				$label,
				$this->get_field_name( $social . '_url' ),
				esc_attr__( 'URL', 'helendo' ),
				$instance[$social . '_url']
			);
		}
	}
}
