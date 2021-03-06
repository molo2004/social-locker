<?php
/**
 * The file contains a page that shows statistics
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * Common Settings
 */
class OnpSL_StatisticsPage extends FactoryPages320_AdminPage  {
 
    public $menuTitle = 'Usage Statistics';
    public $menuPostType = 'social-locker';
    
    public $id = "statistics";
    
    public function __construct(Factory324_Plugin $plugin) {    
        $this->menuTitle = __('Stats & Reports', 'sociallocker');
        parent::__construct($plugin);
    }
        
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        
        $this->styles->request( array( 
            'bootstrap.core'
            ), 'bootstrap' ); 
        
        $this->scripts->add(ONP_SL_PLUGIN_URL . '/assets/admin/js/datepicker.js');  
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/datepicker.css');      
        $this->scripts->add(ONP_SL_PLUGIN_URL . '/assets/admin/js/statistics.030000.js');
        $this->styles->add(ONP_SL_PLUGIN_URL . '/assets/admin/css/statistics.030000.css');   
    }
    
    /**
     * Shows an index page where a user can set settings.
     * 
     * @sinve 1.0.0
     * @return void
     */
    public function indexAction() {

    include_once(ONP_SL_PLUGIN_DIR . '/includes/classes/stats.class.php');
    
    $postId = isset($_REQUEST['sPost']) ? intval($_REQUEST['sPost']) : false;
    $post = ($postId) ? get_post($postId) : false;
    
    $dateStart = isset($_REQUEST['sDateStart']) ? $_REQUEST['sDateStart'] : false;  
    $dateEnd = isset($_REQUEST['sDateEnd']) ? $_REQUEST['sDateEnd'] : false; 
    
    $hrsOffset = get_option('gmt_offset');
    if (strpos($hrsOffset, '-') !== 0) $hrsOffset = '+' . $hrsOffset;
    $hrsOffset .= ' hours';

    // by default shows a 30 days' range
    if (empty($dateEnd) || ($dateRangeEnd = strtotime($dateEnd)) === false) {
        $phpdate = getdate( strtotime($hrsOffset, time()) );
        $dateRangeEnd = mktime(0, 0, 0, $phpdate['mon'], $phpdate['mday'], $phpdate['year']);
    }
    
    if (empty($dateStart) || ($dateRangeStart = strtotime($dateStart)) === false) {
        $dateRangeStart = strtotime("-1 month", $dateRangeEnd);
    }
    
    // creates a statistic viewer
    $stats = new StatsManager();

    // gets data for the chart
    $chartData = $stats->getChartData($dateRangeStart, $dateRangeEnd, $postId);
       
    $page = ( isset( $_GET['n'] ) ) ? intval( $_GET['n'] ) : 1;
    if ( $page <= 0 ) $page = 1;

    // gets table to view
    $viewTable = $stats->getViewTable(array(
        'postId' => $postId,
        'rangeStart' => $dateRangeStart,
        'rangeEnd' => $dateRangeEnd,  
        'per' => 50,
        'total' => true,
        'page' => $page,
        'order' => 'total_count'
    ));
	
    $tableRows = $viewTable['data'];
    $totalRows = $viewTable['count'];
    $pagesCount = ceil( $totalRows / 50 );
    
    $dateStart = date('m/d/Y', $dateRangeStart);
    $dateEnd = date('m/d/Y', $dateRangeEnd); 
    
    $urlBase = 'edit.php?post_type=social-locker&page=statistics-' . $this->plugin->pluginName;
    $postBase = $urlBase . '&sDateStart=' . $dateStart . '&dateEnd=' . $dateEnd;
    ?>
        <script>
            if ( !window.onpsl ) window.onpsl = {};
            if ( !window.onpsl.res ) window.onpsl.res = {};
            window.onpsl.res.total_social_impact = '<?php _e('Total social impact', 'sociallocker') ?>';
            window.onpsl.res.unlocked_by_buttons = '<?php _e('Unlocked by Buttons', 'sociallocker') ?>';
            window.onpsl.res.unlocked_by_timer = '<?php _e('Unlocked by Timer', 'sociallocker') ?>';   
            window.onpsl.res.unlocked_by_close_icon = '<?php _e('Unlocked by Close Icon', 'sociallocker') ?>';
            window.onpsl.res.na = '<?php _e('The count of times when the buttons were not available.', 'sociallocker') ?>';
        </script>
        
        <!--Load the AJAX API-->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">

          // Load the Visualization API and the piechart package.
          google.load('visualization', '1.0', {'packages':['corechart']});

          // Set a callback to run when the Google Visualization API is loaded.
          google.setOnLoadCallback(function(){
              window.onpsl.statistics.drawChart();
          });

          window.chartData = [

            <?php 
            foreach($chartData as $dataRow):
                
                $chartDataCount = array(
                  'date' => 'new Date('.$dataRow['year'].','.$dataRow['mon'].','.$dataRow['day'].')',
                  'facebook-like' => $dataRow['facebook_like_count'],
                  'twitter-tweet' => $dataRow['twitter_tweet_count'],
                  'facebook-share' => $dataRow['facebook_share_count'],
                  'twitter-follow' => $dataRow['twitter_follow_count'],
                  'google-plus' => $dataRow['google_plus_count'],
                  'google-share' => $dataRow['google_share_count'],
                  'linkedin-share' => $dataRow['linkedin_share_count']  
                );
                
                $chartDataCount['timer'] = $dataRow['timer_count'];
                $chartDataCount['cross'] = $dataRow['cross_count'];
                $chartDataCount['na'] = $dataRow['na_count'];
                
                $chartDataCount = apply_filters('onp_sl_statistics_chartData', $chartDataCount, $dataRow);
                
                $printChartData = '';                
                foreach( $chartDataCount as $key => $val):
                    $printChartData .= "'$key': ".$val.',';
                endforeach;
                
                $printChartData = rtrim($printChartData, ',');
            ?>
            {<?php echo $printChartData; ?>},
            <?php endforeach; ?>
         ];
        </script>
        <div class="wrap">
            <h2 style="margin-bottom: 10px;"><?php _e('Stats & Reports', 'sociallocker'); ?></h2>

            <div class="factory-bootstrap-325 factory-fontawesome-320">

            <p style="line-height: 150%; padding-bottom: 5px; margin-bottom: 0px;">
                <?php _e('This page provides usage statistics of social lockers on your pages. Here you can get info about how users interact with your lockers.<br /> By default the chart shows the aggregate data for all posts. Click on the post title to view info for the one.', 'sociallocker'); ?></p>

            <div class="onp-chart-hints">
                <div class="onp-chart-hint onp-chart-hint-errors">
                    <?php printf( __('This chart shows the count of times when the locker was not available to use due to the visitor installed the extensions like Avast or Adblock which may block social networks.<br />By default, the such visitors see the locker without social buttons but with the offer to disable the extensions. You can set another behaviour <a href="%s"><strong>here</strong></a>.', 'sociallocker'), admin_url('admin.php?page=common-settings-' . $this->plugin->pluginName . '&action=advanced') ) ?>
                </div>
            </div>
            
            <div id="onp-sl-chart-area">
                <form method="get"> 
                <input type="hidden" name="post_type" value="social-locker" />
                <input type="hidden" name="page" value="statistics-<?php echo $this->plugin->pluginName ?>" /> 
                <div id="onp-sl-settings-bar">
                    
                    <div id="onp-sl-type-select">
                       <div class="btn-group" id="chart-type-group" data-toggle="buttons-radio">
                          <button type="button" class="btn btn-default active type-total" data-value="total"><i class="fa fa-search"></i> <?php _e('Total', 'sociallocker'); ?></button>
                          <button type="button" class="btn btn-default type-detailed" data-value="detailed"><i class="fa fa-search-plus"></i> <?php _e('Detailed', 'sociallocker'); ?></button>
                          <button type="button" class="btn btn-default type-helpers" data-value="helpers"><i class="fa fa-tint"></i> <?php _e('Leakages', 'sociallocker'); ?></button>     
                          <button type="button" class="btn btn-default type-helpers" data-value="errors"><i class="fa fa-bug"></i> <?php _e('Errors', 'sociallocker'); ?></button>  
                       </div>
                    </div>
                    <div id="onp-sl-date-select">
                            <input type="hidden" name="sPost" value="<?php echo $postId ?>" />
                            <span class="onp-sl-range-label"><?php _e('Date range', 'sociallocker') ?>:</span>
                            <input type="text" id="onp-sl-date-start" name="sDateStart" class="form-control" value="<?php echo $dateStart ?>" />
                            <input type="text" id="onp-sl-date-end" name="sDateEnd" class="form-control" value="<?php echo $dateEnd ?>" />
                            <a id="onp-sl-apply-dates" class="btn btn-default">
                                <?php _e('Apply', 'sociallocker') ?>
                            </a>
                    </div>
                </div>
                </form>

                <div class="chart-wrap">
                    <div id="chart" style="width: 100%; height: 195px;"></div>
                </div>
                
            </div>
            <div id="onp-sl-chart-selector">
                <div class="onp-sl-chart-item facebook-like">
                    <span class="chart-color"></span>
                    <?php _e('FB Likes', 'sociallocker') ?>
                </div>
                <?php ?>
                <div class="onp-sl-chart-item twitter-tweet">
                    <span class="chart-color"></span>
                    <?php _e('Tweets', 'sociallocker') ?>
                </div>  
                <?php ?>
                <div class="onp-sl-chart-item google-plus">
                    <span class="chart-color"></span>
                    <?php _e('Google Plusoners', 'sociallocker') ?>
                </div> 
                <?php ?>
            </div>

            <?php if ($postId) { ?>
                <div class="alert alert-warning">
                <?php echo sprintf(__('Data for the post: <strong>%s</strong> (<a href="%s">return back</a>)', 'sociallocker'),$post->post_title, $postBase); ?>
                </div>
            <?php } else { ?>
                <p><?php _e('Top posts and pages where you placed the lockers showing the best social indicators. Click a post title to get more details.', 'sociallocker') ?></p>
            <?php } ?>

            <div id="onp-sl-posts-wrap">
            <table id="onp-sl-posts">
                <thead>
                    <th class="col-index"></th>
                    <th class="col-title"><?php _e('Post Title', 'sociallocker') ?></th>
                    <th class="col-number col-total"><?php _e('Total', 'sociallocker') ?></th>
                    <th class="col-number col-facebook-like"><?php _e('FB Likes', 'sociallocker') ?></th>
                    <?php ?>
                    <th class="col-number col-twitter-tweet"><?php _e('Tweets', 'sociallocker') ?></th>  
                    <?php ?>
                    <th class="col-number col-google-plus"><?php _e('Google Plusoners', 'sociallocker') ?></th> 
                    <?php ?>
                    <th class="col-number col-timer"><?php _e('Timer', 'sociallocker') ?></th>   
                    <th class="col-number col-cross"><?php _e('Close Icon', 'sociallocker') ?></th>          
                </thead>
                <tbody>
                <?php foreach($tableRows as $index => $dataRow) { ?>
                <tr>
                    <td class="col-index"><?php echo $index + 1 ?>.</td>
                    <td class="col-title"><a href="<?php echo $postBase ?>&sPost=<?php echo $dataRow['ID'] ?>"><?php echo $dataRow['title'] ?></a></td>  
                    <td class="col-number col-total"><?php echo $dataRow['total_count'] ?></td>
                    <td class="col-number col-facebook-like"><?php echo $dataRow['facebook_like_count'] ?></td>
                    <?php ?>
                    <td class="col-number col-twitter-tweet"><?php echo $dataRow['twitter_tweet_count'] ?></td>
                    <?php ?>
                    <td class="col-number col-google-plus"><?php echo $dataRow['google_plus_count'] ?></td>
                    <?php ?>
                    <td class="col-number col-timer"><?php echo $dataRow['timer_count'] ?></td>
                    <td class="col-number col-cross"><?php echo $dataRow['cross_count'] ?></td>   
                </tr>
                <?php } ?>
                </tbody>
            </table>

            </div>
            
            <div id="onp-sl-pagination-wrap">
                <div class="pagination">
                <ul class="pagination pagination-sm">
                <?php for( $i = 1; $i <= $pagesCount; $i++ ) { ?>
                    <li <?php if ( $i == $page ) { ?>class="active"<?php } ?>><a href="?sDateStart=<?php echo $dateStart ?>&sDateEnd=<?php echo $dateEnd ?>&post_type=social-locker&page=statistics-<?php echo $this->plugin->pluginName ?>&n=<?php echo $i ?>"><?php echo $i ?></a></li>
                <?php } ?>
                </ul>
                </div>
            </div>
                
            </div>
        </div>
    <?php
    }
}

FactoryPages320::register($sociallocker, 'OnpSL_StatisticsPage');