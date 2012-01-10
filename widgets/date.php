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

        $date = new JDate();
        $format = $this->get('formats');
        $dates = explode(' ', $date->format($format));
        $i = 1;

        ob_start();
        ?>
        <div id="ex-date">
            <?php foreach($dates as $date) :?>
                <span class="part<?= $i?>"><?= $date ;?></span>
            <?php $i++; endforeach;?>
        </div>
    <?php
        return ob_get_clean();
    }
}

?>
