<?php

/**
 * Plugin Name:       wp-load-more
 * Plugin URI:        CONF_Plugin_Link
 * Description:       Create Load More for any Post Type
 * Version:           1.0.0
 * Author:            saptarshicodeclouds
 * Author URI:        https://github.com/saptarshicodeclouds
 * Text Domain:       wp-load-more
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
*/


defined('ABSPATH') or die("Direct access to the script does not allowed");

// Constants
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'PLUGIN_VERSION',  '1.0.0' );


// Import Styles & Scripts
function enqueue_scripts() {
    // Style
    wp_enqueue_script( 'custom-js', PLUGIN_URL . 'js/custom.js', array( 'jquery' ), PLUGIN_VERSION, true );
    wp_enqueue_script( 'bootstrap-js', PLUGIN_URL . 'js/bootstrap.min.js', array( 'jquery' ), PLUGIN_VERSION, true );

    // Script
    wp_enqueue_style( 'style-css',  PLUGIN_URL . 'css/style.css' );
    wp_enqueue_style( 'responsive-css',  PLUGIN_URL . 'css/responsive.css' );
    wp_enqueue_style( 'bootstrap-css',  PLUGIN_URL . 'css/bootstrap.min.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts');


// Shortcode
function wp_load_more_shortcode( $atts ) {
    $html = ''; // display html content

    extract(shortcode_atts(array(
        'post_type' => 'post',
        'posts_per_page' => 6,
        'post_status' => 'publish',
        'offset' => 0,
        'orderby' => 'date',
        'order' => 'DESC',
    ), $atts));

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'post_status' => $post_status,
        'offset' => $offset,
        'orderby' => $orderby,
        'order' => $order,
    );

    $wp_query = new WP_Query($args);

    $total_post = $wp_query->found_posts; // posts count

    if (!$wp_query->have_posts()) {
        return '<p>' . _e('Sorry, no ' .$post_type. ' found.') . '</p>';
    }
?>

    <div class="loader_div" style="display:none;">
        <div class="loader-container">
            <img src="<?php echo PLUGIN_URL ?>images/loader.gif" alt="" class="ajax-loader lazyloaded" />
        </div>
    </div>

<?php
    
    $html .= '<div class="container"><div id="loadmoresearchresult" class="row">';

    $post_count = 0;

    if ($wp_query->have_posts()) :
        while ($wp_query->have_posts()) : 
            $wp_query->the_post();
            
            $post_count++; // ...
            
            $html .= '<div class="col-lg-4">';
            $html .= '<a href="' . get_the_permalink() .'">';
            $html .= '<h3>' . get_the_title() . '</h3>';

            if (has_post_thumbnail()):
                $attachment_image = wp_get_attachment_url( get_post_thumbnail_id() );
                $featured_img_id = get_post_thumbnail_id();

                // the_post_thumbnail();

                $html.= '<img src="' . $attachment_image . '" alt="" />';
            else:
                $html .= '<img src="' . PLUGIN_URL . '/images/thumbnail-default.jpg" />';
            endif;

            $html .= '</a>';
            $html .= '</div>';
        endwhile;
    endif;

    $html .= '</div></div>';

    

    $html .= '<input type="hidden" value="' . $post_type . '" name="hidden_post_type" id="hidden_post_type">';
    $html .= '<input type="hidden" value="' . $post_count . '" name="hidden_post_no" id="hidden_post_no">';
    $html .= '<input type="hidden" value="' . $total_post . '" name="hidden_total_post" id="hidden_total_post"></div>';
    
    if ($total_post > $post_count) {
        $html .='<div class="load-more-button">';
        $html .='<a href="javascript:void(0);" class="btn-link portfolio_load_more"></a>';
        $html .='</div>';
    }

    echo $html;
}
add_shortcode( 'wp-load-more', 'wp_load_more_shortcode' );


// Portfolio Load More
function get_posts_with_view_more() {
    $html   = '';
    $status = false;
    $post_count = 0;

    $post_no       = $_POST['post_no'];
    $post_type     = $_POST['post_type'];
    $event_type    = $_POST['event_type'];

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'post_status' => $post_status,
        'offset' => $offset,
        'orderby' => $orderby,
        'order' => $order,
    );

    $wp_query = new WP_Query($args);

    if ($wp_query->have_posts()) :
        while ($wp_query->have_posts()) : 
            $wp_query->the_post();
            
            $post_count++; // ...
            $status = true;
            
            $html .= '<div class="col-lg-4">';
            $html .= '<a href="' . get_the_permalink() .'">';
            $html .= '<h3>' . get_the_title() . '</h3>';

            if (has_post_thumbnail()):
                $attachment_image = wp_get_attachment_url( get_post_thumbnail_id() );
                $featured_img_id = get_post_thumbnail_id();

                // the_post_thumbnail();

                $html.= '<img src="' . $attachment_image . '" alt="" />';
            else:
                $html .= '<img src="' . PLUGIN_URL . '/images/thumbnail-default.jpg" />';
            endif;

            $html .= '</a>';
            $html .= '</div>';
        endwhile;
    endif;

    $result = [
        'status' => $status,
        'html'   => $html,
    ];

    echo json_encode($result);
    wp_reset_postdata();
    exit;
}

add_action('wp_ajax_portfolio_load_more', 'get_posts_with_view_more');
add_action('wp_ajax_nopriv_portfolio_load_more', 'get_posts_with_view_more');