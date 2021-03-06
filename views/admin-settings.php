<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   limelight
 * @author    7/Apps <ryan@7apps.com>
 * @license   GPL-2.0+
 * @link      http://www.7apps.com
 * @copyright 7-30-2014 7/Apps
 */

$options = get_option('limelight_options');
?>
<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <hr>

    <form method="post">
        <?php settings_fields('limelight_options'); ?>
        <?php do_settings_sections('limelight'); ?>

        <p class="submit">
            <input name="submit" type="submit" class="button-primary" value="<?php _e('Login', Limelight::$plugin_slug); ?>" />
        </p>
    </form>

    <hr>

    <h3><?php _e('API Sync', Limelight::$plugin_slug); ?></h3>
    <p>
        By default, this plugin will check the API when an admin page (such as this one) is loaded.
        During this check, the plugin will update the local form entires to match the Attendees data.
        Once this happens, the API will not be checked again until after a delay of <strong><?php print Limelight::$check_timeout / 60; ?> minutes</strong>.
    </p>
    <p>The next check will occur after: <strong><?php print date('F jS Y \a\t h:i a', get_transient(Limelight::$prefix.'attendee_check_timeout')); ?></strong></p>
    <p class="submit"><a href="?page=limelight&sync=1" class="button-primary">Sync Now</a></p>

    <?php if ($options['verified']) Limelight_GFFormList::form_list_page(); ?>

</div>
