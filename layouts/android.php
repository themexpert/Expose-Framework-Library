<?php
/**
 * Expose layout view for android device
 *
 * @package     Expose
 * @version     2.0
 * @author      ThemeXpert http://www.themexpert.com
 * @copyright   Copyright (C) 2010 - 2011 ThemeXpert
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @file        android.php
 **/
//prevent direct access
defined ('EXPOSE_VERSION') or die ('resticted aceess');

$display_component = !($expose->isHomePage() AND $expose->get('component_display')==FALSE );
?>
<?php $expose->loadGists('mobile-logo');?>

        <?php /**Begin Mobile-Header**/ if($expose->countModules('mobile-header',0)): ?>
        <!--Start Mobile Header Modules-->
        <div id="tx-mobile-header">
            <div class="tx-container clearfix">
                <?php $expose->renderModules('mobile-header','standard',array(/*pass the class name seperate by , */)); ?>
                <div class="clear"></div>
            </div>
        </div>
        <!--End Mobile Header Modules-->
        <?php /**End Mobile-Header**/ endif;?>

        <div class="tx-main-inner">
            <div id="tx-mainbody" class="android">
                <div class="tx-content">
                    <jdoc:include type="message" />
                    <?php if($display_component) :?>
                        <jdoc:include type="component" />
                    <?php endif;?>
                </div>
            </div>
        </div>

        <?php /**Begin Mobile Footer**/ if($expose->countModules('mobile-footer',0)): ?>
        <!--Start Mobile Footer Modules-->
        <div id="tx-mobile-footer">
            <div class="tx-container">
                <?php $expose->renderModules('mobile-footer','standard',array(/*pass the class name seperate by , */)); ?>
                <div class="clear"></div>
            </div>
        </div>
        <!--End Mobile Footer Modules-->
        <?php /**End Mobile Footer**/ endif;?>



