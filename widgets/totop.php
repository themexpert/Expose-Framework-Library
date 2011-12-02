<?php
/**
 * @package     Expose
 * @version     2.0   
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        totop.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetToTop extends ExposeWidget{

    public $name = 'totop';

    public function init()
    {
        ob_start()
    ?>
    <a href="#top" id="ex-scrolltop">Top</a>
    <?php
        $this->document->addScript($this->exposeUrl.'/interface/js/scrollTo.js');
        echo ob_get_clean();
    }
}
?>
