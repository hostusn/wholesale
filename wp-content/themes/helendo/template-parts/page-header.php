<?php /**
 * Template part for displaying the page header
 *
 * @package Helendo
 */
$page_header = helendo_get_page_header();
if ( ! $page_header ) {
	return;
}

if ( helendo_is_maintenance_page() ) {
	return;
}

$css_classes = array(
	'page-header',
	helendo_is_catalog() ? 'page-header-catalog' : ''
);
if ( ! in_array( 'title', $page_header ) ) {
	$css_classes[] = 'hide-title';
}

$container_class = 'container';
if( in_array( 'full_width', $page_header ) ) {
    $container_class = 'helendo-container';
}

?>

<div class="<?php echo esc_attr( implode( ' ', $css_classes ) ); ?>">
    <div class="<?php echo esc_attr($container_class); ?>">
        <div class="page-header-content">
			<?php
            if( is_singular('post') ) {
	            the_archive_title( '<h2 class="page-title">', '</h2>' );
            } else {
	            the_archive_title( '<h1 class="page-title">', '</h1>' );
            }
			if ( in_array( 'breadcrumb', $page_header ) ) {
				helendo_breadcrumbs();
			}
			?>
        </div>
    </div>
</div>