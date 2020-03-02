<% if ( depth == 0 ) { %>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Mega Menu Content', 'helendo' ) ?>" data-panel="mega"><?php esc_html_e( 'Mega Menu', 'helendo' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Mega Menu Background', 'helendo' ) ?>" data-panel="background"><?php esc_html_e( 'Background', 'helendo' ) ?></a>
<div class="separator"></div>
<% } else if ( depth == 1 ) { %>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Content', 'helendo' ) ?>" data-panel="content"><?php esc_html_e( 'Menu Content', 'helendo' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu General', 'helendo' ) ?>" data-panel="general"><?php esc_html_e( 'General', 'helendo' ) ?></a>
<% } else { %>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu General', 'helendo' ) ?>" data-panel="general"><?php esc_html_e( 'General', 'helendo' ) ?></a>
<% } %>
