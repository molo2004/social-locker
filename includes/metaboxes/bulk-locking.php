<?php
/**
 * The file contains a class to configure the metabox Bulk Locking.
 * 
 * Created via the Factory Metaboxes.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The class to show info on how the plugin support is provided.
 * 
 * @since 1.0.0
 */
class OnpSL_BulkLockingMetaBox extends FactoryMetaboxes320_Metabox
{
    /**
     * A visible title of the metabox.
     * 
     * Inherited from the class FactoryMetabox.
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * 
     * @since 1.0.0
     * @var string
     */
    public $title;
    
    /**
     * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low').
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * Inherited from the class FactoryMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $priority = 'core';
    
    /**
     * The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). 
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     * Inherited from the class FactoryMetabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $context = 'side';
    
    public function __construct( $plugin ) {
        parent::__construct( $plugin );
        
        $this->title = __('Batch Locking', 'sociallocker');
    }
    
    /**
     * Renders content of the metabox.
     * 
     * @see FactoryMetaboxes320_Metabox
     * @since 1.0.0
     * 
     * @return void
     */ 
    public function html()
    {
        global $post;
        $options = get_post_meta($post->ID, 'sociallocker_bulk_locking', true);
        
        // current bulk locker for the "skip & lock" and "more tag" modes
        
        $bulkLockerId = intval( get_option('onp_sl_bulk_locker', 0) );
        $bulkLocker = null;
        if ( $bulkLockerId !== 0 && $bulkLockerId != $post->ID ) {
            $bulkLocker = get_post($bulkLockerId);
            if ( $bulkLocker && empty( $bulkLocker->post_title ) ) $bulkLocker->post_title = __('No title', 'sociallocker');
        }

        // gets values for the form
        
        $setupStateClass = empty( $options ) ? 'onp-sl-empty-state' : 'onp-sl-has-options-state';
        
        $wayStateClass = '';
        if ( !empty($options) && isset( $options['way'] ) ) {
            if ( $options['way'] == 'skip-lock' ) $wayStateClass = 'onp-sl-skip-lock-state';
            elseif ( $options['way'] == 'more-tag' ) $wayStateClass = 'onp-sl-more-tag-state';
            elseif ( $options['way'] == 'css-selector' ) $wayStateClass = 'onp-sl-css-selector-state';
        }
        
        $skipAndLockStateClass = '';
        if ( !empty($options) && $options['way'] == 'skip-lock' ) {
            if ( $options['skip_number'] == 0 ) $skipAndLockStateClass = 'onp-sl-skip-lock-0-state';
            elseif ( $options['skip_number'] == 1 ) $skipAndLockStateClass = 'onp-sl-skip-lock-1-state';
            elseif ( $options['skip_number'] > 1 ) $skipAndLockStateClass = 'onp-sl-skip-lock-2-state';     
        } 
        
        $ruleStateClass = '';

        $defaultWay = 'skip-lock';
        if ( !empty($options) ) $defaultWay = $options['way'];
        
        $skipNumber = 1;
        if ( !empty($options) && $options['way'] == 'skip-lock' ) {
            $skipNumber = intval( $options['skip_number'] );
        }     
        
        $cssSelector = '';
        if ( !empty($options) && $options['way'] == 'css-selector' ) {
            $cssSelector = urldecode( $options['css_selector'] );
        }
        
        $excludePosts = '';
        if ( !empty($options) && !empty( $options['exclude_posts'] ) ) {
            $excludePosts = implode(', ', $options['exclude_posts']);
            $ruleStateClass .= ' onp-sl-exclude-post-ids-rule-state';
        } 
        
        $excludeCategories = '';
        if ( !empty($options) && !empty( $options['exclude_categories'] ) ) {
            $excludeCategories = implode(', ', $options['exclude_categories']);
            $ruleStateClass .= ' onp-sl-exclude-categories-ids-rule-state';
        } 
        
        $postTypes = '';
        if ( !empty($options) && !empty( $options['post_types'] ) ) {
            $postTypes = implode(', ', $options['post_types'] );
            $ruleStateClass .= ' onp-sl-post-types-rule-state';
        }  
        
        $checkedPostTypes = array('post', 'page');
        if ( !empty($options) && !empty( $options['post_types'] ) ) {
            $checkedPostTypes = $options['post_types'];
        }          

        $types = get_post_types( array('public' => true), 'objects' );

        // get interrelated option
        $interrelated = get_option('sociallocker_interrelation', false);
        $interrelatedClass = ( !$interrelated ) ? 'onp-sl-not-interrelation' : '';
        
        
        
        ?>
        <script>
            if ( !window.onpsl ) window.onpsl = {};
            if ( !window.onpsl.lang ) window.onpsl.lang = {}; 
 
            window.onpsl.lang.everyPostWillBeLockedEntirelyExceptFirstsParagraphs = 
                "<?php _e('Every post will be locked entirely except %s paragraphs placed at the beginning.', 'sociallocker') ?>";
        
            window.onpsl.lang.appliesToTypes = 
                "<?php _e('Applies to types: %s', 'sociallocker') ?>";

            window.onpsl.lang.excludesPosts = 
                "<?php _e('Excludes posts: %s', 'sociallocker') ?>";

            window.onpsl.lang.excludesCategories = 
                "<?php _e('Excludes categories: %s', 'sociallocker') ?>";
 
        </script>
  
        <div class="onp-sl-visibility-options-disabled" style="display: none;">
            <div class="alert alert-warning">
                <?php _e( 'You set the the batch locking based on CSS Selector. The visibility options don\'t support CSS selectors.', 'sociallocker') ?>
            </div>
        </div>
        
        <div id="onp-sl-bulk-lock-options">
            <?php if ( !empty($options) ) { ?>
               <?php foreach( $options as $optionKey => $optionValue ) { ?>
               <?php if (in_array($optionKey, array('exclude_posts','exclude_categories','post_types'))) {
                   $optionValue = implode(',', $optionValue);
               } ?>
               <input type="hidden" name="onp_sl_<?php echo $optionKey ?>" value="<?php echo $optionValue ?>" />
               <?php } ?>
            <?php } ?>
        </div>

        <div class="factory-bootstrap-323 factory-fontawesome-320">
            <div class="onp-sl-description-section">
                <?php _e('Batch Locking allows to apply the locker shortcode to your posts automatically.', 'sociallocker') ?>
            </div>
            <div class="onp-sl-setup-section <?php echo $setupStateClass ?>">
                
                <div class="onp-sl-empty-content">
                    <span class="onp-sl-nolock"><?php _e('No batch lock', 'sociallocker') ?></span>
                    <a class="btn btn-default" href="#onp-sl-bulk-lock-modal" role="button" data-toggle="factory-modal">
                        <i class="fa fa-cog"></i> <?php _e('Setup Batch Lock', 'sociallocker') ?>
                    </a>
                </div>
                
                <div class="onp-sl-has-options-content <?php echo $wayStateClass ?> <?php echo $ruleStateClass ?>">
                    
                    <div class="onp-sl-way-description onp-sl-skip-lock-content <?php echo $skipAndLockStateClass ?>">
                        <span class="onp-sl-skip-lock-0-content">
                            <?php echo _e('Every post will be locked entirely.', 'sociallocker') ?>
                        </span>
                        <span class="onp-sl-skip-lock-1-content">
                            <?php echo _e('Every post will be locked entirely except the first paragraph.', 'sociallocker') ?>
                        </span>
                        <span class="onp-sl-skip-lock-2-content">
                            <?php echo sprintf( __('Every post will be locked entirely except %s paragraphs placed at the beginning.', 'sociallocker'), $skipNumber ) ?>
                        </span>
                    </div>
                    
                    <div class="onp-sl-way-description onp-sl-more-tag-content">
                        <?php echo _e('Content placed after the More Tag will be locked in every post.', 'sociallocker') ?>
                    </div>
                    
                    <div class="onp-sl-way-description onp-sl-css-selector-content">
                        <p><?php echo _e('Every content matching the given CSS selector will be locked on every page:', 'sociallocker') ?></p>
                        <p><strong class="onp-sl-css-selector-view"><?php echo $cssSelector ?></strong></p>                       
                    </div>
                    
                    <div class='onp-sl-rules'>
                        <span class='onp-sl-post-types-rule'>
                            <?php printf( __('Applies to types: %s', 'sociallocker'), $postTypes ) ?>
                        </span>
                        <span class='onp-sl-exclude-post-ids-rule'>
                            <?php printf( __('Excludes posts: %s', 'sociallocker'), $excludePosts ) ?>
                        </span>
                        <span class='onp-sl-exclude-categories-ids-rule'>
                            <?php printf( __('Excludes categories: %s', 'sociallocker'), $excludeCategories ) ?>
                        </span>       
                    </div>
                    
                    <div class="onp-sl-controls">
                        <a class="btn btn-primary onp-sl-cancel" href="#onp-sl-bulk-lock-modal" role="button" data-toggle="modal" id="onp-sl-setup-bult-locking-btn">
                            <i class="fa fa-times-circle"></i> <?php _e('Cancel', 'sociallocker') ?>
                        </a>
                        <a class="btn btn-primary onp-sl-setup-bulk-locking" href="#onp-sl-bulk-lock-modal" role="button" data-toggle="factory-modal">
                            <i class="fa fa-cog"></i> <?php _e('Setup Batch Lock', 'sociallocker') ?>
                        </a>
                    </div>
                </div> 
            </div>
            
            <div class="<?php echo $setupStateClass ?> <?php echo $interrelatedClass ?> onp-sl-interrelation-hint">
                <?php _e('Recommended to turn on the Interrelation option on the <a target="_blank" href="./edit.php?post_type=social-locker&page=common-settings-sociallocker-next">Common Settings</a> page. It allows to unlock all lockers when one is unlocked.', 'sociallocker') ?>
            </div>
            
            <div class="onp-sl-after-change-hint">
                <i class="fa fa-exclamation-triangle"></i>
                <?php _e('Don\'t forget to apply made changes via the Update button above.', 'sociallocker') ?>
            </div>

            <div class="modal fade" id="onp-sl-bulk-lock-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php _e('Select Way Of Batch Locking', 'sociallocker') ?></h4>
                  </div>
                  <div class="modal-body">
                      
                    <div class="btn-group" id="onp-sl-bulk-locking-way-selector" data-toggle="buttons-radio">
                        <button data-target="#onp-sl-skip-lock-options" type="button" class="btn btn-default value skip-lock <?php if ( $defaultWay == 'skip-lock') echo 'active'; ?>" data-name="skip-lock"><i class="fa fa-sort-amount-desc"></i> <?php _e('Skip & Lock', 'sociallocker' ) ?></button>
                        <button data-target="#onp-sl-more-tags-options" type="button" class="btn btn-default value more-tag <?php if ( $defaultWay == 'more-tag') echo 'active'; ?>" data-name="more-tag"><i class="fa fa-scissors"></i> <?php _e('More Tag', 'sociallocker' ) ?></button>
                        <button data-target="#onp-sl-css-selector-options" type="button" class="btn btn-default value css-selector <?php if ( $defaultWay == 'css-selector') echo 'active'; ?>" data-name="css-selector"><i class="fa fa-hand-o-up"></i> <?php _e('CSS Selector', 'sociallocker' ) ?></button>     
                    </div>
                      
                    <div id="onp-sl-skip-lock-options" class="onp-sl-bulk-locking-options <?php if ( $defaultWay !== 'skip-lock') echo 'hide'; ?>">
                        <div class="onp-sl-description">
                            <?php _e('Enter the number of paragraphs which will be visible for users at the beginning of your posts and which will be free from locking. The remaining paragraphs will be locked.', 'sociallocker') ?>
                        </div>
                        <div class="onp-sl-content">
                            
                            <div class="onp-sl-form">

                                <div class='onp-sl-skip-number-row'>
                                    <label><?php _e('The number of paragraphs to skip', 'sociallocker') ?></label>
                                    <input type="text" class="form-control onp-sl-skip-number" maxlength="3" min="0" value="<?php echo $skipNumber; ?>" />
                                    <div class="help-block help-block-error">
                                        <?php _e('Please enter a positive integer value.') ?>
                                    </div>
                                </div>

                                <div class="onp-sl-limits">
                                    <div class='onp-sl-exclude'>
                                        <div class='onp-sl-row'>
                                            <label><?php _e('Exclude Posts', 'sociallocker') ?></label>
                                            <input type="text" class="form-control onp-sl-exclude-posts" value="<?php echo $excludePosts; ?>" />
                                            <div class="help-block">
                                                <?php _e('(Optional) Enter posts IDs comma separated, for example, "19,25,33".', 'sociallocker') ?>
                                            </div>
                                        </div>
                                        <div class='onp-sl-row'>
                                            <label><?php _e('Exclude Categories', 'sociallocker') ?></label>
                                            <input type="text" class="form-control onp-sl-exclude-categories" value="<?php echo $excludeCategories; ?>" />
                                            <div class="help-block">
                                                <?php _e('(Optional) Enter categories IDs comma separated, for example, "4,7".', 'sociallocker') ?>
                                            </div>
                                        </div>                        
                                    </div>

                                    <div class='onp-sl-post-types'>
                                        <strong><?php _e('Posts types to lock', 'sociallocker') ?></strong>
                                        <div class="help-block">
                                            <?php _e('Choose post types for batch locking.', 'sociallocker') ?>
                                        </div>
                                        <ul>
                                        <?php foreach($types as $type) {?>
                                            <li>
                                                <label for='onp-sl-post-type-<?php echo $type->name ?>-lock-skip'>
                                                    <input type='checkbox' class='onp-sl-post-type onp-sl-<?php echo $type->name ?>' id='onp-sl-post-type-<?php echo $type->name ?>-lock-skip' value='<?php echo $type->name ?>' <?php if ( in_array($type->name, $checkedPostTypes ) ) { echo 'checked="checked"'; } ?> />
                                                    <?php echo $type->label ?>   
                                                </label>
                                            </li>
                                        <?php } ?> 
                                        </ul>
                                        <div class="help-block help-block-error">
                                            <?php _e('Please choose at least one post type to lock. Otherwise, nothing to lock.', 'sociallocker') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="onp-sl-example">
                                <div class="onp-sl-description">
                                    <strong><?php _e('For example,', 'sociallocker') ?></strong>
                                    <?php _e('If you enter 2, two first paragraphs will be visible, others will be locked.', 'sociallocker') ?>
                                </div>
                                <div class="onp-sl-page">
                                    <div class="onp-sl-skipped">
                                        <div class="onp-sl-p"></div> 
                                        <div class="onp-sl-p"></div>
                                    </div>
                                    <div class="onp-sl-locked">
                                        <div class="onp-sl-hint">
                                            <i class="fa fa-lock"></i> 
                                            <?php _e('this will be locked', 'sociallocker') ?>
                                            <i class="fa fa-lock"></i>
                                        </div>
                                        <div class="onp-sl-p"></div> 
                                        <div class="onp-sl-p"></div>
                                        <div class="onp-sl-p"></div>
                                    </div>
                                </div>
                            </div>  

                        </div>                 
                    </div>
                      
                    <div id="onp-sl-more-tags-options" class="onp-sl-bulk-locking-options <?php if ( $defaultWay !== 'more-tag') echo 'hide'; ?>">
                        <div class="onp-sl-description">
                            <?php _e('All content after the More Tag will be locked in all your posts. If a post doesn\'t have the More Tag, the post will be shown without locking.', 'sociallocker') ?>
                        </div>
                        <div class="onp-sl-content">
                            
                            <div class="onp-sl-form onp-sl-limits">
                                <div class='onp-sl-exclude'>
                                    <div class='onp-sl-row'>
                                        <label><?php _e('Exclude Posts', 'sociallocker') ?></label>
                                        <input type="text" class="form-control onp-sl-exclude-posts" value="<?php echo $excludePosts; ?>" />
                                        <div class="help-block">
                                            <?php _e('(Optional) Enter posts IDs comma separated, for example, "19,25,33".', 'sociallocker') ?>
                                        </div>
                                    </div>
                                    <div class='onp-sl-row'>
                                        <label><?php _e('Exclude Categories', 'sociallocker') ?></label>
                                        <input type="text" class="form-control onp-sl-exclude-categories" value="<?php echo $excludeCategories; ?>" />
                                        <div class="help-block">
                                            <?php _e('(Optional) Enter categories IDs comma separated, for example, "4,7".', 'sociallocker') ?>
                                        </div>
                                    </div>                        
                                </div>

                                <div class='onp-sl-post-types'>
                                    <strong><?php _e('Posts types to lock', 'sociallocker') ?></strong>
                                    <div class="help-block">
                                        <?php _e('Choose post types for batch locking.', 'sociallocker') ?>
                                    </div>
                                    <ul>
                                    <?php foreach($types as $type) {?>
                                        <li>
                                            <label for='onp-sl-post-type-<?php echo $type->name ?>-more-tag'>
                                                <input type='checkbox' class='onp-sl-post-type onp-sl-<?php echo $type->name ?>' id='onp-sl-post-type-<?php echo $type->name ?>-more-tag' value='<?php echo $type->name ?>' <?php if ( in_array($type->name, $checkedPostTypes ) ) { echo 'checked="checked"'; } ?> />
                                                <?php echo $type->label ?>   
                                            </label>
                                        </li>
                                    <?php } ?> 
                                    </ul>
                                    <div class="help-block help-block-error">
                                        <?php _e('Please choose at least one post type to lock. Otherwise, nothing to lock.', 'sociallocker') ?>
                                    </div>
                                </div>
                            </div>

                            <strong class="onp-sl-title"><?php _e('What is the More Tag?', 'sociallocker') ?></strong>
                            <div class="onp-sl-image"></div>
                            <p>
                                <?php _e('Check out <a href="http://en.support.wordpress.com/splitting-content/more-tag/" target="_blank">Splitting Content » More Tag</a> to lean more.', 'sociallocker') ?>
                            </p>

                        </div>
                    </div>   
                      
                    <div id="onp-sl-css-selector-options" class="onp-sl-bulk-locking-options <?php if ( $defaultWay !== 'css-selector') echo 'hide'; ?>">
                        <div class="onp-sl-description">
                            <p>
                                <?php _e('CSS selectors allow accurately choose which content will be locked by usign CSS classes or IDs of elements placed on pages. If you don\'t know what is it, please don\'t use it.', 'sociallocker') ?>
                            </p>
                            <p>
                                <?php _e('Check out <a href="http://www.w3schools.com/cssref/css_selectors.asp" target="_blank">CSS Selector Reference</a> to lean more.', 'sociallocker') ?>
                            </p>
                        </div>
                        <div class="onp-sl-content">
                            <div class="onp-sl-form">
                                <label><?php _e('CSS Selector', 'sociallocker') ?></label>
                                <input type="text" class="form-control onp-sl-css-selector" value="<?php echo htmlentities($cssSelector); ?>" />
                                <div class="help-block">
                                    <?php _e('For example, "#somecontent .my-class, .my-another-class"', 'sociallocker') ?>
                                </div>                     
                                <div class="help-block help-block-error">
                                    <?php _e('Please enter a css selector to lock.', 'sociallocker') ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                      
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Cancel', 'sociallocker') ?></button>
                    <button type="button" class="btn btn-primary" id="onp-sl-save-bulk-locking-btn"><?php _e('Save', 'sociallocker') ?></button>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <?php
    }
    
