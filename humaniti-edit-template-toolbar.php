<?php
/**
 * Main plugin class file
 *
 * @package Humaniti_Edit_Template_Toolbar
 */

/**
 * Plugin Name: Humaniti Edit Template Toolbar
 * Plugin URI: https://humaniti.co
 * Description: Adds an "Edit Template" button to the admin bar for block themes, allowing direct access to edit the current template in the site editor.
 * Version: 1.0.0
 * Author: Humaniti
 * Author URI: https://humaniti.co
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: humaniti-edit-template-toolbar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class that handles the Edit Template toolbar functionality
 */
class Humaniti_Edit_Template_Toolbar_Plugin {

	/**
	 * Initialize the plugin
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize plugin functionality
	 */
	public function init() {
		if ( ! $this->is_block_theme() || ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		add_action( 'admin_bar_menu', array( $this, 'add_edit_template_button' ), 50 );
		add_action( 'admin_head', array( $this, 'add_styles' ) );
		add_action( 'wp_head', array( $this, 'add_styles' ) );
	}

	/**
	 * Check if current theme is a block theme
	 *
	 * @return bool Whether the current theme is a block theme
	 */
	private function is_block_theme() {
		return function_exists( 'wp_is_block_theme' ) && wp_is_block_theme();
	}

	/**
	 * Add edit template button to the admin bar
	 *
	 * @param WP_Admin_Bar $wp_admin_bar The admin bar object.
	 * @return void
	 */
	public function add_edit_template_button( $wp_admin_bar ) {
		if ( ! is_admin() && current_user_can( 'edit_theme_options' ) ) {
			// Get all available block templates
			$templates = get_block_templates( array(), 'wp_template' );

			// Get current template
			$current_template = $this->get_current_template();

			// Add the main Edit Template button
			$wp_admin_bar->add_node(
				array(
					'id'    => 'edit-template',
					'title' => '<span class="ab-icon dashicons dashicons-screenoptions"></span>' . esc_html__( 'Edit Template', 'humaniti-edit-template-toolbar' ),
					'href'  => esc_url( $this->get_template_editor_url( $current_template ) ),
				)
			);

			// Add each template as a submenu item
			if ( ! empty( $templates ) ) {
				foreach ( $templates as $template ) {
					$wp_admin_bar->add_node(
						array(
							'id'     => 'edit-template-' . esc_attr( $template->slug ),
							'parent' => 'edit-template',
							'title'  => esc_html( $template->title->rendered ?? $template->slug ),
							'href'   => esc_url( $this->get_template_editor_url( $template->slug ) ),
							'meta'   => array(
								'class' => $template->slug === $current_template ? 'current-template' : '',
							),
						)
					);
				}
			}
		}
	}

	/**
	 * Add required CSS styles
	 */
	public function add_styles() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		?>
		<style>
			#wpadminbar .current-template,
			#wpadminbar .current-template .ab-item {
				background-color: #1d2327 !important;
			}
			
			#wpadminbar .current-template .ab-item,
			#wpadminbar .current-template:hover .ab-item {
				color: #FFBF00 !important;
			}
			
			#wpadminbar .current-template:hover,
			#wpadminbar .current-template:hover .ab-item {
				background-color: #FFBF00 !important;
				color: #1d2327 !important;
			}
		</style>
		<?php
	}

	/**
	 * Get the current template being used
	 *
	 * @return string Template slug
	 */
	private function get_current_template() {
		global $_wp_current_template_id;
		
		if ( $_wp_current_template_id ) {
			$parts = explode( '//', $_wp_current_template_id );
			if ( isset( $parts[1] ) ) {
				return $parts[1];
			}
		}
		
		return 'index';
	}

	/**
	 * Get the editor URL for a specific template
	 *
	 * @param string $template_slug The template slug.
	 * @return string The editor URL
	 */
	private function get_template_editor_url( $template_slug ) {
		$template_id = get_stylesheet() . '//' . $template_slug;
		
		return add_query_arg(
			array(
				'canvas'   => 'edit',
				'postId'   => $template_id,
				'postType' => 'wp_template',
			),
			admin_url( 'site-editor.php' )
		);
	}
}

// Initialize the plugin.
new Humaniti_Edit_Template_Toolbar_Plugin();
