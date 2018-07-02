<?php

namespace App\Controllers;

/**
* Handles Adding Custom Info to Product on Back And Front End
*/
class ProductController
{
    public function __construct()
    {
        add_filter('woocommerce_products_general_settings', [$this, 'addPriceSettings']);
        add_action('woocommerce_admin_field_image', [$this, 'imageHTML']);
        add_action('add_meta_boxes', array( $this, 'addMetaBox' ));
        add_action("save_post", [$this, 'saveProduct'], 10, 3);
    }

    public function addMetaBox($meta_boxes)
    {
        add_meta_box("vue-meta-box", "Live Preview Form", [$this, 'metaMarkup'], "product", "normal", "high", null);
    }

    public function metaMarkup($object)
    {
        global $wpdb;

        $table_name = $wpdb->prefix.'vue_forms';
        $selectedForm = get_post_meta($object->ID, 'vue-form', true);
        $formId = isset($selectedForm['id']) ? $selectedForm['id'] : 0;
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");
        $forms = $wpdb->get_results("SELECT * FROM {$table_name}");

        ?>
        <div>
           <label for="vue-form" class="product-meta-label">Select Form Associated With This Product</label>
           <select name="vue-form" id="vue-form" class="product-meta-select">
           <option value="">None</option>
            <?php foreach ($forms as $form) : ?>
                <option value="<?= $form->id; ?>" <?php echo intval($formId) == intval($form->id) ? "selected" : ""; ?>><?= $form->title; ?></option>
            <?php endforeach; ?>
           </select>
        </div>
    <?php
    }

    public function saveProduct($post_id, $post, $update)
    {
        global $wpdb;

        if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))) {
            return $post_id;
        }

        if (!current_user_can("edit_post", $post_id)) {
            return $post_id;
        }

        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
            return $post_id;
        }

        if ("product" != $post->post_type) {
            return $post_id;
        }

        if (isset($_POST["vue-form"]) && !empty($_POST['vue-form'])) {
            $id = intval($_POST["vue-form"]);
            $table_name = $wpdb->prefix.'vue_forms';
            $query = $wpdb->get_row("SELECT * FROM {$table_name} WHERE id={$id}");

            $form = [
                'id' => $id,
                'title' => $query->title,
                'headerText' => $query->header,
                'options' => unserialize($query->options),
                'colors' => unserialize($query->colors)
            ];
        } elseif (isset($_POST["vue-form"]) && empty($_POST['vue-form'])) {
            $form = [];
        }

        update_post_meta($post_id, "vue-form", $form);
    }

    public function addPriceSettings($settings)
    {
        $settings[] = array( 'name' => __('Live Preview Settings', 'vuecommerce'), 'type' => 'title', 'desc' => '', 'id' => 'woocommerce_vue_settings' );

        $settings[] = array(
            'title'     => __('Custom Text Price', 'vuecommerce'),
            'id'        => 'woocommerce_vue_text_field',
            'desc'      => __('Price For Custom Text On Signs', 'vuecommerce'),
            'type'      => 'number',
            'default'   => '',
            'desc_tip'  => false,
            'placeholder' => __('$5', 'vuecommerce'),
        );

        $settings[] = array(
            'title'     => __('Handwritten Text Price', 'vuecommerce'),
            'id'        => 'woocommerce_vue_handwritten_field',
            'desc'      => __('Price For Handwritten Signs', 'vuecommerce'),
            'type'      => 'number',
            'default'   => '',
            'desc_tip'  => false,
            'placeholder' => __('$45', 'vuecommerce'),
        );

        $settings[] = array(
            'title'     => __('Classic Mini Price', 'vuecommerce'),
            'id'        => 'woocommerce_vue_classic_mini_field',
            'desc'      => __('Price For Classic Mini Signs', 'vuecommerce'),
            'type'      => 'number',
            'default'   => '18',
            'desc_tip'  => false,
            'placeholder' => __('$18', 'vuecommerce'),
        );

        $settings[] = array(
            'title'     => __('Longboard Mini Price', 'vuecommerce'),
            'id'        => 'woocommerce_vue_longboard_mini_field',
            'desc'      => __('Price For Longboard Mini Signs', 'vuecommerce'),
            'type'      => 'number',
            'default'   => '25',
            'desc_tip'  => false,
            'placeholder' => __('$25', 'vuecommerce'),
        );

        $settings[] = array( 'type' => 'sectionend', 'id' => 'woocommerce_vue_settings');

        return $settings;
    }
}
