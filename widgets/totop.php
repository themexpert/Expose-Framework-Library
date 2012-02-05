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

    public function render()
    {
        global $expose;
        $js = "
            jQuery('#ex-scrolltop a').click(function () {
                jQuery('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });
        ";
        $expose->addjQDom($js);

        ob_start()
    ?>
    <p id="ex-scrolltop"><a href="#top"><span>Back to Top</span></a></p>

    <?php
        return ob_get_clean();
    }
}
?>

