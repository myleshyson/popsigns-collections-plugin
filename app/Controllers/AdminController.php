<?php

namespace App\Controllers;

// use Aws\S3\S3Client;

/**
* Responsible for generating the admin view in wordpress
*/
class AdminController
{
    public $pages = [];

    public function __construct()
    {
        add_action('admin_post_delete_vueform', [$this, 'delete']);
        add_action('admin_menu', [$this, 'registerAdminMenu']);
        add_action('wp_ajax_store', [$this, 'store']);
        add_action('wp_ajax_update', [$this, 'update']);
        add_action('admin_enqueue_scripts', [$this, 'orderScripts']);
    }

    public function registerAdminMenu()
    {
        $this->pages[] = add_menu_page(
            'Popsigns Collections',
            'Popsigns Collections',
            'manage_options',
            'vuecommerce',
            [$this, 'index'],
            'dashicons-chart-pie',
            20
        );

        // $this->pages[] = add_submenu_page(
        //     'vuecommerce',
        //     'New Collection',
        //     'New Form',
        //     'manage_options',
        //     'vue-form-create',
        //     [$this, 'create']
        // );

        // $this->pages[] = add_submenu_page(
        //     null,
        //     'Update Collection',
        //     'Update',
        //     'manage_options',
        //     'vue-form-update',
        //     [$this, 'edit']
        // );

        $this->loadAssets();
    }

    public function loadAssets()
    {
        $pages = collect($this->pages);

        $pages->each(function ($page) {
            add_action('load-' . $page, [$this, 'enqueueAssets']);
        });
    }

    public function orderScripts()
    {
        global $post_type;
        if ($post_type == 'shop_order') {
            $this->enqueueAssets();
        }
    }

    public function enqueueAssets()
    {
        global $pluginDir, $pluginUrl;
        wp_register_style('popsign-forms-css', $pluginUrl . 'dist/app.css', null, '1.0', false);
        wp_register_script('popsign-forms-js', $pluginUrl . 'dist/app.js', ['jquery'], '1.0', true);
        wp_register_style('typekit', 'https://use.typekit.net/jdj8lbb.css', null, '1.0', false);
        wp_localize_script(
            'popsign-forms-js',
            'wp_admin',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('csrf'),
                'admin_url' => admin_url('admin.php')
            ]
        );
        wp_enqueue_media();
        wp_enqueue_style('typekit');
        wp_enqueue_style('popsign-forms-css');
        wp_enqueue_script('popsign-forms-js');
    }

    public function index()
    {
        ob_start(); ?>
		<div id="app-wrap" class="wrap">
			<div id="vue-app">hello</div>
		</div>
		<?php echo ob_get_clean();
    }

    public function create()
    {
        global $pluginDir;
        global $wpdb;

        $table_name = $wpdb->prefix . 'vue_forms';

        require $pluginDir . '/views/create.php';
    }

    public function store()
    {
        require_once ABSPATH . 'wp-load.php';

        global $wpdb;

        $table_name = $wpdb->prefix . 'vue_forms';

        $data = [
          'title' => $_POST['title'],
          'options' => json_encode($_POST['options']),
          'colors' => json_encode($_POST['colors']),
          // 'header' => $_POST['headerText'],
          'is_custom' => filter_var($_POST['isCustom'], FILTER_VALIDATE_BOOLEAN)
        ];

        $wpdb->insert($table_name, $data);
    }

    public function edit()
    {
        global $pluginDir;
        global $wpdb;

        $table_name = $wpdb->prefix . 'vue_forms';
        $id = $_GET['id'];

        $form = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id=%s", $id))[0];
        $title = $form->title;
        $header = $form->header;
        $custom = $form->is_custom;
        $options = preg_replace("/\n/m", '\n', htmlentities(str_replace('\\', '', stripcslashes($form->options))));

        $colors = htmlentities(str_replace('\\', '', stripcslashes($form->colors)));

        require $pluginDir . '/views/update.php';
    }

    public function update()
    {
        require_once ABSPATH . 'wp-load.php';

        global $wpdb;
        $table_name = $wpdb->prefix . 'vue_forms';
        $data = [
            'title' => $_POST['title'],
            'options' => json_encode($_POST['options']),
            'colors' => json_encode($_POST['colors']),
            // 'header' => $_POST['headerText'],
            'is_custom' => filter_var($_POST['isCustom'], FILTER_VALIDATE_BOOLEAN),
        ];

        $wpdb->update($table_name, $data, ['id' => intval($_POST['id'])]);
    }

    public function delete()
    {
        global $pluginDir, $wpdb;
        $table_name = $wpdb->prefix . 'vue_forms';
        $id = $_POST['id'];
        $form = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id=%s", $id))[0];
        $options = collect($form->options);

        $options->each(function ($option) {
            unlink($option['filePath']);
        });

        $wpdb->delete($table_name, ['id' => $id]);

        header('Location: ' . admin_url('admin.php?page=vuecommerce'));
    }
}
