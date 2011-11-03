<?php
/**
 * @package     Expose
 * @version     2.0  
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        copyright.php
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

if($this->get('copyright')){
    ob_start();
    ?>
    <div id="tx-copyright">
        <p>
            <?php echo ($this->get('copyright_text')==='EXPOSE_DEFAULT_COPYRIGHT') ? JText::_('EXPOSE_DEFAULT_COPYRIGHT')  : $this->get('copyright_text'); ?>
        </p>
    </div>
<?php
echo ob_get_clean();
}
?>
