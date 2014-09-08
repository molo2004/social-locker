<?php
/**
 * Ajax requests linked with collecting statistics.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

add_action('wp_ajax_sociallocker_tracking', 'onp_sl_tracking');
add_action('wp_ajax_nopriv_sociallocker_tracking', 'onp_sl_tracking');

/**
 * Increases counters in a database after unlocking content.
 * 
 * @since 1.0.0
 * @return void
 */
function onp_sl_tracking() {
    global $wpdb;
    
    
    
    $postId = isset( $_POST['targetId'] ) ? intval($_POST['targetId']) : 0;
    if (!$postId) exit;
    
    $sender = isset( $_POST['sender'] ) ? $_POST['sender'] : null;
    if (empty($sender) || !in_array($sender, array('button', 'timer', 'cross'))) exit;
    
    $senderName = !empty($_POST['senderName']) ? $_POST['senderName'] : false;
    
    $insertPart = false;
    $updatePart = false;
    
    switch($sender) {
        
        case 'timer':
                $insertPart = '0,0,0,0,1,0,0,0,0,0'; 
            


            $updatePart = 'timer_count = timer_count + 1';
            break;
        
        case 'cross':
                $insertPart = '0,0,0,0,0,1,0,0,0,0'; 
            

            
            $updatePart = 'cross_count = cross_count + 1';
            break;
        
        case 'button':
                
                $buttonsPart = array(
                    'facebook-like' => array(
                        'insertPart' => '1,1,0,0,0,0,0,0,0,0',
                        'updatePart' => 'facebook_like_count = facebook_like_count + 1, total_count = total_count + 1'
                    ),
                    'twitter-tweet' => array(
                        'insertPart' => '1,0,1,0,0,0,0,0,0,0',
                        'updatePart' => 'twitter_tweet_count = twitter_tweet_count + 1, total_count = total_count + 1'
                    ),
                    'google-plus' => array(
                        'insertPart' => '1,0,0,1,0,0,0,0,0,0',
                        'updatePart' => 'google_plus_count = google_plus_count + 1, total_count = total_count + 1'
                    ),
                    'facebook-share' => array(
                        'insertPart' => '1,0,0,0,0,0,1,0,0,0',
                        'updatePart' => 'facebook_share_count = facebook_share_count + 1, total_count = total_count + 1'
                    ),
                    'twitter-follow' => array(
                        'insertPart' => '1,0,0,0,0,0,0,1,0,0',
                        'updatePart' => 'twitter_follow_count = twitter_follow_count + 1, total_count = total_count + 1'
                    ),
                    'google-share' => array(
                        'insertPart' => '1,0,0,0,0,0,0,0,1,0',
                        'updatePart' => 'google_share_count = google_share_count + 1, total_count = total_count + 1'
                    ),
                    'linkedin-share' => array(
                        'insertPart' => '1,0,0,0,0,0,0,0,0,1',
                        'updatePart' => 'linkedin_share_count = linkedin_share_count + 1, total_count = total_count + 1'
                    )                  
                );                
            

            
            $insertPart = empty($buttonsPart[$senderName]['insertPart']) ? null : $buttonsPart[$senderName]['insertPart'];
            $updatePart = empty($buttonsPart[$senderName]['updatePart']) ? null : $buttonsPart[$senderName]['updatePart'];

            break;
    }
    
    $part = apply_filters('onp_sl_tracking_part', array(
        'insertPart' => $insertPart,
        'updatePart' => $updatePart
    ), $sender, $senderName);
    
    $insertPart = $part['insertPart'];
    $updatePart = $part['updatePart'];
        
    if (!$insertPart || !$updatePart) exit;
    
    $hrsOffset = get_option('gmt_offset');
    if (strpos($hrsOffset, '-') !== 0) $hrsOffset = '+' . $hrsOffset;
    $hrsOffset .= ' hours';
    
    $time = strtotime($hrsOffset, time());
    
    $date = date("Y-m-d", $time);        
        $useField = 'AggregateDate, PostID, total_count, facebook_like_count, twitter_tweet_count, google_plus_count, timer_count, cross_count, facebook_share_count, twitter_follow_count, google_share_count, linkedin_share_count';
    

    
    $insertField = apply_filters('onp_sl_tracking_insert_fields', $useField);
   
    $sql = "INSERT INTO {$wpdb->prefix}so_tracking 
                (".$insertField.") 
                VALUES ('$date',$postId, $insertPart)
                ON DUPLICATE KEY UPDATE $updatePart";

    $wpdb->query($sql); 
    exit;
}

