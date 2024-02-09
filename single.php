<?php 

    get_header();
    remove_filter('the_content', 'wpautop');
    $thumbnail_url = get_the_post_thumbnail_url();

    echo "<h1 class='single-title'>".get_the_title()."</h1>
        <img class='single-img' src='".esc_url($thumbnail_url)."' alt='Blog Post Image'>
        <p class='single-content>".the_content()."</p>";