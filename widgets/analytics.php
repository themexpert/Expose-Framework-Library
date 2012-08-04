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

class ExposeWidgetAnalytics extends ExposeWidget{

    public $name = 'analytics';

    public function isInMobile()
    {
        return TRUE;
    }

    public function init()
    {
        global $expose;
        ob_start();
    ?>
        // start of Google Analytics javascript
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?php echo $this->get('id'); ?>']);
        _gaq.push(['_trackPageview']);
        _gaq.push(['_trackPageLoadTime']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

      <?php
      $expose->document->addScriptDeclaration(ob_get_clean());
    }
}

?>