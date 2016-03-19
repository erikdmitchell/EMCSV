<?php
$emscvupload_template_folder='adminpages/';

/**
 * Retrieves a template part
 *
 * Taken from bbPress
 *
 * @param string $slug
 * @param string $name Optional. Default null
 *
 * @uses  emscvupload_locate_template()
 * @uses  load_template()
 * @uses  get_template_part()
 */
function emscvupload_get_template_part( $slug, $name = null, $atts=array(), $load = true ) {
	// Execute code for this part
	do_action( 'get_template_part_' . $slug, $slug, $name );

	// Setup possible parts
	$templates = array();
	if ( isset( $name ) )
		$templates[] = $slug . '-' . $name . '.php';
	$templates[] = $slug . '.php';

	// Allow template parts to be filtered
	$templates = apply_filters( 'emscvupload_get_template_part', $templates, $slug, $name );

	// Return the part that is found
	return emscvupload_locate_template( $templates, $load, false, $atts );
}
/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. If the template is
 * not found in either of those, it looks in the theme-compat folder last.
 *
 * Taken from bbPress
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true.
 *                            Has no effect if $load is false.
 * @return string The template filename if one is located.
 */
function emscvupload_locate_template( $template_names, $load = false, $require_once = true, $atts=array() ) {
	global $emscvupload_template_folder;

	// No file found yet
	$located = false;

	// Try to find a template file
	foreach ( (array) $template_names as $template_name ) {

		// Continue if template is empty
		if ( empty( $template_name ) )
			continue;

		// Trim off any slashes from the template name
		$template_name = ltrim( $template_name, '/' );

		// Check child theme first
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $emscvupload_template_folder . $template_name ) ) {
			$located = trailingslashit( get_stylesheet_directory() ) . $emscvupload_template_folder . $template_name;
			break;

		// Check parent theme next
		} elseif ( file_exists( trailingslashit( get_template_directory() ) . $emscvupload_template_folder . $template_name ) ) {
			$located = trailingslashit( get_template_directory() ) . $emscvupload_template_folder . $template_name;
			break;

		// Check theme compatibility last
		} elseif ( file_exists( trailingslashit( emscvupload_get_templates_dir() ) . $template_name ) ) {
			$located = trailingslashit( emscvupload_get_templates_dir() ) . $template_name;
			break;
		}
	}

	if ( ( true == $load ) && ! empty( $located ) )
		emscvupload_load_template( $located, $require_once, $atts );

	return $located;
}

/**
 * Require the template file with WordPress environment.
 *
 * The globals are set up for the template file to ensure that the WordPress
 * environment is available from within the function. The query variables are
 * also available.
 *
 * Mirrors the WP load_template() function.
 * However, we remove $require_once in place of an include_once to allow our variables
 *
 * @global array      $posts
 * @global WP_Post    $post
 * @global bool       $wp_did_header
 * @global WP_Query   $wp_query
 * @global WP_Rewrite $wp_rewrite
 * @global wpdb       $wpdb
 * @global string     $wp_version
 * @global WP         $wp
 * @global int        $id
 * @global WP_Comment $comment
 * @global int        $user_ID
 *
 * @param string $_template_file Path to template file.
 * @param bool   $include_once   Whether to include_once or include. Default true.
 */
function emscvupload_load_template( $_template_file, $include_once = true, $atts=array() ) {
    global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

    if ( is_array( $wp_query->query_vars ) ) {
            extract( $wp_query->query_vars, EXTR_SKIP );
    }

    if ( isset( $s ) ) {
            $s = esc_attr( $s );
    }

    if ( $include_once ) {
            include_once( $_template_file );
    } else {
            include( $_template_file );
    }
}

/**
 * emscvupload_get_templates_dir function.
 *
 * @access public
 * @return void
 */
function emscvupload_get_templates_dir() {
	global $emscvupload_template_folder;

	return plugin_dir_path(dirname(__FILE__)).$emscvupload_template_folder;
}
?>