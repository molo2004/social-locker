<?php
/**
 * A group of classes and methods to create and manage notices.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-notices 
 * @since 1.0.0
 */

add_action('admin_notices', 'factory_notices_305_admin_notices');
function factory_notices_305_admin_notices() {
    $manager = new FactoryNotices305();
    $manager->showNotices();
}

/**
 * A class to manage notices.
 * 
 * @since 1.0.0
 */
class FactoryNotices305 {
    
    public function showNotices() {
        
        $notices = apply_filters('factory_notices_305', array());
        if ( count($notices) == 0 ) return;

        if ( 
            !current_user_can('activate_plugins') || 
            !current_user_can('edit_plugins') || 
            !current_user_can('install_plugins')) return;

        wp_enqueue_style('factory-notices-305-css', FACTORY_NOTICES_305_URL . '/assets/css/notices.css');      
        wp_enqueue_script('factory-notices-305-js', FACTORY_NOTICES_305_URL . '/assets/js/notices.js');
        
        ?>
        <div class="updated factory-bootstrap-305 factory-fontawesome-305 factory-notices-305-notices">
        <?php
        foreach ($notices as $notice) {
            $this->showNotice($notice);
        }
        ?>
        </div>
        <?php
    }
    
    /**
     * Shows a notice.
     * 
     * The data has the followin format:
     *  "id" => an id of the notice
     *  "where" => a place where the notice should be visible (plugins, dashboard and so on)
     *  "header" => a header of the notice
     *  "message" => a message of the notice
     *  "class" => an extra class to add to the notice
     *  "close" => if true, then the close icon will be available to dissmish the notice
     * 
     * @since 1.0.0
     * @param type $data
     * @return void
     */
    public function showNotice( $data ) {
        
        $type = empty( $data['type'] ) ? 'offer' : $data['type'];
        $subtype = empty( $data['subtype'] ) ? 'none' : $data['subtype'];
        
        // checking if we should show a notice on a current page
        $where = empty( $data['where'] ) ? array('plugins','dashboard') : $data['where'];
        $screen = get_current_screen();
        if ( !in_array($screen->base, $where) ) return;

        // setups a content of the notice to display
        $header = empty( $data['header'] ) ? null : $data['header'];
        $message = empty( $data['message'] ) ? null : $data['message'];

        $hasHeader = !empty( $header );
        $hasMessage = !empty( $message );
        $hasClose = isset( $data['close'] ) ? $data['close'] : false;
        $hasIcon = isset( $data['icon'] );      
        
        $classes = array();
        if ( !empty( $data['class'] ) ) $classes[] = $data['class'];
        if ( !empty( $data['plugin'] ) ) $classes[] = 'notice-' . $data['plugin'];
        if ( $hasIcon ) $classes[] = 'factory-has-icon';  
        
        ?>
            <div class="factory-notice <?php echo implode(' ', $classes) ?>" id="<?php echo $data['id'] ?>">
            <div class="factory-inner-wrap"> 
                <?php if ( $hasClose ) { ?>
                <a href="#" class="factory-close close" title="Dismiss this message."><i class="fa fa-times"></i></a>
                <?php } ?>
                <?php if ( $hasIcon ) { ?>
                    <i class="factory-icon <?php echo $data['icon'] ?>"></i>
                <?php } ?>                
                <div class="factory-message-container">                   
                    <?php if ( $hasHeader ) { ?>
                    <h4 class="factory-header alert-heading"><?php echo $header ?></h4>
                    <?php } ?>
                    <span class="factory-message"><?php echo $message ?></span>
                </div>

                <div class="factory-buttons actions">
                    <?php foreach( $data['buttons'] as $buttonData ) { ?>
                    <?php $this->renderNoticeButton( $buttonData, $data['id'] ) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Show a notice button.
     * 
     * @since 1.0.0
     * @return void
     */
    public function renderNoticeButton( $data, $id ) {
        $title = $data['title'];
        $action = $data['action'];
        
        $classes = array();
        if ( !empty( $data['class'] ) ) $classes[] = $data['class'];
        
        $onclick = '';
        if ( $action == 'x' ) { 
            $onclick = "factory_notices_305_hide_notice('$id', false); return false;";
            $action = '#';
        }

        if ( $action == 'xx' ) { 
            $action = '#';
            $onclick = "factory_notices_305_hide_notice('$id', true); return false;"; 
        }

        ?>
        <a href="<?php echo $action ?>" onclick="<?php echo $onclick ?>" class="factory-button <?php echo implode(' ', $classes) ?>">
            <?php echo $title ?>
        </a>
        <?php 
    }
}