<?php
/**
 * @package     Expose
 * @version     4.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetFontresizer extends ExposeWidget{

    public $name = 'fontresizer';

    public function render()
    {
        global $expose;

        $selector    = (string) $this->get('selector');

        $js = "jQuery('$selector').jfontsize({ btnMinusClasseId: '#fr-m', btnDefaultClasseId: '#fr-d', btnPlusClasseId: '#fr-p' });";

        $expose->addLink('jquery.jfontsize.js', 'js');
        $expose->addjQDom($js);

        ob_start();
        ?>

        <div id="font-resizer" class="hidden-phone">
            <a class="fr-button" id="fr-m">A-</a>
            <a class="fr-button" id="fr-d">A</a>
            <a class="fr-button" id="fr-p">A+</a>
        </div>

    <?php
        return ob_get_clean();
    }
}

?>
