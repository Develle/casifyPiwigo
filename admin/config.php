<?php
defined('CASIFY_PATH') or die('Hacking attempt!');

// +-----------------------------------------------------------------------+
// | Configuration tab                                                     |
// +-----------------------------------------------------------------------+

if (isset($_POST['save_config']))
{

	$conf['casify'] = array(
        'cas_host' => $_POST['cas_host'],
        'cas_port' => (int) $_POST['cas_port'],
    );
    
    conf_update_param('casify', $conf['casify']);
    $page['infos'][] = l10n('Information data registered in database');
}

// send config to template
$template->assign($conf['casify']);

// define template file
$template->set_filename('casify_content', realpath(CASIFY_PATH . 'admin/template/config.tpl'));
