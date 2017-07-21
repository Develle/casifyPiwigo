<?php
defined('CASIFY_PATH') or die('Hacking attempt!');

/**
 * admin plugins menu link
 */
function casify_admin_plugin_menu_links($menu) 
{
    $menu[] = array(
        'NAME' => 'Casify',
        'URL' => CASIFY_ADMIN,
    );
    return $menu;
}