    public function addBulkLockingWayCssClass( $classes ) {
        $classes[] = $this->way;
        return $classes;
    }
    
    public function save( $postId ) {
        
        // clear previos settings
        $bulkLockers = get_option('onp_sl_bulk_lockers', array());

        if ( !is_array($bulkLockers) ) $bulkLockers = array();
        if ( isset( $bulkLockers[$postId] ) ) unset( $bulkLockers[$postId] );
        
        if ( !isset( $_POST['onp_sl_way'] ) ) {
            delete_post_meta($postId, 'sociallocker_bulk_locking');
            delete_option('onp_sl_bulk_lockers');
            add_option('onp_sl_bulk_lockers', $bulkLockers);
            return;
        } else {
            $way = $_POST['onp_sl_way'];
            
            if ( !in_array( $way, array( 'skip-lock', 'more-tag', 'css-selector' )) ) {
                delete_post_meta($postId, 'sociallocker_bulk_locking');
                delete_option('onp_sl_bulk_lockers');
                add_option('onp_sl_bulk_lockers', $bulkLockers);
                return;
            }
                
            $data = array('way' => $way);
            if ( $way == 'skip-lock' ) {
                $data['skip_number'] = intval( $_POST['onp_sl_skip_number'] );
            } elseif( $way == 'css-selector' ) {
                $data['css_selector'] = $_POST['onp_sl_css_selector'];
            }
            
            if ( $way == 'skip-lock' || $way == 'more-tag' ) {
                $postTypes = isset( $_POST['onp_sl_post_types'] ) ? $_POST['onp_sl_post_types'] : '';          
                $excludePosts = isset( $_POST['onp_sl_exclude_posts'] ) ? $_POST['onp_sl_exclude_posts'] : '';
                $excludeCategories = isset( $_POST['onp_sl_exclude_categories'] ) ? $_POST['onp_sl_exclude_categories'] : '';
                
                $data['post_types'] = explode ( ',', $postTypes );
                $data['post_types'] = !empty( $data['post_types'] ) ? array_map( 'trim', $data['post_types'] ) : array();
                
                $data['exclude_posts'] = !empty( $excludePosts ) 
                        ? $this->_normalizeIntValArray( explode ( ',', $excludePosts ) )
                        : array();
                
                $data['exclude_categories'] = !empty( $excludeCategories ) 
                        ? $this->_normalizeIntValArray( explode ( ',', $excludeCategories ) )
                        : array();
            }
            
            $bulkLockers[$postId] = $data;
            
            delete_option('onp_sl_bulk_lockers');
            add_option('onp_sl_bulk_lockers', $bulkLockers);
            
            update_post_meta($postId, 'sociallocker_bulk_locking', $data);
        }
    }
    
