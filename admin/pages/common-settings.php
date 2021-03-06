<?php
/**
 * The file contains a page that shows the common settings for the plugin.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * The page Common Settings.
 * 
 * @since 1.0.0
 */
class OnpSL_CommonSettingsPage extends FactoryPages320_AdminPage  {
 
    /**
     * The title of the page in the admin menu.
     * 
     * @see FactoryPages320_AdminPage
     * 
     * @since 1.0.0
     * @var string 
     */
    public $menuTitle = 'Global Settings';
    
    /**
     * The parent menu of the page in the admin menu.
     * 
     * @see FactoryPages320_AdminPage
     * 
     * @since 1.0.0
     * @var string 
     */
    public $menuPostType = 'social-locker';
    
    /**
     * The id of the page in the admin menu.
     * 
     * Mainly used to navigate between pages.
     * @see FactoryPages320_AdminPage
     * 
     * @since 1.0.0
     * @var string 
     */
    public $id = "common-settings";
    
    /**
     * Available languages.
     * 
     * @since 1.0.0
     * @var mixed[] 
     */
    protected $languages;
    
    public function __construct(Factory324_Plugin $plugin) {   
        parent::__construct($plugin);
        $this->menuTitle = __('Global Settings', 'sociallocker');
    }
    
    /**
     * Requests assets (js and css) for the page.
     * 
     * @see FactoryPages320_AdminPage
     * 
     * @since 1.0.0
     * @return void 
     */
    public function assets($scripts, $styles) {
        
        $this->scripts->request('jquery');
        
        $this->scripts->request( array( 
            'control.checkbox',
            'control.dropdown',
            ), 'bootstrap' );
        
        $this->styles->request( array( 
            'bootstrap.core', 
            'bootstrap.form-group',
            'bootstrap.separator',
            'control.dropdown',
            'control.checkbox'
            ), 'bootstrap' ); 
        
        $this->scripts->add(ONP_SL_PLUGIN_URL . '/assets/admin/js/settings.030702.js');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/settings.030702.css');   
        
        
    }
    
