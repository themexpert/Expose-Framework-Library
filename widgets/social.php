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

class ExposeWidgetSocial extends ExposeWidget{

    public $name = 'social';

    public function render()
    {
        global $expose;

        $twitter    = (string) $this->get('twitter');
        $facebook   = (string) $this->get('facebook');
        $rss        = (string) $this->get('rss');

        ob_start();
        ?>
        <?php if( $twitter OR $facebook OR $rss ): ?>
        <ul class="social-icons clearfix">
            <?php if( !empty($twitter) ):?>
                <li class="twitter"><a href="<?php echo $twitter ;?>" target="_blank">Twitter</a></li>
            <?php endif;?>
            <?php if( !empty($facebook) ):?>
                <li class="facebook"><a href="<?php echo $facebook ;?>" target="_blank">Facebook</a></li>
            <?php endif; ?>
            <?php if( !empty($rss) ):?>
                <li class="rss"><a href="<?php echo $rss; ?>" target="_blank">RSS</a></li>
            <?php endif;?>
        </ul>
        <?php endif;?>

    <?php
        return ob_get_clean();
    }
}

?>
