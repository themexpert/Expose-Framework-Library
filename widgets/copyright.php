<?php
/**
 * @package     Expose
 * @version     2.0  
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetCopyright extends ExposeWidget{

    public $name = 'copyright';

    public function render()
    {
        ob_start();
            ?>
            <div id="ex-copyright">
                <p>
                    <?php echo ($this->get('text') ==='EXPOSE_DEFAULT_COPYRIGHT') ? JText::_('EXPOSE_DEFAULT_COPYRIGHT')  : $this->get('text'); ?>
                </p>
            </div>
        <?php
        echo ob_get_clean();
    }
}
?>
