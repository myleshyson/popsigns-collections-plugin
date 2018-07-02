<?php

namespace App\Database;

use Intervention\Image\ImageManager;



/**
* Responsible for loading and retrieving items from the database;
*/
class DatabaseHandler
{

    function __construct()
    {
        $this->installTableQuery();
    }

    public function installTableQuery()
    {
            global $wpdb;

            $table_name = $wpdb->prefix.'vue_forms';

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            options longblob NULL,
            colors blob NULL,
            header varchar(255) NOT NULL,
            is_custom bit(1),
            PRIMARY KEY  (id)
            ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            dbDelta( $sql );
    }



}