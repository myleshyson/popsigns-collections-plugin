<?php

namespace App\Controllers;

/**
* Handles Ajax Requests for Front End
*/
class FrontEndController
{
    public function __construct()
    {
        add_action('wp_ajax_vue_add_to_cart', [$this, 'addToCart']);
        add_action('wp_ajax_nopriv_vue_add_to_cart', [$this, 'addToCart']);
        add_action('woocommerce_before_calculate_totals', [$this, 'updatePrice'], 999999999, 1);
        add_filter('woocommerce_add_cart_item_data', [$this, 'addCartMeta'], 1, 10);
        add_filter('woocommerce_get_item_data', [$this, 'displayCartMeta'], 1, 10);
        add_filter('wc_add_to_cart_message_html', [$this, 'cartMessage'], 90, 3);
        add_action('woocommerce_add_order_item_meta', [$this, 'addOrderMeta'], 1, 3);
        add_filter('woocommerce_package_rates', [$this, 'updateShippingCost'], 10, 2);
    }

    public function addToCart()
    {
        check_ajax_referer('csrf', 'nonce');

        if (!$_POST['side1']['approved'] || !$_POST['side2']['approved']) {
            throw new Exception('please approve your designs before submitting');
            die();
        }

        global $woocommerce;

        WC()->cart->add_to_cart($_POST['productId'], intval($_POST['quantity']));

        return wc_add_to_cart_message([$_POST['productId'] => $_POST['quantity']], true);
    }

    public function addCartMeta($cart_item_data, $product_id)
    {
        global $woocommerce;
        $data = $_POST;

        if (null !== $data['side1'] || null !== $data['side2']) {
            $price = $_POST['price'];
            $size = $_POST['side1']['size'];
            $new_value = [];
            $new_value['_vue_option']['isCustom'] = filter_var($_POST['isCustom'], FILTER_VALIDATE_BOOLEAN);
            $new_value['_vue_option']['darkHandImage'] = $_POST['darkHandImage'];
            $new_value['_vue_option']['lightHandImage'] = $_POST['lightHandImage'];
            $new_value['_vue_option']['side1'] = $data['side1'];
            $new_value['_vue_option']['side2'] = $data['side2'];
            $new_value['_vue_option']['price'] = $_POST['price'];
            $new_value['_vue_option']['size'] = $size;
            $new_value['_vue_option']['quantity'] = $_POST['quantity'];
            $new_value['_vue_option']['productId'] = $_POST['productId'];
        }
        
        $woocommerce->session->set('side1_logo', $data['side1']['logo']);
        $woocommerce->session->set('side1_hashtag', $data['side1']['hashtag']);
        $woocommerce->session->set('side1_logosize', $data['side1']['logoWidth']);
        $woocommerce->session->set('side2_logo', $data['side2']['logo']);
        $woocommerce->session->set('side2_hashtag', $data['side2']['hashtag']);
        $woocommerce->session->set('side2_logosize', $data['side2']['logoWidth']);

        if (empty($cart_item_data)) {
            return $new_value;
        } else {
            return array_merge($cart_item_data, $new_value);
        }
    }

