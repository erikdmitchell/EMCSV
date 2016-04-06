=== EMCSV ===

A CSV uploader class for WordPress. Designed to be used in plugins.
However, it does run on its own for now.

Need to test when a csv doesn't have a header

== Hooks and Filters ==

$wp_fields=apply_filters('emcsv_get_wordpress_fields', $wp_fields);
$custom_fields=apply_filters('emcsv_get_meta_keys_custom_fields', $wpdb->get_col($sql), $sql);
$meta_box_fields=apply_filters('emcsv_get_meta_keys_meta_box_fields', $meta_box_fields, $wp_meta_boxes);

$post_custom_fields=apply_filters('emcsv_add_row_to_db_custom_fields', $clean_row['custom_fields']);
$post_taxonomies=apply_filters('emcsv_add_row_to_db_taxonomies', $clean_row['taxonomies']);
$post_data=apply_filters('emcsv_add_row_to_db_data', $post_data, $clean_row);

== Changelog ==

= 0.1.1 =

 * Added $emcsv_active=true;
 * Added is_emcsv_active()
 * Added a lot of hooks and filters