    function _normalizeIntValArray( $arr ) {
        
        $arr = !empty( $arr ) ? array_map( 'intval', $arr ) : array();
    
        $return = array();
        foreach( $arr as $value ) {
            if ( $value == 0 ) continue;
            $return[] = $value;
        }
        
        return $return;
    }
}

FactoryMetaboxes320::register('OnpSL_BulkLockingMetaBox', $sociallocker);


/**
 * Prints bulk lock status.
 * 
 * @ToDo: Yes, this code repeats the code above. 
 *  
 * @param type $lockerId
 */
function onp_sl_print_bulk_locking_state( $lockerId ) {

    $options = get_post_meta($lockerId, 'sociallocker_bulk_locking', true);

    // gets values for the form

    $setupStateClass = empty( $options ) ? 'onp-sl-empty-state' : 'onp-sl-has-options-state';

    $wayStateClass = '';
    if ( !empty($options) && isset( $options['way'] ) ) {
        if ( $options['way'] == 'skip-lock' ) $wayStateClass = 'onp-sl-skip-lock-state';
        elseif ( $options['way'] == 'more-tag' ) $wayStateClass = 'onp-sl-more-tag-state';
        elseif ( $options['way'] == 'css-selector' ) $wayStateClass = 'onp-sl-css-selector-state';
    }

    $skipAndLockStateClass = '';
    if ( !empty($options) && $options['way'] == 'skip-lock' ) {
        if ( $options['skip_number'] == 0 ) $skipAndLockStateClass = 'onp-sl-skip-lock-0-state';
        elseif ( $options['skip_number'] == 1 ) $skipAndLockStateClass = 'onp-sl-skip-lock-1-state';
        elseif ( $options['skip_number'] > 1 ) $skipAndLockStateClass = 'onp-sl-skip-lock-2-state';     
    } 

    $ruleStateClass = '';

    $defaultWay = 'skip-lock';
    if ( !empty($options) ) $defaultWay = $options['way'];

    $skipNumber = 1;
    if ( !empty($options) && $options['way'] == 'skip-lock' ) {
        $skipNumber = intval( $options['skip_number'] );
    }     

    $cssSelector = '';
    if ( !empty($options) && $options['way'] == 'css-selector' ) {
        $cssSelector = urldecode( $options['css_selector'] );
    }

    $excludePosts = '';
    if ( !empty($options) && !empty( $options['exclude_posts'] ) ) {
        $excludePosts = implode(', ', $options['exclude_posts']);
        $ruleStateClass .= ' onp-sl-exclude-post-ids-rule-state';
    } 

    $excludeCategories = '';
    if ( !empty($options) && !empty( $options['exclude_categories'] ) ) {
        $excludeCategories = implode(', ', $options['exclude_categories']);
        $ruleStateClass .= ' onp-sl-exclude-categories-ids-rule-state';
    } 

    $postTypes = '';
    if ( !empty($options) && !empty( $options['post_types'] ) ) {
        $postTypes = implode(', ', $options['post_types'] );
        $ruleStateClass .= ' onp-sl-post-types-rule-state';
    }  

    ?>

    <div class="factory-bootstrap-323 factory-fontawesome-320">
        
        <div class="onp-sl-setup-section <?php echo $setupStateClass ?>">

            <div class="onp-sl-empty-content">
                <span class="onp-sl-nolock">—</span>
            </div>

            <div class="onp-sl-has-options-content <?php echo $wayStateClass ?> <?php echo $ruleStateClass ?>">

                <div class="onp-sl-way-description onp-sl-skip-lock-content <?php echo $skipAndLockStateClass ?>">
                    <span class="onp-sl-skip-lock-0-content">
                        <?php echo _e('Every post will be locked entirely.', 'sociallocker') ?>
                    </span>
                    <span class="onp-sl-skip-lock-1-content">
                        <?php echo _e('Every post will be locked entirely except the first paragraph.', 'sociallocker') ?>
                    </span>
                    <span class="onp-sl-skip-lock-2-content">
                        <?php echo sprintf( __('Every post will be locked entirely except %s paragraphs placed at the beginning.', 'sociallocker'), $skipNumber ) ?>
                    </span>
                </div>

                <div class="onp-sl-way-description onp-sl-more-tag-content">
                    <?php echo _e('Content placed after the More Tag will be locked in every post.', 'sociallocker') ?>
                </div>

                <div class="onp-sl-way-description onp-sl-css-selector-content">
                    <p><?php echo _e('Every content matching the CSS selector will be locked on every page:', 'sociallocker') ?></p>
                    <strong class="onp-sl-css-selector-view"><?php echo $cssSelector ?></strong>                      
                </div>

                <div class='onp-sl-rules'>
                    <span class='onp-sl-post-types-rule'>
                        <?php printf( __('Applies to types: %s', 'sociallocker'), $postTypes ) ?>
                    </span>
                    <span class='onp-sl-exclude-post-ids-rule'>
                        <?php printf( __('Excludes posts: %s', 'sociallocker'), $excludePosts ) ?>
                    </span>
                    <span class='onp-sl-exclude-categories-ids-rule'>
                        <?php printf( __('Excludes categories: %s', 'sociallocker'), $excludeCategories ) ?>
                    </span>       
                </div>
            </div> 
        </div>

    </div>
    <?php
}



