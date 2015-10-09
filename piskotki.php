<?php

/**
 * @package Piškotki
 * @version 0.3-alpha
 */

/*
Plugin Name: Piškotki
Version: 0.3-alpha
Author: Aljaž Jelen (Sibit d.o.o.)
Description: This is a plugin.
*/

defined('ABSPATH') or die('<h1>One does not simply try to access plugin files directly.</h1>');

define('PISKOTKI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PISKOTKI_PLUGIN_DIR', plugin_dir_path(__FILE__ ));

function piskotki_add_options_page()
{
    add_options_page('PISKOTKI', 'PISKOTKI', 'manage_options', 'piskotki', 'piskotki_settings_page');
}
add_action('admin_menu', 'piskotki_add_options_page');

function register_input_settings()
{
    register_setting('piskotki_input', 'message');
    register_setting('piskotki_input', 'dismiss');
    register_setting('piskotki_input', 'learnMore');
    register_setting('piskotki_input', 'link');
    //register_setting('piskotki_input', 'container');
    register_setting('piskotki_input', 'theme');
    //register_setting('piskotki_input', 'expiryDays');
}
if (is_admin())
{
    add_action('admin_init', 'register_input_settings');
}

function piskotki_settings_page()
{
?>

<div class="wrap">
    <h2>Piškotki</h2>
    <form action="options.php" method="POST">
        <?php
        settings_fields('piskotki_input');
        do_settings_sections('piskotki_input');
        ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Sporočilo</th>
                <td>
                    <textarea class="regular-text" name="message" rows="10" cols="50"><?php
                        echo esc_textarea(get_option('message'));
                    ?></textarea>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Opusti</th>
                <td><input class="regular-text" type="text" name="dismiss" value="<?php echo esc_attr(get_option('dismiss')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Prikaži več</th>
                <td><input class="regular-text" type="text" name="learnMore" value="<?php echo esc_attr(get_option('learnMore')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Povezava</th>
                <td>
                    <input class="regular-text" type="text" name="link" value="<?php echo esc_attr(get_option('link')); ?>" />
                    <p class="description">
                        <?php _e('Povezava naj vodi do WordPress strani, ki vsebuje pogoje uporabe tega spletnega mesta.'); ?>
                    </p>
                </td>
            </tr>
            <!-- <tr valign="top">
                <th scope="row">Kontejner</th>
                <td><input type="text" name="container" value="<?php //echo esc_attr(get_option('container')); ?>" /></td>
            </tr> -->
            <tr valign="top">
                <th scope="row">Izgled (CSS)</th>
                <td><input class="regular-text" type="text" name="theme" value="<?php echo esc_attr(get_option('theme')); ?>" /></td>
            </tr>
            <!-- <tr valign="top">
                <th scope="row">Veljavnost (v dnevih)</th>
                <td><input type="text" name="expiryDays" value="<?php //echo esc_attr(get_option('expiryDays')); ?>" /></td>
            </tr> -->
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>

<?php
}
/*
function echo_settings()
{
    $nl = "\n";

    $script = '<script>' . $nl;
    $script .= '    window.cookieconsent_options = {' . $nl;

    if (get_option('message') != null)    { $script .= "      message: '" . get_option('message') . "'," .       $nl; }
    if (get_option('dismiss') != null)    { $script .= "      dismiss: '" . get_option('dismiss') . "'," .       $nl; }
    if (get_option('learnMore') != null)  { $script .= "      learnMore: '" . get_option('learnMore') . "'," .   $nl; }
    if (get_option('link') != null)       { $script .= "      link: '" . get_option('link') . "'," .             $nl; }
    if (get_option('container') != null)  { $script .= "      container: '" . get_option('container') . "'," .   $nl; }
    if (get_option('theme') != null)      { $script .= "      theme: '" . get_option('theme') . "'," .           $nl; }
    if (get_option('expiryDays') != null) { $script .= "      expiryDays: '" . get_option('expiryDays') . "'," . $nl; }

    $script .= '    };' . $nl;
    $script .= '</script>';

    echo $script;
}
add_action('wp_footer', 'echo_settings');*/

function echo_popup_box()
{
    $nl = "\n";
    $popup  = '<div class="cc-popup">'                                                                     . $nl;
    $popup .= '    <p>' . get_option('message') . '</p>'                                                   . $nl;
    $popup .= '    <a href="' . get_option('link') . '">' . get_option('learnMore') . '</a>'               . $nl;
    $popup .= '    <div class="cc-dismiss">' . get_option('dismiss') . '</div>'                            . $nl;
    $popup .= '</div>'                                                                                     . $nl;

    echo $popup;
}
add_action('wp_footer', 'echo_popup_box');

function echo_options()
{
    $nl = "\n";
    $options  = '<div class="cc-options" onclick="OpenCookieConfDialog()"></div>' . $nl;

    echo $options;
}
add_action('wp_footer', 'echo_options');

function register_scripts_and_styles()
{
    wp_register_script(
        'js_cookies',
        plugins_url('js/js.cookie.js', __FILE__),
        array('jquery'),
        '2.0.3',
        true
    );
    wp_register_script(
        'conf',
        plugins_url('js/cookies.consent.js', __FILE__),
        array('jquery'),
        '1.0',
        true
    );
    wp_register_style(
        'cookies',
        plugins_url('styles/cookies.css', __FILE__)
    );

    wp_enqueue_script('js_cookies');
    wp_enqueue_script('conf');
    wp_enqueue_style('cookies');
}
add_action('wp_enqueue_scripts', 'register_scripts_and_styles');