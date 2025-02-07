<?php
/*
Plugin Name: PNWp
Description: It is a plugin. Just a plugin.
Author: A A
Version: 1.0
*/
?>



<?php
if (!defined('ABSPATH')) {
    exit; // Защита от прямого доступа
}

class cartStatusManager {
    public function __construct() {
        register_activation_hook(__FILE__, [$this, 'check_cart_post_type']);
        add_filter('manage_edit-cart_columns', [$this, 'add_cart_status_column']);
        add_action('manage_cart_posts_custom_column', [$this, 'display_cart_status_column'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_update_cart_status', [$this, 'update_cart_status']);
        add_action('manage_cart_posts_custom_column', [$this, 'count_and_display_priority_column'], 10, 2);
    }

    public function check_cart_post_type() {
        if (!post_type_exists('cart')) {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die('Тип записи "cart" не найден. Плагин деактивирован.');
        }
    }

    public function add_cart_status_column($columns) {
        $columns['cart_status'] = 'Статус';
        $columns['priority'] = 'Приоритет'; // Ensure it is correctly added
        return $columns;
    }

    public function display_cart_status_column($column, $post_id) {
        if ($column === 'cart_status' ) {
            $status = get_post_meta($post_id, 'cart_status', true) ?: '0';
            echo '<select class="cart-status" data-cart-id="' . esc_attr($post_id) . '" onchange="pnwpStart(' . esc_js($post_id) . ', this.value)">
                <option value="0" ' . selected($status, '0', false) . '>В ожидании</option>
                <option value="1" ' . selected($status, '1', false) . '>В работе</option>
                <option value="2" ' . selected($status, '2', false) . '>Выполнен</option>
                <option value="-1" ' . selected($status, '-1', false) . '>Отменён</option>
            </select>';
        }
    }

    public function count_and_display_priority_column($column, $post_id)
    {
        if ($column === 'priority') {
            // Fetch values from ACF
            $datenow = get_field('datenow');   // Order date
            $datetill = get_field('datetill'); // Deadline date
            $alertif = get_field('alertif');   // Urgency (true/false)
            $workif = get_field('workif');     // Needed in work (true/false)

            // Convert to proper types
            $datenow = strtotime($datenow);    // Convert to timestamp
            $datetill = strtotime($datetill);  // Convert to timestamp
            $today = strtotime(date('Y-m-d')); // Today's date

            $alertif = filter_var($alertif, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            $workif = filter_var($workif, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
//ПЕРЕДЕЛАТЬ
            // Calculate raw priority score (P)
            $P = (0.05 * ( $datenow/$today) / 86400)
                - (0.2 * ($datetill / $today) / 86400)
                + (0.5 * $workif)
                + (0.4 * $alertif);

            // Define min/max range for normalization
            $P_min = -10; // Adjust based on expected minimum raw P value
            $P_max = 10;  // Adjust based on expected maximum raw P value
            $scaled_min = 0.1;
            $scaled_max = 5;

            // Normalize P to the range [0.1, 5]
            $P_normalized = $scaled_min + ($P - $P_min) * ($scaled_max - $scaled_min) / ($P_max - $P_min);
            $P_normalized = max($scaled_min, min($P_normalized, $scaled_max)); // Ensure bounds

            // Display normalized priority
            echo round($P_normalized, 2);
        }
    }



    public function enqueue_scripts($hook) {
        if ($hook === 'edit.php' || $hook === 'post.php') {
            wp_enqueue_script('cart-status-js', plugin_dir_url(__FILE__) . 'PNWp.js', ['jquery'], null, true);
            wp_localize_script('cart-status-js', 'cartStatusAjax', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('update_cart_status_nonce'),
            ]);
        }
    }

    public function update_cart_status() {
        check_ajax_referer('update_cart_status_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => 'Insufficient permissions']);
        }

        $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
        $status  = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

        if ($cart_id && in_array($status, ['0', '1', '2', '-1'])) {
            update_post_meta($cart_id, 'cart_status', $status);
            wp_send_json_success(['message' => 'Status updated', 'status' => $status]);
        }

        wp_send_json_error(['message' => 'Update failed']);
    }
}

new cartStatusManager();
?>
