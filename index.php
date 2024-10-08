<?php

function custom_recent_posts_shortcode() {
        // Query for the first set of 4 posts
        $recent_posts = new WP_Query(array(
            'posts_per_page' => 4,
            'post_status' => 'publish',
        ));
    
        $default_image_url = '[DEFAULT-IMAGE-URL-HERE]';  
    
        ob_start(); ?>
        
        <div id="recent-posts-layout" class="recent-posts-layout">
            <div class="row">
                <?php $recent_posts->the_post(); // First post ?>
                <div class="first-post">
                    <div class="first-post-left">
                        <?php 
                        $is_external_link = get_field('external_link_checkbox') === 'Yes';
                        $external_link = get_field('external_link');
                        $link = $is_external_link && $external_link ? $external_link : get_permalink();
                        $target = $is_external_link ? ' target="_blank"' : '';  // Si es externo, abre en una nueva pestaña
    
                        if (has_post_thumbnail()) : ?>
                            <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>><?php the_post_thumbnail('medium'); ?></a>
                        <?php else : ?>
                            <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>>
                                <img src="<?php echo esc_url($default_image_url); ?>" alt="Default Image" />
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="first-post-right">
                        <div class="post-category-date">
                            <p class="post-category"><?php the_category(', '); ?></p>
                            <span style="color:#959595;">|</span>
                            <p class="post-date"><?php echo get_the_date('j M Y'); ?></p>
                        </div>
                        <div class="post-title">
                            <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>><?php the_title(); ?></a>
                        </div>
                        <div class="post-excerpt"><?php the_excerpt(); ?></div>
                        <div class="post-read-more">
                            <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>>Read More</a>
                        </div>
                    </div>
                </div>
            </div>
    
            <div id="recent-posts-container" class="row">
                <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                    <div class="col">
                        <?php 
                        $is_external_link = get_field('external_link_checkbox') === 'Yes';
                        $external_link = get_field('external_link');
                        $link = $is_external_link && $external_link ? $external_link : get_permalink();
                        $target = $is_external_link ? ' target="_blank"' : '';
    
                        if (has_post_thumbnail()) : ?>
                            <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>><?php the_post_thumbnail('medium'); ?></a>
                        <?php else : ?>
                            <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>>
                                <img src="<?php echo esc_url($default_image_url); ?>" alt="Default Image" />
                            </a>
                        <?php endif; ?>
                        <div class="post-category-date">
                            <p class="post-category"><?php the_category(', '); ?></p>
                            <span style="color:#959595;">|</span>
                            <p class="post-date"><?php echo get_the_date('j M Y'); ?></p>
                        </div>
                        <div class="post-title">
                            <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>><?php the_title(); ?></a>
                        </div>
                        <div class="post-excerpt"><?php the_excerpt(); ?></div>
                        <div class="post-read-more">
                            <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>>Read More</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    
        <div class="load-more-container">
            <button id="load-more-posts" data-page="1">Load More</button>
        </div>
        <div id="no-more-posts" style="display: none;"><i class="fa-solid fa-xmark"></i> No more posts to load</div>
    
        <?php wp_reset_postdata();
    
        return ob_get_clean();
    }
    
    function load_more_posts() {
        check_ajax_referer('load_more_nonce', 'security');
    
        $paged = $_POST['page'] + 1;
        $recent_posts = new WP_Query(array(
            'posts_per_page' => 3,
            'paged' => $paged,
            'post_status' => 'publish',
        ));
    
        $default_image_url = '[DEFAULT-IMAGE-URL-HERE]';  
    
        if ($recent_posts->have_posts()) :
            while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                <div class="col">
                    <?php 
                    $is_external_link = get_field('external_link_checkbox') === 'Yes';
                    $external_link = get_field('external_link');
                    $link = $is_external_link && $external_link ? $external_link : get_permalink();
                    $target = $is_external_link ? ' target="_blank"' : '';
    
                    if (has_post_thumbnail()) : ?>
                        <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>><?php the_post_thumbnail('medium'); ?></a>
                    <?php else : ?>
                        <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>>
                            <img src="<?php echo esc_url($default_image_url); ?>" alt="Default Image" />
                        </a>
                    <?php endif; ?>
                    <div class="post-category-date">
                        <p class="post-category"><?php the_category(', '); ?></p>
                        <span style="color:#959595;">|</span>
                        <p class="post-date"><?php echo get_the_date('j M Y'); ?></p>
                    </div>
                    <div class="post-title">
                        <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>><?php the_title(); ?></a>
                    </div>
                    <div class="post-excerpt"><?php the_excerpt(); ?></div>
                    <div class="post-read-more">
                        <a href="<?php echo esc_url($link); ?>"<?php echo $target; ?>>Read More</a>
                    </div>
                </div>
            <?php endwhile;
        else :
            echo 'no_more_posts';
        endif;
    
        wp_die();
    }
    
    add_shortcode('custom_recent_posts', 'custom_recent_posts_shortcode');
    add_action('wp_ajax_load_more_posts', 'load_more_posts');
    add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');
