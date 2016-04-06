# em-csv-uploader #
A CSV uploader class for WordPress. Designed to be used in plugins.
However, it does run on its own for now.

Need to text when a csv doesn't have a header

0.1.1

$emcsv_active=true;

is_emcsv_active()

$wp_fields=apply_filters('emcsv_get_wordpress_fields', $wp_fields);
$custom_fields=apply_filters('emcsv_get_meta_keys_custom_fields', $wpdb->get_col($sql), $sql);
$meta_box_fields=apply_filters('emcsv_get_meta_keys_meta_box_fields', $meta_box_fields, $wp_meta_boxes);

$post_custom_fields=apply_filters('emcsv_add_row_to_db_custom_fields', $clean_row['custom_fields']);
$post_taxonomies=apply_filters('emcsv_add_row_to_db_taxonomies', $clean_row['taxonomies']);
$post_data=apply_filters('emcsv_add_row_to_db_data', $post_data, $clean_row);