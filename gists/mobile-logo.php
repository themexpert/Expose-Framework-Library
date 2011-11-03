<?php
/**
 * @package     Expose
 * @version     2.0 
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        mobile-logo.php
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

if($this->layout->isMobile()){
?>
    <div id="mobile-header" class="mobile-logo">
        <div class="tx-block">
            <a href="<?php echo $this->baseUrl;?>" title="<?php echo $this->app->getCfg('sitename');?>">
                <span><?php echo $this->app->getCfg('sitename');?></span>
            </a>
        </div>
    </div>
<?php
}
?>