/**
 * Removes the locker options from the global bulk locker options array.
 * 
 * @since 3.0.0
 * @param integer $lockerId
 * @return boolean
 */
function onp_sl_clear_bulk_locker_options( $lockerId ) {
    
    $bulkLockers = get_option('onp_sl_bulk_lockers', array());
    if ( !is_array($bulkLockers) ) $bulkLockers = array();
    if ( isset( $bulkLockers[$lockerId] ) ) unset( $bulkLockers[$lockerId] );
    
    delete_option('onp_sl_bulk_lockers');
    add_option('onp_sl_bulk_lockers', $bulkLockers);
}

/**
 * Removes the locker options from the global bulk locker options array.
 * 
 * @since 3.0.0
 * @param integer $lockerId
 * @return boolean
 */
function onp_sl_reset_bulk_locker_options( $lockerId ) {
    $data = get_post_meta($lockerId, 'sociallocker_bulk_locking', true);
    if ( empty($data) ) return;
    
    $bulkLockers = get_option('onp_sl_bulk_lockers', array());
    if ( !is_array($bulkLockers) ) $bulkLockers = array();
    $bulkLockers[$lockerId] = $data;
    
    delete_option('onp_sl_bulk_lockers');
    add_option('onp_sl_bulk_lockers', $bulkLockers);
}

