<?php

/**
 * Plugin Name: ITS FluentCRM Email Attachment
 * Plugin URI:  https://itsgroup.co.nz/
 * Description: Intercepts FluentCRM mailer to allow shortcode-based file attachments via class alias.
 * Author: ITS Group
 * Author URI: https://itsgroup.co.nz/
 * Version: 1.1
 * Requires at least: 5.6
 * Requires PHP: 7.4
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: its-fluentcrm-email-attachment
 */

if (!defined('ABSPATH')) exit;

// 1. Load the override early before FluentCRM registers its Mailer
add_action('init', function () {
    require_once plugin_dir_path(__FILE__) . 'includes/mailer.php';

    if (!class_exists('FluentCrm\\App\\Services\\Libs\\Mailer\\Mailer', false)) {
        class_alias('ITSMailerOverride\\Mailer', 'FluentCrm\\App\\Services\\Libs\\Mailer\\Mailer');
    }
}, 0);

// 2. Register the [fc_attach] shortcode
add_shortcode('fc_attach', function ($atts) {
    $file = isset($atts['file']) ? esc_attr($atts['file']) : '';
    return $file ? "[[FC_ATTACH::{$file}]]" : '';
});

