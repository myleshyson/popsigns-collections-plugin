<?php

namespace App\Controllers;

/**
*
*/
class OrderController
{

    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'orderMetaBox']);
    }

    public function orderMetaBox()
    {
        add_meta_box('order_images', __('Order Images', 'woocommerce'), [$this, 'displayOrderImages'], 'shop_order', 'side', 'core');
    }


    public function displayOrderImages()
    {
        global $post, $pluginDir, $woocommerce;

        $order = new \WC_Order($post->ID);
        $order_id = trim(str_replace('#', '', $order->get_order_number()));
        $order = wc_get_order($post->ID);

        require $pluginDir."/views/admin-order.php";
    }
}