/**
 * Deletes bulk locking options on a locker deletion.
 * 
 * @since 3.0.0
 * @return boolean
 */
function onp_sl_clear_bulk_locker_options_on_deletion( $postId ) {
    if ( !current_user_can( 'delete_posts' ) ) return true;

    $post = get_post( $postId );
    if ( empty( $post) ) return true;
    if ( $post->post_type !== 'social-locker' ) return true;
     
    onp_sl_clear_bulk_locker_options($postId);
    return true;
}
add_action('delete_post', 'onp_sl_clear_bulk_locker_options_on_deletion');

/**
 * Update global bulk locker options on changing a locker status.
 * 
 * Deletes bulk locking options on a locker deletion on moving to trash.
 * And reset options on retoring from the trash.
 * 
 * @since 3.0.0
 * @return void
 */
function onp_sl_update_bulk_locker_options_on_changing_status( $new_status, $old_status, $post ) {
    if ( empty( $post) ) return true;
    if ( $post->post_type !== 'social-locker' ) return true;
    
    if ( $new_status == 'trash' ) {
        onp_sl_clear_bulk_locker_options($post->ID);
    } elseif ( $new_status !== 'trash' ) {
        onp_sl_reset_bulk_locker_options($post->ID);
    }
}
add_action('transition_post_status', 'onp_sl_update_bulk_locker_options_on_changing_status', 10, 3 );