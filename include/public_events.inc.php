<?php

/**
 * logout
 */
function casify_logout()
{

    if (pwg_get_session_var('casify_user') === null) {
        return true;
    }

    pwg_unset_session_var('casify_user');
    logout_user();
    
    require_once CASIFY_PHP_PATH;
    
    global $conf;
    $config_cas = unserialize($conf['casify']);
    phpCAS::client(CAS_VERSION_2_0, $config_cas['cas_host'], $config_cas['cas_port'], '/cas', false);
    phpCAS::logout(['service' => 'http://'.$_SERVER['HTTP_HOST'].get_gallery_home_url() ]);
    
}


/**
 * identification menu block
 */
function casify_begin_identification()
{
    global $conf;

    if (!isset($_GET['auth']) or $_GET['auth']!='cas') {
        return true;
    }
    
    require_once CASIFY_PHP_PATH;
    phpCAS::client(CAS_VERSION_2_0, $conf['casify']['cas_host'], $conf['casify']['cas_port'], '/cas', false);
    phpCAS::setCasServerCACert('/etc/ssl/certs/UTN_USERFirst_Hardware_Root_CA.pem');
    phpCAS::forceAuthentication();
    
    $cas_id = phpCAS::getUser();
    
    // connected
    if (!empty($cas_id))
    {
        
        $mail_user = $cas_id."@unistra.fr";
        $pwd = base64_encode( hash_hmac('sha1', '', $conf['secret_key'].$cas_id,true) );
        
        // check is already registered
        if (!($user_id = get_userid($cas_id)))
        {
            register_user($cas_id, $pwd, $mail_user, false);
            $user_id = get_userid($cas_id);
        }
        
        
        if ( !  try_log_user($cas_id, $pwd, false) )
        {
            phpCAS::logout(['service'=> 'http://'.$_SERVER['HTTP_HOST'].get_gallery_home_url() ]);
        }
        
        pwg_set_session_var('casify_user', $cas_id);
    }
    
    redirect(get_gallery_home_url() );
}


/**
 * identification menu block
 */
function casify_blockmanager()
{
    global $template;
  
    $template->set_prefilter('menubar', 'casify_add_menubar_button');
}

function casify_add_menubar_button($content)
{

    $search = '#({include file=\$block->template\|@?get_extent:\$id ?})#';
    $add = file_get_contents(CASIFY_PATH . 'template/identification_menubar.tpl');
    $script = '
{footer_script require="jquery"}
jQuery("a[href=\"identification.php\"]").parent().parent().hide();
{/footer_script}';

    $content = preg_replace($search, '$1 '.$add, $content);
    return $content.$script;
}

