<?php
/**
 * @package     Expose
 * @version     2.0   
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @filesource
 * @file        lazyloader.php
 **/

//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

//import parent gist class
expose_import('core.widget');

class ExposeWidgetLazyLoader extends ExposeWidget{

    public $name = 'lazyloader';

    public function init()
    {
        $js ='jQuery(\'img\').lazyload({effect: "fadeIn",threshold : 100});';
        $this->document->addScript($this->exposeUrl.'/interface/js/lazyload.js');
        $this->addjQDom($js);
    }
}
/*
if($this->get('lazy_loader')){
    //due to lazyloader bug for mobile devices, we will not load it on mobile device
    if(!$this->layout->isMobile()){
        $js ='jQuery(\'img\').lazyload({effect: "fadeIn",threshold : 100});';
        $this->addScript($this->exposeUrl.'interface/js/lazyload.js');
        $this->addjQDom($js);
    }
    else {
        return;
    }
    
}
*/
?>
