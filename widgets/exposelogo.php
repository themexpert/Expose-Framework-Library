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

//import parent gist class
expose_import('core.widget');

class ExposeWidgetExposelogo extends ExposeWidget{

    public $name = 'exposelogo';


    public function render()
    {
        global $expose;
        $website = 'http://www.expose-framework.org';
        ob_start();
            ?>
            <div id="ex-poweredby" class="expose-logo expose-logo-<?php echo $this->get('type');?>">
                <a href="<?php echo $website ;?>" target="_blank" title="Powered By Expose Framework">
                    <span>Powered By Expose Framework</span>
                </a>
            </div>
    <?php
        return ob_get_clean();
    }
}

?>

