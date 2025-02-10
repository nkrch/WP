<?php
/*
Plugin Name: ProductsRating
Description: Display Rating in WordPress comment and product admin panel.
Author: A A
Version: 1.0
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ratingManager {

    public function __construct() {
        // Add the rating column to the comment list in the admin panel
        add_filter('manage_edit-comments_columns', [$this, 'add_rating_column'], 10, 1);

        // Display the rating in the custom column
        add_action('manage_comments_custom_column', [$this, 'display_rating_column'], 10, 2);

        // Enqueue script for handling AJAX requests (optional step)
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Handle saving rating for comments when posted
        add_action('comment_post', [$this, 'save_comment_rating'], 10, 2);

        // For Products
        add_filter('manage_edit-product_columns', [$this, 'add_rating_column_to_products'], 10, 1);

        // Display the rating in the custom column for products
        add_action('manage_product_posts_custom_column', [$this, 'display_rating_column_to_products'], 10, 2);

        // Handle saving rating for products
        add_action('save_post_product', [$this, 'save_product_rating'], 10, 3);
    }

    // Add the rating column in the comments admin panel
    public function add_rating_column($columns) {
        $columns['rating'] = 'Рейтинг'; // Column header for comment rating
        return $columns;
    }

    // Display the rating value for each comment
    public function display_rating_column($column, $comment_ID) {
        if ($column === 'rating') {
            // Get the rating from the comment's metadata
            $rating = get_comment_meta($comment_ID, 'rating', true);
            echo $rating ? $rating : '0'; // Display 0 if no rating found
        }
    }

    // Enqueue scripts for handling AJAX (optional step)
    public function enqueue_scripts($hook) {
        if ($hook === 'edit-comments.php') {
            wp_enqueue_script('rating-js', plugin_dir_url(__FILE__) . 'UpdRating.js', ['jquery'], null, true);
            wp_localize_script('rating-js', 'RatingAjax', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('update_rating_nonce'),
            ]);
        }
    }

    // Save the rating when a comment is posted
    public function save_comment_rating($comment_ID, $comment_approved) {
        if (isset($_POST['rating'])) {
            $rating = intval($_POST['rating']);
            // Save the rating as comment metadata
            update_comment_meta($comment_ID, 'rating', $rating);
        }
    }

    // Add the 'rating' column to the products admin list
    public function add_rating_column_to_products($columns) {
        $columns['rating'] = 'Рейтинг'; // Column header for product rating
        return $columns;
    }

    // Display the rating in the custom column for products
    public function display_rating_column_to_products($column, $post_ID) {
        if ($column === 'rating') {
            // Get the average rating of the product based on comment ratings
            $average_rating = $this->get_product_average_rating_from_comments($post_ID);

            // Display the average rating or 0 if no ratings are found
            echo $average_rating ? number_format($average_rating, 1) : '0';
        }
    }

    // Calculate the average rating for a product based on comments' ratings
    public function get_product_average_rating_from_comments($product_id) {
        global $wpdb;

        // Query to get all comments for this product (only approved ones)
        $comments = $wpdb->get_results($wpdb->prepare("
            SELECT comment_ID 
            FROM {$wpdb->prefix}comments 
            WHERE comment_post_ID = %d AND comment_approved = '1'",
            $product_id
        ));

        if (empty($comments)) {
            return 0; // Return 0 if no comments found
        }

        // Collect the ratings from the comments
        $ratings = [];
        foreach ($comments as $comment) {
            $rating = get_comment_meta($comment->comment_ID, 'rating', true);
            if ($rating) {
                $ratings[] = $rating;
            }
        }

        // Calculate and return the average rating
        if (empty($ratings)) {
            return 0;
        }

        $total_ratings = array_sum($ratings);
        $number_of_ratings = count($ratings);

        return $total_ratings / $number_of_ratings;
    }

    // Save the rating when a product is updated (or a new rating is added)
    public function save_product_rating($post_ID, $post, $update) {
        // Check if this is a product and not a revision
        if ($post->post_type !== 'product' || wp_is_post_revision($post_ID)) {
            return;
        }

        // Optionally, you could save or update product-specific ratings here
    }

}

// Initialize the plugin
new ratingManager();
?>
