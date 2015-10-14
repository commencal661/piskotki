<?php

/**
 * @package Piškotki
 * @version 1.2-beta
 */

/*
Plugin Name: Piškotki
Version: 1.1-beta
Author: Aljaž Jelen (Sibit d.o.o.)
Description: This is a plugin.
*/

// This is used to easily turn on and off all "experimental" features.
global $debug;
$debug = false;
//$debug = true;

defined('ABSPATH') or die('<h1>One does not simply try to access plugin files directly.</h1>');

define('PISKOTKI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PISKOTKI_PLUGIN_DIR', plugin_dir_path(__FILE__ ));

register_activation_hook(__FILE__, 'piskotki_create_page');
register_deactivation_hook(__FILE__, 'piskotki_remove_page');

function piskotki_add_options_page()
{
    add_options_page('PIŠKOTKI', 'PIŠKOTKI', 'manage_options', 'piskotki', 'piskotki_settings_page');
}
add_action('admin_menu', 'piskotki_add_options_page');

function piskotki_create_page()
{
	global $wpdb;

	$page_title = 'Piškotki';
	$page_name = 'piskotki';

	delete_option('piskotki_page_title');
	add_option('piskotki_page_title', $page_title, '', 'yes');

	delete_option('piskotki_page_name');
	add_option('piskotki_page_name', $page_name, '', 'yes');

	delete_option('piskotki_page_id');
	add_option('piskotki_page_id', '0', '', 'yes');

	$page = get_page_by_title($page_title);

	if (!$page)
	{
		$_p = array();
		$_p['post_title'] = $page_title;
		$_p['post_content'] = "To besedilo se lahko prepiše preko vtičnika. Ne urejajte ga tukaj!";
		$_p['post_status'] = 'publish';
		$_p['post_type'] = 'page';
		$_p['comment_status'] = 'closed';
		$_p['ping_status'] = 'closed';

		$page_id = wp_insert_post($_p);
	}
	else
	{
		$page_id = $page->ID;

		$page->post_status = 'publish';
		$page_id = wp_update_post($page);

		delete_option('piskotki_page_id');
		add_option('piskotki_page_id', $page_id);
	}
}

function piskotki_remove_page()
{
	global $wpdb;

	$page_title = get_option('piskotki_page_title');
	$page_name = get_option('piskotki_page_name');
	$page_id = get_option('piskotki_page_id');

	if ($page_id)
	{
		wp_delete_post($page_id);
	}

	delete_option('piskotki_page_title');
	delete_option('piskotki_page_name');
	delete_option('piskotki_page_id');
}

function get_id_by_slug($slug)
{
	$page = get_page_by_path($slug);
	if ($page) { return $page->ID; }
	else { return NULL; }
}

// This function overrides the content of the generated page.
function create_page_content($content)
{
	global $post;
	if ($post->ID == get_id_by_slug('piskotki'))
	{
		$entry = get_option('page');
		$entry .= '<br><div class="cc-delete">Izbriši piškotke</div>';
		$entry .= '<div class="cc-create">Ustvari piškotke</div>';

		return $entry;
	}

	return $content;
}
add_filter('the_content', 'create_page_content');

function piskotki_query_parser($query)
{
	$page_name = get_option('piskotki_page_name');
	$page_id = get_option('piskotki_page_id');

	$query_var = $query->query_vars;

	if (!$query->did_permalink && (isset($query_var['page_id'])) && (intval($query_var['page_id']) == $page_id))
	{
		$query->set('piskotki_page_is_called', true);
		return $query;
	}
	elseif (isset($query_var['pagename']) && (($query_var['pagename'] == $page_name) || ($_pos_found = strpos($query_var['pagename'], $page_name.'/') === 0)))
	{
		$query->set('piskotki_page_is_called', true);
		return $query;
	}
	else
	{
		$query->set('piskotki_page_is_called', false);
		return $query;
	}
}
add_filter('parse_query', 'piskotki_query_parser');

function register_input_settings()
{
	register_setting('piskotki_input', 'message');
	register_setting('piskotki_input', 'dismiss');
	register_setting('piskotki_input', 'learn_more');
	//register_setting('piskotki_input', 'link');
	register_setting('piskotki_input', 'theme');
	register_setting('piskotki_input', 'delete');
	register_setting('piskotki_input', 'page');
}

if (is_admin())
{
    add_action('admin_init', 'register_input_settings');
}

function piskotki_settings_page()
{
	global $debug;
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
                <td><input class="regular-text" type="text" name="learn_more" value="<?php echo esc_attr(get_option('learn_more')); ?>" /></td>
            </tr>
            <!-- <tr valign="top">
                <th scope="row">Povezava</th>
                <td>
                    <input class="regular-text" type="text" name="link" value="<?php //echo esc_attr(get_option('link')); ?>" />
                    <p class="description">
                        <?php //_e('Povezava naj vodi do WordPress strani, ki vsebuje pogoje uporabe tega spletnega mesta.'); ?>
                    </p>
                </td>
            </tr> -->
			<?php if ($debug == true): ?>
            <tr valign="top">
                <th scope="row">Izgled (CSS)</th>
                <td><input class="regular-text" type="text" name="theme" value="<?php echo esc_attr(get_option('theme')); ?>" /></td>
            </tr>
			<?php endif; ?>
            <tr valign="top">
                <th scope="row">Sporočilo (izbris piškotkov) </th>
                <td>
					<input class="regular-text" type="text" name="delete" value="<?php echo esc_attr(get_option('delete')); ?>" />
					<p class="description">
						<?php _e('Ta tekst se prikaže, ko se klikne na ikono plugina na spletni strani.'); ?>
					</p>
				</td>
            </tr>
			<tr valign="top">
				<th scope="row">Vsebina strani</th>
				<td>
                    <textarea class="regular-text" name="page" rows="12" cols="60"><?php
						echo esc_textarea(get_option('page'));
					?></textarea>
					<p class="description">
						<?php _e('Ta tekst se prikaže na strani z informacijami o piškotkih.'); ?>
					</p>
				</td>
			</tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>

<?php
}

// Frontend stuff
function echo_popup_box()
{
	$id = get_id_by_slug('piskotki');
	$link = get_page_link($id);

    $nl = "\n";
    $popup  = '<div class="cc-popup">'                                           . $nl;
    $popup .= '    <p>' . get_option('message') . '</p>'                         . $nl;
    $popup .= '    <a href="' . $link . '">' . get_option('learn_more') . '</a>' . $nl;
    $popup .= '    <div class="cc-dismiss">' . get_option('dismiss') . '</div>'  . $nl;
    $popup .= '</div>'                                                           . $nl;

    echo $popup;
}
add_action('wp_footer', 'echo_popup_box');

function echo_settings_box()
{
	$id = get_id_by_slug('piskotki');
	$link = get_page_link($id);

	$nl = "\n";
	$popup  = '<div class="cc-settings">'                                                        . $nl;
	$popup .= '    <p>' . get_option('delete') . '</p>'                                          . $nl;
	$popup .= '    <div class="cc-dismiss-no"><a href="' . $link . '">' . 'Nastavitve</a></div>' . $nl;
	$popup .= '</div>'                                                                           . $nl;

	echo $popup;
}
add_action('wp_footer', 'echo_settings_box');

function echo_options()
{
    $nl = "\n";
    $options  = '<div class="cc-options" onclick="OpenCookieOptions()"></div>' . $nl;

    echo $options;
}
add_action('wp_footer', 'echo_options');

// End of frontend stuff

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