    /**
     * Renders the page 
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {
        
        global $sociallocker;
        
        $this->languages = array(
            array('ca_ES', __('Catalan', 'sociallocker')),
            array('cs_CZ', __('Czech', 'sociallocker')),
            array('cy_GB', __('Welsh', 'sociallocker')),
            array('da_DK', __('Danish', 'sociallocker')),
            array('de_DE', __('German', 'sociallocker')),
            array('eu_ES', __('Basque', 'sociallocker')),
            array('en_US', __('English', 'sociallocker')),
            array('es_ES', __('Spanish', 'sociallocker')),
            array('fi_FI', __('Finnish', 'sociallocker')), 
            array('fr_FR', __('French', 'sociallocker')), 
            array('gl_ES', __('Galician', 'sociallocker')), 
            array('hu_HU', __('Hungarian', 'sociallocker')),
            array('it_IT', __('Italian', 'sociallocker')),
            array('ja_JP', __('Japanese', 'sociallocker')),
            array('ko_KR', __('Korean', 'sociallocker')),
            array('nb_NO', __('Norwegian', 'sociallocker')),
            array('nl_NL', __('Dutch', 'sociallocker')),
            array('pl_PL', __('Polish', 'sociallocker')),
            array('pt_BR', __('Portuguese (Brazil)', 'sociallocker')),
            array('pt_PT', __('Portuguese (Portugal)', 'sociallocker')),
            array('ro_RO', __('Romanian', 'sociallocker')),
            array('ru_RU', __('Russian', 'sociallocker')),
            array('sk_SK', __('Slovak', 'sociallocker')),  
            array('sl_SI', __('Slovenian', 'sociallocker')), 
            array('sv_SE', __('Swedish', 'sociallocker')),
            array('th_TH', __('Thai', 'sociallocker')),
            array('tr_TR', __('Turkish', 'sociallocker')), 
            array('ku_TR', __('Kurdish', 'sociallocker')), 
            array('zh_CN', __('Simplified Chinese (China)', 'sociallocker')), 
            array('zh_HK', __('Traditional Chinese (Hong Kong)', 'sociallocker')),
            array('zh_TW', __('Traditional Chinese (Taiwan)', 'sociallocker')), 
            array('af_ZA', __('Afrikaans', 'sociallocker')),
            array('sq_AL', __('Albanian', 'sociallocker')),
            array('hy_AM', __('Armenian', 'sociallocker')),
            array('az_AZ', __('Azeri', 'sociallocker')),
            array('be_BY', __('Belarusian', 'sociallocker')),
            array('bn_IN', __('Bengali', 'sociallocker')),
            array('bs_BA', __('Bosnian', 'sociallocker')),
            array('bg_BG', __('Bulgarian', 'sociallocker')),
            array('hr_HR', __('Croatian', 'sociallocker')),
            array('nl_BE', __('Dutch (België)', 'sociallocker')),
            array('eo_EO', __('Esperanto', 'sociallocker')),
            array('et_EE', __('Estonian', 'sociallocker')),
            array('fo_FO', __('Faroese', 'sociallocker')),
            array('ka_GE', __('Georgian', 'sociallocker')),
            array('el_GR', __('Greek', 'sociallocker')),
            array('gu_IN', __('Gujarati', 'sociallocker')),
            array('hi_IN', __('Hindi', 'sociallocker')),
            array('is_IS', __('Icelandic', 'sociallocker')),
            array('id_ID', __('Indonesian', 'sociallocker')),
            array('ga_IE', __('Irish', 'sociallocker')),
            array('jv_ID', __('Javanese', 'sociallocker')),
            array('kn_IN', __('Kannada', 'sociallocker')),
            array('kk_KZ', __('Kazakh', 'sociallocker')),
            array('la_VA', __('Latin', 'sociallocker')),
            array('lv_LV', __('Latvian', 'sociallocker')),
            array('li_NL', __('Limburgish', 'sociallocker')),
            array('lt_LT', __('Lithuanian', 'sociallocker')), 
            array('mk_MK', __('Macedonian', 'sociallocker')), 
            array('mg_MG', __('Malagasy', 'sociallocker')),
            array('ms_MY', __('Malay', 'sociallocker')),
            array('mt_MT', __('Maltese', 'sociallocker')),
            array('mr_IN', __('Marathi', 'sociallocker')),
            array('mn_MN', __('Mongolian', 'sociallocker')),
            array('ne_NP', __('Nepali', 'sociallocker')),
            array('pa_IN', __('Punjabi', 'sociallocker')),
            array('rm_CH', __('Romansh', 'sociallocker')),
            array('sa_IN', __('Sanskrit', 'sociallocker')),
            array('sr_RS', __('Serbian', 'sociallocker')),
            array('so_SO', __('Somali', 'sociallocker')),
            array('sw_KE', __('Swahili', 'sociallocker')),
            array('tl_PH', __('Filipino', 'sociallocker')),
            array('ta_IN', __('Tamil', 'sociallocker')),
            array('tt_RU', __('Tatar', 'sociallocker')), 
            array('te_IN', __('Telugu', 'sociallocker')),
            array('ml_IN', __('Malayalam', 'sociallocker')),
            array('uk_UA', __('Ukrainian', 'sociallocker')),
            array('uz_UZ', __('Uzbek', 'sociallocker')),
            array('vi_VN', __('Vietnamese', 'sociallocker')),
            array('xh_ZA', __('Xhosa', 'sociallocker')),
            array('zu_ZA', __('Zulu', 'sociallocker')),
            array('km_KH', __('Khmer', 'sociallocker')),
            array('tg_TJ', __('Tajik', 'sociallocker')),
            array('ar_AR', __('Arabic', 'sociallocker')), 
            array('he_IL', __('Hebrew', 'sociallocker')),
            array('ur_PK', __('Urdu', 'sociallocker')),
            array('fa_IR', __('Persian', 'sociallocker')),
            array('sy_SY', __('Syriac', 'sociallocker')),  
            array('yi_DE', __('Yiddish', 'sociallocker')),
            array('gn_PY', __('Guaraní', 'sociallocker')),
            array('qu_PE', __('Quechua', 'sociallocker')),
            array('ay_BO', __('Aymara', 'sociallocker')),
            array('se_NO', __('Northern Sámi', 'sociallocker')),
            array('ps_AF', __('Pashto', 'sociallocker'))
        );
        
        $form = new FactoryForms324_Form(array(
            'scope' => 'sociallocker',
            'name'  => 'common-setting'
        ), $sociallocker );
        
        $form->setProvider( new FactoryForms324_OptionsValueProvider(array(
            'scope' => 'sociallocker'
        )));
        
        
        
        $formOptions = array();
        
        $formOptions[] = array(
            'type' => 'separator'
        );
        
        $formOptions[] = array(
            'type'      => 'textbox',
            'name'      => 'facebook_appid',
            'title'     => __( 'Facebook App ID', 'sociallocker' ),
            'hint'      => __( 'The Facebook App Id. By default, the developer app id is used. If you want to use the Facebook Share button you should register another app id specifically for your domain. Please check out <a style="font-weight: bold;" target="_blank" href="http://support.onepress-media.com/how-to-register-a-facebook-app/">this article</a> for more information.', 'sociallocker' )
        );
   
        $formOptions[] = array(
            'type'      => 'dropdown',
            'name'      => 'lang',
            'title'     => __( 'Language of buttons', 'sociallocker' ),
            'data'      => $this->languages,
            'hint'      => __( 'Choose the language that will be used for social buttons.', 'sociallocker' ),
        );
        
        $formOptions[] = array(
            'type'      => 'dropdown',
            'way'       => 'buttons',
            'name'      => 'facebook_version',
            'title'     => __( 'Facebook API Version', 'sociallocker' ),
            'default'   => 'v1.0',
            'data'      => array(
                array('v1.0', 'v1.0'),
                array('v2.0', 'v2.0')       
             ),
            'hint'      => __( 'Optional. Use the most recent version of the API (v2.0) but if Facebook buttons don\'t work on your website try to switch the API to the v1.0. Please note currently the v2.0 does not allow to change the language of the Facebook buttons. On all probability it\'s a bug on the Facebook side.', 'sociallocker' ),
        );

        $formOptions[] = array(
            'type' => 'separator'
        );
        
        $formOptions[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'interrelation',
            'title'     => __( 'Interrelation', 'sociallocker' ),
            'hint'      => __( 'Set On to make lockers interrelated. When one of the interrelated lockers are unlocked on your site, the others will be unlocked too.<br /> Recommended to turn on, if you use the Batch Locking feature.', 'sociallocker' ),
            'default'   => false
        );

        $formOptions[] = array(
            'type' => 'separator'
        );

        $formOptions[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'google_analytics',
            'title'     => __( 'Google Analytics', 'sociallocker' ),
            'hint'      => __( 'If set On, the plugin will generate <a href="https://support.google.com/analytics/answer/1033068?hl=en" target="_blank">events</a> for the Google Analytics when the content is unlocked.<br /><strong>Note:</strong> before enabling this feature, please <a href="https://support.google.com/analytics/answer/1008015?hl=en" target="_blank">make sure</a> that your website contains the Google Analytics tracker code.', 'sociallocker' )
        );
        

        $formOptions[] = array(
            'type'      => 'html',
            'html'      => array($this, 'statsHtml')
        );
        
        $formOptions[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',  
            'name'      => 'tracking',
            'title'     => __( 'Collecting Stats', 'sociallocker' ),
            'data'      => $this->languages,
            'hint'      => __( 'Turns on collecting the statistical data for reports.', 'sociallocker' )
        );
        
        $formOptions[] = array(
            'type' => 'separator'
        );

        $formOptions[] = array(
            'type'      => 'html',
            'html'      => array($this, 'advancedOptionsHtml')
        );

        $formOptions[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'debug',
            'title'     => __( 'Debug', 'sociallocker' ),
            'data'      => $this->languages,
            'hint'      => __( 'When this option turned on, the locker appears always, ignoring any settings, even if the user already unlocked the content.', 'sociallocker' )
        );
        
        $formOptions[] = array(
            'type' => 'separator'
        );

        $formOptions = apply_filters('onp_sl_settings_options', $formOptions );
        $form->add($formOptions);
        
        if ( isset( $_POST['save-action'] ) ) {
            $form->save();
            
            $selectedLang = $_POST['sociallocker_lang'];
            update_option('sociallocker_lang', $selectedLang);
            
            if ( !empty( $selectedLang ) ) {

                $langItem = null;
                foreach( $this->languages as $lang ) {
                    if ( $lang[0] == $selectedLang ) {
                        $langItem = $lang;
                        break;
                    }
                }

                $parts = explode('_', $selectedLang);
                update_option('sociallocker_short_lang', $parts[0]);

                if ( $langItem && isset( $langItem[2] ) ) {
                    update_option('sociallocker_google_lang', $langItem[2] );   
                } else {
                    delete_option('sociallocker_google_lang');   
                }
            }
            
            $redirectArgs = apply_filters('onp_sl_settings_options_redirect_args', array('saved' => 1 ) );
            return $this->redirectToAction('index', $redirectArgs);
        }

        ?>
        <div class="wrap ">
            <h2><?php _e('Global Settings', 'sociallocker') ?></h2>
            <p style="margin-top: 0px;"><?php _e('The settings below are applied to all your social lockers.', 'sociallocker') ?></p>
            
            <div class="factory-bootstrap-325">
            <form method="post" class="form-horizontal">

                <?php if ( isset( $_GET['saved'] ) ) { ?>
                <div id="message" class="alert alert-success">
                    <p>The settings have been updated successfully!</p>
                </div>
                <?php } ?>
                
                <?php do_action('onp_sl_settings_options_notices') ?>

                <div style="padding-top: 10px;">
                <?php $form->html(); ?>
                </div>
                
                <div class="form-group form-horizontal">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="control-group controls col-sm-10">
                        <input name="save-action" class="btn btn-primary" type="submit" value="<?php _e('Save changes', 'sociallocker') ?>"/>
                    </div>
                </div>
            
            </form>
            </div>  
                
        </div>
        <?php
    }
    
    /**
     * A page to edit the Advanced Options.
     * 
     * @since v3.7.2
     * @return vod
     */
    public function advancedAction() {
        global $sociallocker;
        
        $form = new FactoryForms324_Form(array(
            'scope' => 'sociallocker',
            'name'  => 'advanced-setting'
        ), $sociallocker );
        
        $form->setProvider( new FactoryForms324_OptionsValueProvider(array(
            'scope' => 'sociallocker'
        )));
        
        $formOptions = array();
        
        $formOptions[] = array(
            'type' => 'separator'
        );

        $formOptions[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'dynamic_theme',
            'title'     => __( 'I use a dynamic theme', 'sociallocker' ),
            'hint'      => __( 'If your theme loads pages dynamically via ajax, set "On" to get the lockers working (if everything works properly, don\'t turn on this option).', 'sociallocker' )
        );
        
        $formOptions[] = array(
            'type'      => 'div',
            'id'        => 'onp-dynamic-theme-options',
            'items'     => array(

                array(
                    'type'      => 'textbox',
                    'name'      => 'dynamic_theme_event',
                    'title'     => __( 'jQuery Events', 'sociallocker' ),
                    'hint'      => __( 'If pages of your site are loaded dynamically via ajax, it\'s necessary to catch ' . 
                                   'the moment when the page is loaded in order to appear the locker.<br />By default the plugin covers ' .
                                   '99% possible events. So <strong>you don\'t need to set any value here</strong>.<br />' .
                                   'But if you know how it works and sure that it will help, you can put here the javascript event ' .
                                   'that triggers after loading of pages on your site.', 'sociallocker' )
                )   
            )
        );
        
        $formOptions[] = array(
            'type' => 'separator'
        );
        
        $formOptions[] = array(
            'type'      => 'dropdown',
            'name'      => 'alt_overlap_mode',
            'data'      => array(
                array( 'full', 'Classic (full)' ),
                array( 'transparence', 'Transparency' )    
            ),
            'default'   => 'transparence',
            'title'     => __( 'Alt Overlap Mode', 'sociallocker' ),
            'hint'      => __( 'This overlap mode will be applied for browsers which don\'t support the blurring effect.', 'sociallocker' )
        );

        $formOptions[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'rss',
            'title'     => __( 'Locked content<br /> is visible in RSS feeds', 'sociallocker' ),
            'hint'      => __( 'Set On to make locked content visible in RSS feed.', 'sociallocker' ),
            'default'   => false
        );
        
        $formOptions[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'actual_urls',
            'title'     => __( 'Actual URLs by default', 'sociallocker' ),
            'hint'      => __( 'Optional. If you do not set explicitly URLs to like/share in the settings of social buttons, then by default the plugin will use an URL of the page where the locker is located. Turn on this option to extract URLs to like/share from an address bar of the user browser, saving all query arguments. By default (when this option disabled) permalinks are used.', 'sociallocker' ),
            'default'   => false
        );

        $formOptions[] = array(
            'type' => 'separator'
        );
        
        $formOptions[] = array(
            'type'      => 'checkbox',
            'way'       => 'buttons',
            'name'      => 'tumbler',
            'title'     => __( 'Anti-Cheating', 'sociallocker' ),
            'default'   => false,
            'hint'      => __( 'Turn it on to protect your locked content against cheating from visitors. Some special browser extensions allow to view the locked content without actual sharing. This option checks whether the user has really liked/shared your page. In future versions of the plugin, we will make this option active by default.', 'sociallocker' )
        );
        
        $formOptions[] = array(
            'type'      => 'dropdown',
            'name'      => 'na_mode',
            'data'      => array(
                array( 'show-error', 'Show the error' ),
                array( 'show-content', 'Remove the locker, show the content' )    
            ),
            'title'     => __( 'If N/A, what to do?', 'sociallocker' ),
            'default'   => false,
            'hint'      => __( 'Optional. Select what the locker should to do if the social buttons have not been loaded. At this case, the locker is not available to use. It occurs if the visitor uses the extensions like Avast or Adblock which may block social networks. By default the locker shows the error and the offer to disable the extensions.<br /><i>How to test? Set the option "Timeout of waiting loading the locker (in ms)" to 1</i>.', 'sociallocker' )
        );

        $formOptions[] = array(
            'type'      => 'textbox',
            'name'      => 'timeout',
            'title'     => __( 'Timeout of waiting<br />loading the locker (in ms)', 'sociallocker' ),
            'default'   => '10000',
            'hint'      => __( 'The use can have browser extensions which block loading scripts from social networks. If the social buttons have not been loaded within the specified timeout, the locker shows the error (in the red container) alerting about that a browser blocks loading of the social buttons.<br />', 'sociallocker' )
        );
 
        $formOptions[] = array(
            'type' => 'separator'
        );

        
        $formOptions = apply_filters('onp_sl_advanced_settings', $formOptions );
        $form->add($formOptions);
        
        if ( isset( $_POST['save-action'] ) ) {
            $form->save();
            
            $redirectArgs = apply_filters('onp_sl_advanced_settings_redirect_args', array('saved' => 1 ) );
            return $this->redirectToAction('advanced', $redirectArgs);
        }
        
        ?>
        <div class="wrap ">
            <h2><?php _e('Advanced Settings', 'sociallocker') ?></h2>
            <p style="margin-top: 0px;">
                <?php _e('A set of extra options for advanced users and for troubleshooting.', 'sociallocker' )?><br />
                <?php printf( __('It\'s not recommended to change them if you are not sure that you do. If the plugin does\'t work, <a href="%s">contact us</a> at first.', 'sociallocker'), admin_url('/admin.php?page=how-to-use-' . $sociallocker->pluginName . '&onp_sl_page=troubleshooting&action=index') ) ?>
            </p>
            
            <div class="factory-bootstrap-325">
            <form method="post" class="form-horizontal">

                <?php if ( isset( $_GET['saved'] ) ) { ?>
                <div id="message" class="alert alert-success">
                    <p>The settings have been updated successfully!</p>
                </div>
                <?php } ?>
                
                <?php do_action('onp_sl_advanced_settings_notices') ?>

                <div style="padding-top: 10px;">
                <?php $form->html(); ?>
                </div>

                <div class="form-group form-horizontal">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="control-group controls col-sm-10">
                        <input name="save-action" class="btn btn-primary" type="submit" value="<?php _e('Save changes', 'sociallocker') ?>"/>
                        <a class="btn btn-default" href="<?php $this->actionUrl('index') ?>"><?php _e('Return back', 'sociallocker') ?></a>
                    </div>
                </div>
            
            </form>
            </div>  
                
        </div>
        <?php
    }
    
