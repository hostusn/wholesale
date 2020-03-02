<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Helendo
 */

get_header();
?>

    <div id="primary" class="content-area <?php helendo_content_columns(); ?>">
        <main id="main" class="site-main text-center">

            <section class="error-404 not-found">

                <div class="text-center"><span class="icon icon-confused"></span></div>
                <h1 class="page-title"><?php esc_html_e( 'ohh! page not found', 'helendo' ); ?></h1>

                <div class="page-content">
                    <p class="description"><?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help or go back to', 'helendo' ); ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'Homepage', 'helendo' ); ?></a>
                    </p>
                    <div class="search">
						<?php get_search_form(); ?>
                    </div>
                </div><!-- .page-content -->
            </section><!-- .error-404 -->

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
