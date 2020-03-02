<?php
/**
 * Hooks for share socials
 *
 * @package Helendo
 */

if ( ! function_exists( 'helendo_addons_share_link_socials' ) ) :
	function helendo_addons_share_link_socials( $socials, $title, $link, $media ) {
		$socials_html = '';

		if ( empty( $socials ) ) {
			return $socials_html;
		}

		foreach ( $socials as $social ) {
			if ( 'facebook' == $social ) {
				$socials_html .= sprintf(
					'<li><a class="share-facebook helendo-facebook" title="%s" href="http://www.facebook.com/sharer.php?u=%s&t=%s" target="_blank"><i class="social_facebook"></i></a></li>',
					esc_attr( $title ),
					urlencode( $link ),
					urlencode( $title )
				);
			}

			if ( 'twitter' == $social ) {
				$socials_html .= sprintf(
					'<li><a class="share-twitter helendo-twitter" href="http://twitter.com/share?text=%s&url=%s" title="%s" target="_blank"><i class="social_twitter"></i></a></li>',
					urlencode( $title ),
					urlencode( $link ),
					esc_attr( $title )
				);
			}

			if ( 'pinterest' == $social ) {
				$socials_html .= sprintf(
					'<li><a class="share-pinterest helendo-pinterest" href="http://pinterest.com/pin/create/button?media=%s&url=%s&description=%s" title="%s" target="_blank"><i class="social_pinterest"></i></a></li>',
					urlencode( $media ),
					urlencode( $link ),
					urlencode( $title ),
					esc_attr( $title )
				);
			}


			if ( 'google' == $social ) {
				$socials_html .= sprintf(
					'<li><a class="share-google-plus helendo-google-plus" href="https://plus.google.com/share?url=%s&text=%s" title="%s" target="_blank"><i class="social_googleplus"></i></a></li>',
					urlencode( $link ),
					urlencode( $title ),
					esc_attr( $title )
				);
			}


			if ( 'linkedin' == $social ) {
				$socials_html .= sprintf(
					'<li><a class="share-linkedin helendo-linkedin" href="http://www.linkedin.com/shareArticle?url=%s&title=%s" title="%s" target="_blank"><i class="social_linkedin"></i></a></li>',
					urlencode( $link ),
					urlencode( $title ),
					esc_attr( $title )
				);
			}


			if ( 'tumblr' == $social ) {
				$socials_html .= sprintf(
					'<li><a class="share-tumblr helendo-tumblr" href="http://www.tumblr.com/share/link?url=%s" title="%s" target="_blank"><i class="social_tumblr"></i></a></li>',
					urlencode( $link ),
					esc_attr( $title )
				);
			}


			if ( 'whatsapp' == $social ) {
				$socials_html .= sprintf(
					'<li><a class="share-whatsapp helendo-whatsapp" href="https://api.whatsapp.com/send?text=%s" title="%s" target="_blank"><i class="fa fa-whatsapp"></i></a></li>',
					urlencode( $link ),
					esc_attr( $title )
				);
			}


			if ( 'email' == $social ) {
				$socials_html .= sprintf(
					'<li><a class="share-email helendo-email" href="mailto:?subject=%s&body=%s" title="%s" target="_blank"><i class="fa fa-envelope"></i></a></li>',
					esc_html( $title ),
					urlencode( $link ),
					esc_attr( $title )
				);
			}
		}

		if ( $socials_html ) {
			return sprintf( '<ul class="helendo-social-share socials-inline">%s</ul>', $socials_html );
		}
		?>
		<?php
	}

endif;