    /**
     * Render the html block on how much the statistics data takes places.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function statsHtml() {
        global $wpdb;
        
        $dataSizeInBytes = $wpdb->get_var(
            "SELECT round(data_length + index_length) as 'size_in_bytes' FROM information_schema.TABLES WHERE " . 
            "table_schema = '" . DB_NAME . "' AND table_name = '{$wpdb->prefix}so_tracking'");
        
        $count = $wpdb->get_var("SELECT COUNT(*) AS n FROM {$wpdb->prefix}so_tracking");
        $humanDataSize = factory_324_get_human_filesize( $dataSizeInBytes );
        
        ?>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php _e('Statistical Data', 'sociallocker') ?></label>
                <div class="control-group controls col-sm-10">
                    <p class="onp-sl-inline">
                        <?php if ( $count == 0 ) { ?>
                        <?php printf( __( 'The statistical data is <strong>empty</strong>.', 'sociallocker' ), $humanDataSize ); ?>
                        <?php } else { ?>
                        <?php printf( __( 'The statistical data takes <strong>%s</strong> on your server', 'sociallocker' ), $humanDataSize ); ?>
                        <a class="button" style="margin-left: 5px;" href="<?php $this->actionUrl('clearStatsData') ?>"><?php _e('clear data', 'sociallocker') ?></a>
                        <?php } ?>
                    </p>
                </div>
            </div>
        <?php
    }
    
    /**
     * Shows the button to show the advanced options.
     * 
     * @sinve 3.7.1
     * @return void
     */
    public function advancedOptionsHtml() {
        ?>
        <div class="form-group form-horizontal">
            <label class="col-sm-2 control-label"><?php _e('Advanced Options', 'sociallocker') ?></label>
            <div class="control-group controls col-sm-10">
                <a class="btn btn-default" href="<?php $this->actionUrl('advanced') ?>"><?php _e('Show advanced options', 'sociallocker') ?></a>
                <div class='help-block'><?php _e('A set of extra options for advanced users and for troubleshooting. It\'s not recommended to change them if you are not sure that you do.', 'sociallocker') ?></div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Clears the statisticals data.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function clearStatsDataAction() {
        
        if ( !isset( $_REQUEST['onp_confirmed'] ) ) {
            return $this->confirm(array(
                'title' => __('Are you sure that you want to clear the current statistical data?', 'sociallocker'),
                'description' => __('All the statistical data will be removed.', 'sociallocker'),
                'actions' => array(
                    'onp_confirm' => array(
                        'class' => 'btn btn-danger',
                        'title' => __("Yes, I'm sure", 'sociallocker'),
                        'url' => $this->getActionUrl('clearStatsData', array(
                            'onp_confirmed' => true
                        ))
                    ),
                    'onp_cancel' => array(
                        'class' => 'btn btn-default',
                        'title' => __("No, return back", 'sociallocker'),
                        'url' => $this->getActionUrl('index')
                    ),
                )
            ));
        }
        
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}so_tracking");
        return $this->redirectToAction('index', array('onp_table_cleared' => true));
    }
    
    /**
     * Shows the html block with a confirmation dialog.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function confirm( $data ) {
        ?>
        <div class="onp-page-wrap factory-bootstrap-325" id="onp-confirm-dialog">
            <div id="onp-confirm-dialog-wrap">
                <h1><?php echo $data['title'] ?></h1>
                <p><?php echo $data['description'] ?></p>
                <div class='onp-actions'>
                    <?php foreach( $data['actions'] as $action ) { ?>
                        <a href='<?php echo $action['url'] ?>' class='<?php echo $action['class'] ?>'>
                           <?php echo $action['title'] ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
}

FactoryPages320::register($sociallocker, 'OnpSL_CommonSettingsPage');

