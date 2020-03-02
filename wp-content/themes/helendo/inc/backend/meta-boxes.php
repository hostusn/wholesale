<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


/**
 * Enqueue script for handling actions with meta boxes
 *
 * @since 1.0
 *
 * @param string $hook
 */
function helendo_meta_box_scripts( $hook ) {
	// Detect to load un-minify scripts when WP_DEBUG is enable
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'helendo-meta-boxes', get_template_directory_uri() . "/js/backend/meta-boxes.js", array( 'jquery' ), '20180927', true );
	}
}

add_action( 'admin_enqueue_scripts', 'helendo_meta_box_scripts' );

/**
 * Registering meta boxes
 *
 * Using Meta Box plugin: http://www.deluxeblogtips.com/meta-box/
 *
 * @see http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 *
 * @param array $meta_boxes Default meta boxes. By default, there are no meta boxes.
 *
 * @return array All registered meta boxes
 */
function helendo_register_meta_boxes( $meta_boxes ) {
	// Display Settings
	$meta_boxes[] = array(
		'id'       => 'page-header-settings',
		'title'    => esc_html__( 'Page Header Settings', 'helendo' ),
		'pages'    => array( 'page', 'post', 'product' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Hide Page Header', 'helendo' ),
				'id'   => 'hide_page_header',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name' => esc_html__( 'Hide Breadcrumb', 'helendo' ),
				'id'   => 'hide_breadcrumb',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name' => esc_html__( 'Hide Title', 'helendo' ),
				'id'   => 'hide_title',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name' => esc_html__( 'Full Width', 'helendo' ),
				'id'   => 'full_width',
				'type' => 'checkbox',
				'std'  => false,
			),
		),
	);

	$meta_boxes[] = array(
		'id'       => 'product-videos',
		'title'    => esc_html__( 'Product Video', 'helendo' ),
		'pages'    => array( 'product' ),
		'context'  => 'side',
		'priority' => 'low',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Video URL', 'helendo' ),
				'id'   => 'video_url',
				'type' => 'oembed',
				'std'  => false,
				'desc' => esc_html__( 'Enter URL of Youtube or Vimeo or specific filetypes such as mp4, webm, ogv.', 'helendo' ),
			),
			array(
				'name'             => esc_html__( 'Video Thumbnail', 'helendo' ),
				'id'               => 'video_thumbnail',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'std'              => false,
				'desc'             => esc_html__( 'Add video thumbnail', 'helendo' ),
			),
			array(
				'name'    => esc_html__( 'Video Position', 'helendo' ),
				'id'      => 'video_position',
				'type'    => 'select',
				'options' => array(
					'1' => esc_html__( 'The last product gallery', 'helendo' ),
					'2' => esc_html__( 'The first product gallery', 'helendo' ),
				),
			),
		),
	);

	$sliders = helendo_get_rev_sliders();

	// Header Video
	$meta_boxes[] = array(
		'id'       => 'header-video',
		'title'    => esc_html__( 'Header Banner', 'helendo' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Show Header Banner', 'helendo' ),
				'id'   => 'header_banner',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name'    => esc_html__( 'Select Slider', 'helendo' ),
				'id'      => 'slider',
				'type'    => 'select',
				'options' => $sliders,
			),
		),
	);

	// Header Left Sidebar Item
	$meta_boxes[] = array(
		'id'       => 'header-left-sidebar-item',
		'title'    => esc_html__( 'Header Items', 'helendo' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name'            => esc_html__( 'Items', 'helendo' ),
				'id'              => 'header_items',
				'type'            => 'checkbox_list',
				'options'         => array(
					'search'   => esc_html__( 'Search', 'helendo' ),
					'wishlist' => esc_html__( 'Wishlist', 'helendo' ),
					'cart'     => esc_html__( 'Cart', 'helendo' ),
					'account'  => esc_html__( 'Account', 'helendo' ),
				),
				// Display options in a single row?
				'inline'          => true,
				// Display "Select All / None" button?
				'select_all_none' => true,
				'std'             => array( 'search', 'wishlist', 'cart', 'account' )
			)
		),
	);

	// Home Boxed Background
	$meta_boxes[] = array(
		'id'       => 'boxed-settings',
		'title'    => esc_html__( 'Home Boxed Background Settings', 'helendo' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name' => esc_html__( 'Background Color', 'helendo' ),
				'id'   => 'color',
				'type' => 'color',
			),
			array(
				'name'             => esc_html__( 'Background Image', 'helendo' ),
				'id'               => 'image',
				'type'             => 'image_advanced',
				'class'            => 'image',
				'max_file_uploads' => 1,
			),
			array(
				'name'    => esc_html__( 'Background Horizontal', 'helendo' ),
				'id'      => 'background_horizontal',
				'type'    => 'select',
				'std'     => '',
				'options' => array(
					''       => esc_html__( 'None', 'helendo' ),
					'left'   => esc_html__( 'Left', 'helendo' ),
					'center' => esc_html__( 'Center', 'helendo' ),
					'right'  => esc_html__( 'Right', 'helendo' ),
				),
			),
			array(
				'name'    => esc_html__( 'Background Vertical', 'helendo' ),
				'id'      => 'background_vertical',
				'type'    => 'select',
				'std'     => '',
				'options' => array(
					''       => esc_html__( 'None', 'helendo' ),
					'top'    => esc_html__( 'Top', 'helendo' ),
					'center' => esc_html__( 'Center', 'helendo' ),
					'bottom' => esc_html__( 'Bottom', 'helendo' ),
				),
			),
			array(
				'name'    => esc_html__( 'Background Repeat', 'helendo' ),
				'id'      => 'background_repeat',
				'type'    => 'select',
				'std'     => '',
				'options' => array(
					''          => esc_html__( 'None', 'helendo' ),
					'no-repeat' => esc_html__( 'No Repeat', 'helendo' ),
					'repeat'    => esc_html__( 'Repeat', 'helendo' ),
					'repeat-y'  => esc_html__( 'Repeat Vertical', 'helendo' ),
					'repeat-x'  => esc_html__( 'Repeat Horizontal', 'helendo' ),
				),
			),
			array(
				'name'    => esc_html__( 'Background Attachment', 'helendo' ),
				'id'      => 'background_attachment',
				'type'    => 'select',
				'std'     => '',
				'options' => array(
					''       => esc_html__( 'None', 'helendo' ),
					'scroll' => esc_html__( 'Scroll', 'helendo' ),
					'fixed'  => esc_html__( 'Fixed', 'helendo' ),
				),
			),
			array(
				'name'    => esc_html__( 'Background Size', 'helendo' ),
				'id'      => 'background_size',
				'type'    => 'select',
				'std'     => '',
				'options' => array(
					''        => esc_html__( 'None', 'helendo' ),
					'auto'    => esc_html__( 'Auto', 'helendo' ),
					'cover'   => esc_html__( 'Cover', 'helendo' ),
					'contain' => esc_html__( 'Contain', 'helendo' ),
				),
			),
		),
	);

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'helendo_register_meta_boxes' );
