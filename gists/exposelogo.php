<?php
/**
 * @package     Expose
 * @version     2.0   
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        exposelogo.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.gist');

class ExposeGistExposeLogo extends ExposeGist{

    public $name = 'exposelogo';

    public function render()
    {
        // TODO: Implement render() method.
    }
}
/*
if($this->get('expose_logo')){
    ob_start();
    ?>
    <div id="tx-poweredby" class="expose-logo expose-logo-<?php echo $this->get('expose_logo_type');?>">
        <a href="http://expose.themexpert.com/" target="_blank" title="Powered By Expose Framework">
            <span>Powered By Expose Framework</span>
        </a>
    </div>
<?php
echo ob_get_clean();
}*/

?>

