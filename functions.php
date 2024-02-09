<?php

function support(){
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support( 'custom-logo' );
}
add_action('after_setup_theme','support');


register_nav_menus( array(
    'main_nav' => 'Main navigation'
) );




function load_custom_style() {
    wp_enqueue_style('custom', get_template_directory_uri() . "/style.css", array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'load_custom_style');


function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');



// ADDING CUSTOM POST TYPE

function custom_post_type() {

    $labels = array(
        'name'                  => _x( 'Custom posts', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'radi', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Custom Posts', 'text_domain' ),
        'name_admin_bar'        => __( 'Custom Post', 'text_domain' ),
        'archives'              => __( 'Item Archives', 'text_domain' ),
        'attributes'            => __( 'Item Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
        'all_items'             => __( 'All Items', 'text_domain' ),
        'add_new_item'          => __( 'Add New Item', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Item', 'text_domain' ),
        'edit_item'             => __( 'Edit Item', 'text_domain' ),
        'update_item'           => __( 'Update Item', 'text_domain' ),
        'view_item'             => __( 'View Item', 'text_domain' ),
        'view_items'            => __( 'View Items', 'text_domain' ),
        'search_items'          => __( 'Search Item', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
        'items_list'            => __( 'Items list', 'text_domain' ),
        'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Custom Post', 'text_domain' ),
        'description'           => __( 'Custom Post Type Description', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'radi', $args );

}
add_action( 'init', 'custom_post_type', 0 );


// ADDING POSTS TO CUSTOM POST TYPES
// add_action('init', 'get_github_repos');

function get_github_repos(){
    $githubUsername = 'laravel';

    $reposURL = 'https://api.github.com/users/'.$githubUsername.'/repos';

    $ch = curl_init($reposURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: PHP',
        "Authorization: Bearer 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",
        "Accept: application/vnd.github.v3+json"]);
    $rawResponse = curl_exec($ch);

    $reposData = json_decode($rawResponse, true);

    $curlError = curl_error($ch);
    curl_close($ch);

    if (!is_array($reposData)) {
        // Handle unexpected data format error
        $error = "Unexpected data format received from GitHub API";
        // Log or display the error message
        return;
    }

    $reposNames = array_map(function($repo) {
        if(is_array($repo)&& isset($repo['name'])) {
            return $repo['name'];
        }else{
            return null;
        }

    }, $reposData);

    foreach($reposNames as $name){
        $token = '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';
        
        $readeMeUrl = "https://api.github.com/repos/laravel/".$name."/contents/README.md";

        $headers = [
            'Authorization' => 'token ' . $token,
            'User-Agent' => 'YourApp',
        ];

        $response = wp_remote_get($readeMeUrl, array(
            'headers' => $headers,
        ));

        require_once '/Applications/MAMP/htdocs/fetch/wp-content/themes/fetch-tema/Parsedown.php';
        $parsedown = new Parsedown();

        $redaMeData = json_decode(wp_remote_retrieve_body($response));

   
            if(!empty($redaMeData->content)){
                $readeMeMd = base64_decode($redaMeData->content);

                $readeMeHtml = $parsedown->text($readeMeMd);
                $post_data = array(
                    'post_title'    => $name,
                    'post_content'  => $readeMeHtml, 
                    'post_status'   => 'publish',
                    'post_author'   => 1, 
                    'post_type'     => 'radi', 
                );

                  $post_id =  wp_insert_post($post_data);

                  //GET https://shot.screenshotapi.net/screenshot?token=TOKEN&url=URL&[OPTIONS]
                  $screenshot_url  = "https://shot.screenshotapi.net/screenshot?token=A79J2RQ-AJ2MBAQ-MNHJ2FH-78VEF7V&url=https://github.com/laravel/".$name."/blob/10.x/README.md&output=image&file_type=png";
                    $screenshot_response = file_get_contents($screenshot_url);

                        $upload = wp_upload_bits($name.'.png', null, $screenshot_response);
                        if(!$upload['error']){
                            $wo_filetype = wp_check_filetype($upload['file'], null);
                            $attachment = array(
                                'post_mime_type' => $wo_filetype['type'],
                                'post_title' => sanitize_file_name($upload['file']),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );

                            $attach_id = wp_insert_attachment($attachment, $upload['file']);
                            require_once(ABSPATH.'wp-admin/includes/image.php');

                            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                            wp_update_attachment_metadata($attach_id, $attach_data);

                            
                        }
                        set_post_thumbnail($post_id, $attach_id);
                    }

             }
    };


add_action('init', 'run_it_once');
function run_it_once(){
    if(did_action('init') >= 2) return;

    if(!get_option('run_get_github_repos')){
        get_github_repos();
        update_option('run_get_github_repos', true);
    }
}

