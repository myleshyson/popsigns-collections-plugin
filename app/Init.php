<?php

namespace App;

class Init
{
    public function __construct()
    {
        add_action('init', [$this, 'removeCart']);
        add_filter('kses_allowed_protocols', [$this, 'protocols'], 10);
        add_filter('wp_kses_allowed_html', [$this, 'allowStyleTag'], 10, 3);
    }

    public function removeCart()
    {
        remove_action('woocommerce_cart_is_empty', 10, 3);
    }

    public function protocols($protocols)
    {
        $protocols[] = 'data';
        return $protocols;
    }

    public function allowStyleTag($tags, $context)
    {
        $tags['style'] = [];

        return  $tags;
    }
}
