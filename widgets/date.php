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

class ExposeWidgetDate extends ExposeWidget{

    public $name = 'date';

    public function isInMobile()
    {
        return FALSE;
    }

    public function render()
    {
        global $expose;
        $now = &JFactory::getDate();
        $formate = $this->get('formats');

        ob_start();
        ?>
        <div id="ex-date">
            <span><?php echo $now->toFormat($formate) ;?></span>
        </div>
    <?php
        return ob_get_clean();
    }
}

?>
