<?php
/**
 * @package     Expose
 * @version     2.0  
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        date.php
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.gist');

class ExposeGistDate extends ExposeGist{

    public $name = 'date';

    public function render()
    {

    }
}
/*
if($this->get('date')){
    $now = &JFactory::getDate();
    $formate = $this->get('formats');
    ob_start();
    ?>
    <div id="tx-date">
        <span><?php echo $now->toFormat($formate) ;?></span>
    </div>
<?php
echo ob_get_clean();
}*/

?>
