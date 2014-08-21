<?php

class LimelightModel {

    public static function get_form_settings_table_name() {

        global $wpdb;
        return $wpdb->prefix . Limelight::$prefix . "form_settings";
    }

    /**
     * Setup the database tables
     */
    public static function setup_database() {

        global $wpdb;

        require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        //------ FORM -----------------------------------------------
        $form_settings_table_name = self::get_form_settings_table_name();
        $sql = "CREATE TABLE $form_settings_table_name (
                form_id mediumint(8) unsigned not null,
                settings longtext,
                PRIMARY KEY (form_id)
            ) $charset_collate;";
        dbDelta($sql);
    }

    public static function get_form_settings($id) {

        global $wpdb;

        $form_settings_table_name = self::get_form_settings_table_name();
        $sql = "SELECT *
                FROM $form_settings_table_name
                WHERE form_id = $id";

        $res = $wpdb->get_results($sql);

        // initialize form settings if none are found
        if (count($res) === 0) {
            $sql = "INSERT INTO $form_settings_table_name
                    VALUES ( $id, '{}' )";
            $wpdb->get_results($sql);
            self::get_form_settings($id);
        } else {
            $settings = json_decode($res[0]->settings);

            if (!isset($settings->event_id)) $settings->event_id = false;
            if (!isset($settings->action))   $settings->action   = '';

            return $settings;
        }
    }

    public static function update_form_settings($id, $settings) {

        global $wpdb;

        // ensure there is a form settings row in the table
        $s = self::get_form_settings($id);

        $settings = json_encode($settings);

        $form_settings_table_name = self::get_form_settings_table_name();
        $sql = "UPDATE $form_settings_table_name
                SET settings = '$settings'
                WHERE form_id = $id";
        $wpdb->get_results($sql);
    }

}
