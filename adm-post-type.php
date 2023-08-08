<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register Custom Post Type
function adm_register_custom_post_type() {
    $labels = array(
        'name'               => _x( 'Downloads', 'post type general name', 'adm' ),
        'singular_name'      => _x( 'Download', 'post type singular name', 'adm' ),
        'menu_name'          => _x( 'Downloads', 'admin menu', 'adm' ),
        'name_admin_bar'     => _x( 'Download', 'add new on admin bar', 'adm' ),
        'add_new'            => _x( 'Add New', 'download', 'adm' ),
        'add_new_item'       => __( 'Add New Download', 'adm' ),
        'new_item'           => __( 'New Download', 'adm' ),
        'edit_item'          => __( 'Edit Download', 'adm' ),
        'view_item'          => __( 'View Download', 'adm' ),
        'all_items'          => __( 'All Downloads', 'adm' ),
        'search_items'       => __( 'Search Downloads', 'adm' ),
        'parent_item_colon'  => __( 'Parent Downloads:', 'adm' ),
        'not_found'          => __( 'No downloads found.', 'adm' ),
        'not_found_in_trash' => __( 'No downloads found in Trash.', 'adm' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'downloads' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ), // Add more supports if needed.
        'menu_icon'          => 'dashicons-download',
        'taxonomies'         => array( 'category', 'post_tag' ), // Add category and tag support.
    );

    register_post_type( 'download', $args );
}
add_action( 'init', 'adm_register_custom_post_type' );

function adm_modify_downloads_columns( $columns ) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'adm_thumbnail' => 'Thumbnail', // This will be the column header for Thumbnail
        'title' => 'Title',
        'categories' => 'Category',
        'tags' => 'Tags',
        'author' => 'Author',
        'update_date' => 'Last Modified', // You can change this to 'date' for Publish Date
    );

    return $new_columns;
}

add_filter( 'manage_download_posts_columns', 'adm_modify_downloads_columns' );

// Add content to the 'adm_thumbnail' and 'update_date' columns
function adm_custom_downloads_column_content( $column_name, $post_id ) {
    if ( $column_name === 'adm_thumbnail' ) {
        // Display the thumbnail for the 'adm_thumbnail' column
        echo get_the_post_thumbnail( $post_id, array( 60, 60 ) );
    }
    if ( $column_name === 'update_date' ) {
        // Display the last modified date for the 'update_date' column
        $date = get_the_modified_time( 'F j, Y ', $post_id );
        $time = get_the_modified_time( 'g:i a ', $post_id );
        echo $date."<br>".$time;
    }
}

add_action( 'manage_download_posts_custom_column', 'adm_custom_downloads_column_content', 10, 2 );


// for the 'update_date' column size
function custom_admin_column_styles() {
    echo '<style>
        .wp-list-table .column-title {
            width: 40%;
        }
    </style>';
}
add_action('admin_head', 'custom_admin_column_styles');





// Add custom meta box for download fields
function adm_add_download_meta_box() {
    add_meta_box(
        'adm_download_fields',
        'Download Details',
        'adm_render_download_fields',
        'download',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'adm_add_download_meta_box' );

// Render the custom meta box content
function adm_render_download_fields( $post ) {
    // Retrieve saved values (if any)
    $name_download_link = get_post_meta( $post->ID, 'name_download_link', true );
    $version = get_post_meta( $post->ID, 'version', true );
    $buy_now = get_post_meta( $post->ID, 'buy_now', true );
    ?>

    <label for="name_download_link">Name Download Link:</label>
    <input type="text" name="name_download_link" id="name_download_link" value="<?php echo esc_attr( $name_download_link ); ?>" style="width: 100%;">

    <label for="version">Version:</label>
    <input type="text" name="version" id="version" value="<?php echo esc_attr( $version ); ?>" style="width: 100%;">

    <label for="buy_now">Buy Now URL:</label>
    <input type="url" name="buy_now" id="buy_now" value="<?php echo esc_attr( $buy_now ); ?>" style="width: 100%;">
    <?php
}

// Save custom meta box data
function adm_save_download_meta( $post_id ) {
    if ( isset( $_POST['name_download_link'] ) ) {
        update_post_meta( $post_id, 'name_download_link', sanitize_text_field( $_POST['name_download_link'] ) );
    }

    if ( isset( $_POST['version'] ) ) {
        update_post_meta( $post_id, 'version', sanitize_text_field( $_POST['version'] ) );
    }

    if ( isset( $_POST['buy_now'] ) ) {
        update_post_meta( $post_id, 'buy_now', esc_url_raw( $_POST['buy_now'] ) );
    }
}
add_action( 'save_post_download', 'adm_save_download_meta' );



// Function to get most viewed downloads
function adm_get_most_viewed_downloads( $limit = 5 ) {
    $args = array(
        'post_type'      => 'download',
        'posts_per_page' => $limit,
        'meta_key'       => 'hit_count',
        'orderby'        => 'meta_value_num',
        'order'          => 'desc',
    );

    $most_viewed_downloads = new WP_Query( $args );
    return $most_viewed_downloads;
}