    public function displayCartMeta($cart_data, $cart_item)
    {
        $custom_items = [];
        if (!empty($cart_data)) {
            $custom_items = $cart_data;
        }
        if (isset($cart_item['_vue_option'])) {
            if (filter_var($cart_item['_vue_option']['side1']['hasCustomText'], FILTER_VALIDATE_BOOLEAN)) {
                $custom_items[] = [
                'name' => __('Side 1 Text', 'vuecommerce'),
                'value' => $cart_item['_vue_option']['side1']['text']
                ];

                $custom_items[] = [
                'name' => __('Side 1 Text Type', 'vuecommerce'),
                'value' => '<span class="ttc">' . $cart_item['_vue_option']['side1']['textType'] . ' Lettering</span>'
                ];
            }

            $custom_items[] = [
            'name' => __('Side 1 Color', 'vuecommerce'),
            'value' => $cart_item['_vue_option']['side1']['color']['rgb']
            ];

            if (filter_var($cart_item['_vue_option']['side1']['hasLogoHashtag'], FILTER_VALIDATE_BOOLEAN)) {
                $custom_items[] = [
                'name' => __('Side 1 Logo', 'vuecommerce'),
                'value' => '<img style="max-width: 70px" src="' . $cart_item['_vue_option']['side1']['logo'] . '" >'
                ];

                $custom_items[] = [
                'name' => __('Side 1 Hashtag', 'vuecommerce'),
                'value' => $cart_item['_vue_option']['side1']['hashtag']
                ];
            }

            if (filter_var($cart_item['_vue_option']['side2']['hasCustomText'], FILTER_VALIDATE_BOOLEAN)) {
                $custom_items[] = [
                'name' => __('Side 2 Text', 'vuecommerce'),
                'value' => $cart_item['_vue_option']['side2']['text']
                ];

                $custom_items[] = [
                'name' => __('Side 2 Text Type', 'vuecommerce'),
                'value' => '<span class="ttc">' . $cart_item['_vue_option']['side2']['textType'] . ' Lettering</span>'
                ];
            }

            $custom_items[] = [
            'name' => __('Side 2 Color', 'vuecommerce'),
            'value' => $cart_item['_vue_option']['side2']['color']['rgb']
            ];

            if (filter_var($cart_item['_vue_option']['side2']['hasLogoHashtag'], FILTER_VALIDATE_BOOLEAN)) {
                $custom_items[] = [
                'name' => __('Side 2 Logo', 'vuecommerce'),
                'value' => '<img style="max-width: 70px" src="' . $cart_item['_vue_option']['side2']['logo'] . '" >'
                ];

                $custom_items[] = [
                'name' => __('Side 2 Hashtag', 'vuecommerce'),
                'value' => $cart_item['_vue_option']['side2']['hashtag']
                ];
            }
        }
        return $custom_items;
    }

    public function updateShippingCost($rates, $package)
    {
        if (isset($rates['flat_rate:2']) && WC()->cart->cart_contents_count < 2) {
            $rates['flat_rate:2']->cost = 14;
        }
        return $rates;
    }

    public function updatePrice($cart_object)
    {
        foreach ($cart_object->cart_contents as $cart_item_key => $value) {
            if (count($cart_object->cart_contents) < 2 && !isset($value['_vue_option'])) {
                $shipping_id = get_term_by('slug', 'one-item', 'product_shipping_class')->term_id;
                $value['data']->set_shipping_class_id($shipping_id);
                return false;
            }
            if (isset($value['_vue_option'])) {
                $value['data']->set_price($value['_vue_option']['price']);
                if (($value['_vue_option']['side1']['size'] == 'longboardMini' || $value['_vue_option']['side1']['size'] == 'classicMini') && !str_contains($value['data']->get_name(), 'Minis')) {
                    $newName = str_singular($value['data']->get_name()) . ' Minis';
                    $value['data']->set_name($newName);
                    if ($value['_vue_option']['side1']['size'] == 'longboardMini') {
                        $shipping_id = get_term_by('slug', 'longboard-minis', 'product_shipping_class')->term_id;
                        $value['data']->set_shipping_class_id($shipping_id);
                    } elseif ($value['_vue_option']['side1']['size'] == 'classicMini') {
                        $shipping_id = get_term_by('slug', 'minis', 'product_shipping_class')->term_id;
                        $value['data']->set_shipping_class_id($shipping_id);
                    }
                }
            }
        }
    }

    public function cartMessage($message, $product_id)
    {
        $product = new \WC_Product(key($product_id));
        if ($_POST['side1']['size'] == 'classicMini' || $_POST['side1']['size'] == 'longboardMini') {
            $newName = str_singular($product->get_name()) . ' Minis';
            $product->set_name($newName);
            $message = $product->get_name() . ' Successfully added to cart!';
        }
        $photo = wp_get_attachment_thumb_url($product->get_image_id());
        $added_to_cart = '<div class="product_notification_wrapper">';
        $added_to_cart .= '<img class="product_notification_background" src="' . $photo . '">';
        $added_to_cart .= '<div class="product_notification_text">' . $message . '</div> </div>';

        return $added_to_cart;
    }

    public function addOrderMeta($item_id, $values, $cart_item_key)
    {
        wc_add_order_item_meta($item_id, 'vueform', $values['_vue_option']);
    }
}
