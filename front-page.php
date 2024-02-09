 <?php get_header(); ?>
<?php 
    $counter = 0;
    $args = array(
        'post_type' => 'radi',
        'posts_per_page' => -1,
    );

    $custom_query = new WP_Query($args);
    if($custom_query->have_posts()){
        while($custom_query->have_posts()){
            $custom_query->the_post();
            $counter++;
            $thumbnail_url = get_the_post_thumbnail_url();
            $order = ($counter % 2 == 0) ? 'reverse-order' : '';
            echo "<div class = 'container ".$order."'>
                <div class='text box'>
                    <a href=".get_permalink()."><h1>".get_the_title()."</h1></a>
                    <p>".get_the_excerpt()."</p>
                </div>
                <div class='image box'>
                    <img src='".esc_url($thumbnail_url)."' alt='Sunset Background'>
                </div>
            </div>";
        };
    };
?> 
<?php get_footer(); ?>


