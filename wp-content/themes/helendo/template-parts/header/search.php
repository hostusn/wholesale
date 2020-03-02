<?php
/**
 * Template part for displaying the search icon
 *
 * @package Helendo
 */

$search_type   = helendo_get_option( 'header_search_style' );
$header_layout = helendo_get_option( 'header_layout' );
$header_type   = helendo_get_option( 'header_type' );

$classes = array(
	'header-search',
);

$search_icon = true;

if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
	$search_icon = true;
	$classes[]   = 'icon';

} else {
	if ( $header_type == 'custom' ) {
		$classes[] = $search_type;

		if ( $search_type == 'form' ) {
			$classes[]   = 'search-modal';
			$search_icon = false;
		}

	} else {

		if ( $header_layout == 'v1' || $header_layout == 'v8' ) {
			$classes[]   = 'form';
			$classes[]   = 'search-modal';
			$search_icon = false;

		} else {
			$classes[] = 'icon';
		}
	}
}

?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php if ( $search_icon == true ) : ?>
		<span class="search-icon" data-toggle="modal" data-target="search-modal">
			<i class="icon-magnifier"></i>
		</span>
	<?php else: ?>
	<form method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
		<label>
			<input type="text" name="s" class="search-field" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e( 'Search anything...', 'helendo' ) ?>" autocomplete="off">
			<?php if ( 'product' == helendo_get_option( 'header_search_type' ) && function_exists( 'is_woocommerce' ) ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
			<span class="search-icon"><i class="icon-magnifier"></i></span>
		</label>
	</form>
	<div class="loading">
		<span class="helendo-loader"></span>
	</div>
	<div class="search-results">
		<?php if ( $search_type == 'form' ) : ?>
			<div class="submenu__arrow"></div>
		<?php endif; ?>
		<div class="searched-items"></div>
	</div>
<?php endif; ?>
</div>
