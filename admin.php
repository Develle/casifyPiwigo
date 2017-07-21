<?php
/**
 * This is the main administration page, if you have only one admin page you can put
 * directly its code here or using the tabsheet system like bellow
 */

defined('CASIFY_PATH') or die('Hacking attempt!');

global $template, $page, $conf;

// include page
include(CASIFY_PATH . 'admin/config.php');

// template vars
$template->assign(array(
    'CASIFY_PATH'=> CASIFY_PATH, // used for images, scripts, ... access
        'CASIFY_ABS_PATH'=> realpath(CASIFY_PATH), // used for template inclusion (Smarty needs a real path)
        'CASIFY_ADMIN' => CASIFY_ADMIN,
        ));

// send page content
$template->assign_var_from_handle('ADMIN_CONTENT', 'casify_content');
