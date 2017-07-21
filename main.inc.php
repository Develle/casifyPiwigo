<?php 
/*
Plugin Name: Casify
Version: 0.0.1
Description: Connect with CAS
Plugin URI: 
Author: Develle
Author URI: 
*/

/**
 * This is the main file of the plugin, called by Piwigo in "include/common.inc.php" line 137.
 * At this point of the code, Piwigo is not completely initialized, so nothing should be done directly
 * except define constants and event handlers (see http://piwigo.org/doc/doku.php?id=dev:plugins)
 */

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

if (basename(dirname(__FILE__)) != 'casify')
{
    add_event_handler('init', 'ouath_error');
    function casify_error()
    {
        global $page;
        $page['errors'][] = 'Casify folder name is incorrect, uninstall the plugin and rename it to "casify"';
    }
    return;
}

// +-----------------------------------------------------------------------+
// | Define plugin constants                                               |
// +-----------------------------------------------------------------------+

define('CASIFY_ID',      basename(dirname(__FILE__)));
define('CASIFY_PATH' ,   PHPWG_PLUGINS_PATH . CASIFY_ID . '/');
define('CASIFY_ADMIN',   get_root_url() . 'admin.php?page=plugin-' . CASIFY_ID);
define('CASIFY_DIR',     PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'casify/');
define('CASIFY_PHP_PATH', '/usr/share/php/CAS.php');

global $cas_conf;

// +-----------------------------------------------------------------------+
// | Add event handlers                                                    |
// +-----------------------------------------------------------------------+
// init the plugin
add_event_handler('init', 'casify_init');

/*
 * this is the common way to define event functions: create a new function for each event you want to handle
 */
if (defined('IN_ADMIN'))
{
    // file containing all admin handlers functions
    $admin_file = CASIFY_PATH . 'include/admin_events.inc.php';

    add_event_handler('get_admin_plugin_menu_links', 'casify_admin_plugin_menu_links',
                      EVENT_HANDLER_PRIORITY_NEUTRAL, $admin_file);

}
else
{
    // file containing all public handlers functions
    $public_file = CASIFY_PATH . 'include/public_events.inc.php';

    add_event_handler('loc_begin_identification', 'casify_begin_identification',
                      EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);
    add_event_handler('user_logout', 'casify_logout',
                      EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);
    add_event_handler('blockmanager_apply', 'casify_blockmanager',
                      EVENT_HANDLER_PRIORITY_NEUTRAL, $public_file);

}


/**
 * plugin initialization
 */
function casify_init()
{
    global $conf, $page, $template;

    // load plugin language file
    load_language('plugin.lang', CASIFY_PATH);
    
    // prepare plugin configuration
    $conf['casify'] = safe_unserialize($conf['casify']);
    
    if (defined('IN_ADMIN'))
    {
        if (!file_exists(CASIFY_PHP_PATH)) {
            $page['warnings'][] = l10n('Casify: PHP Cas extension is needed');
        }
    }


   
}
