<?php
/**
 * @package     Expose
 * @version     3.0.1
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import date utility class
jimport('joomla.utilities.date');

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
        $format = $this->get('formats');
        $date = $now->toFormat($format);

        ob_start();
        ?>
        <div id="ex-date">
            <?php echo $date; ?>
        </div>
    <?php
        return ob_get_clean();
    }
}

?>
