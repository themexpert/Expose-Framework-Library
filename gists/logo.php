<?php
/**
 * @package     Expose
 * @version     2.0 
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        logo.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.gist');

class ExposeGistLogo extends ExposeGist{

    public $name = 'logo';

    public function render()
    {
        return 'gist-logo';
    }
}
/*
ob_start();
?>
    <div id="tx-logo">
        <div class="tx-block">
            <a href="<?php echo $this->baseUrl;?>" title="<?php echo $this->app->getCfg('sitename');?>">
                <span><?php echo $this->app->getCfg('sitename');?></span>
            </a>
        </div>
    </div>
<?php
echo ob_get_clean();
*/
?>



