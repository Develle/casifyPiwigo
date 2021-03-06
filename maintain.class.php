<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

/**
 * This class is used to expose maintenance methods to the plugins manager
 * It must extends PluginMaintain and be named "PLUGINID_maintain"
 * where PLUGINID is the directory name of your plugin.
 */
class casify_maintain extends PluginMaintain
{
    private $default_conf = array(
        'cas_host' => '',
        'cas_port' => 443,
    );
    
    private $dir;
    
    function __construct($plugin_id)
    {
        parent::__construct($plugin_id);
        $this->dir = PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'casify/';
    }
    
    /**
     * Plugin installation
     *
     * Perform here all needed step for the plugin installation such as create default config,
     * add database tables, add fields to existing tables, create local folders...
     */
    function install($plugin_version, &$errors=array())
    {
        global $conf;
        
        if (empty($conf['casify']))
        {
            // conf_update_param well serialize and escape array before database insertion
            // the third parameter indicates to update $conf['skeleton'] global variable as well
            conf_update_param('casify', $this->default_conf, true);
        }
        else
        {
             $old_conf = safe_unserialize($conf['casify']);

             if (empty($old_conf['cas_port']))
             { // use case: this parameter was added in a new version
                 $old_conf['cas_port'] = 443;
             }
             conf_update_param('casify', $old_conf, true);
        }
        
       
    }
    
    /**
     * Plugin activation
     *
     * This function is triggered after installation, by manual activation or after a plugin update
     * for this last case you must manage updates tasks of your plugin in this function
     */
    function activate($plugin_version, &$errors=array())
    {
    }
    
    /**
     * Plugin deactivation
     *
     * Triggered before uninstallation or by manual deactivation
     */
    function deactivate()
    {
    }
    
    /**
     * Plugin (auto)update
     *
     * This function is called when Piwigo detects that the registered version of
     * the plugin is older than the version exposed in main.inc.php
     * Thus it's called after a plugin update from admin panel or a manual update by FTP
     */
    function update($old_version, $new_version, &$errors=array())
    {
        
        $this->install($new_version, $errors);
    }
    
    /**
     * Plugin uninstallation
     *
     * Perform here all cleaning tasks when the plugin is removed
     * you should revert all changes made in 'install'
     */
    
    function uninstall()
    {
        conf_delete_param('casify');

        // delete local folder
        // use a recursive function if you plan to have nested directories
        foreach (scandir($this->dir) as $file)
        {
            if ($file == '.' or $file == '..') continue;
            unlink($this->dir.$file);
        }
        rmdir($this->dir);
    }
}
