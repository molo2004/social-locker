<?php
/**
 * The file contains a base class for all metaboxe.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2013, OnePress Ltd
 * 
 * @package factory-metaboxes 
 * @since 1.0.0
 */

/**
 * The base class for all metaboxes.
 * 
 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
 * @since 1.0.0
 */
abstract class FactoryMetaboxes320_Metabox {
    
    /**
     * Id of the metabox. 
     * Be default, the current class name is used.
     * 
     * @since 1.0.0
     * @var string 
     */
    public $id = null;
    
    /**
     * A visible title of the metabox.
     * 
     * @since 1.0.0
     * @var string
     */
    public $title = '';
    
    /**
     * The part of the page where the edit screen 
     * section should be shown ('normal', 'advanced', or 'side').
     * 
     * @since 1.0.0
     * @var string 
     */
    public $context = 'normal';
    
    /**
     * The priority within the context where the boxes 
     * should show ('high', 'core', 'default' or 'low')
     * 
     * @since 1.0.0
     * @var string
     */
    public $priority = 'default';
    
    /**
     * Post types for which a metabox should be shown.
     * 
     * @since 1.0.0
     * @var array
     */
    public $postTypes = array();
    
    /**
     * Scripts that should be include on the page where the metabox will be shown.
     * 
     * @since 1.0.0
     * @var Factory324_ScriptList
     */
    public $scripts;
    
    /**
     * Styles that should be include on the page where the metabox will be shown.
     * 
     * @since 1.0.0
     * @var Factory324_StyleList
     */  
    public $styles;
    
    /**
     * Stores a state of assets (connected of not).
     * 
     * @since 1.0.0
     * @var bool 
     */
    protected $isConnected = false;
    
    /**
     * Creates a new instance of a metabox.
     * 
     * @since 1.0.0
     */
    public function __construct( $plugin ) {
        $this->plugin = $plugin;
        $this->id = empty($this->id) ? get_class($this) : $this->id;
    }
    
    /**
     * Adds a new post type where the metabox should appear.
     * 
     * @since 1.0.0
     * @param string $typeName
     */
    public function addPostType( $typeName ) {
       if ( !in_array($typeName, $this->postTypes) ) {
           $this->postTypes[] = $typeName;
       }
    }
    
    /**
     * Configures a metabox.
     * 
     * @since 1.0.0
     * @param Factory324_ScriptList $scripts A set of scripts to include.
     * @param Factory324_StyleList $styles A set of style to include.
     * @return void
     */
    public function configure( $scripts, $styles) {
        // method must be overriden in the derived classed.
    }

    /**
     * Registers this metabox to show.
     * 
     * @since 1.0.0
     * @return void
     */
    public function connect() {
        if ( $this->isConnected ) return;
         $this->isConnected = true;
           
        $this->scripts = $this->plugin->newScriptList();
        $this->styles = $this->plugin->newStyleList();
        
        $this->configure( $this->scripts, $this->styles );
        $this->includeScriptsAndStyles();
    }
    
    /**
     * Includes scripts and styles for a metabox.
     * 
     * @since 1.0.0
     * @return void
     */
    public function includeScriptsAndStyles() {
        global $post;
            
        if ( $this->scripts->isEmpty() && $this->styles->isEmpty() ) return;
        if ( !in_array( $post->post_type, $this->postTypes)) return;

        foreach ($this->scripts->getAllRequired() as $script) {
            wp_enqueue_script( $script );
        }        
        
        foreach ($this->scripts->getAll() as $script) {
            wp_enqueue_script( $script, $script, array('jquery'), false, true);
        }

        foreach ($this->styles->getAllRequired() as $style) {
            wp_enqueue_style( $style );
        }       
        
        foreach ($this->styles->getAll() as $style) {
            wp_enqueue_style( $style, $style);
        }          
    }
    
    /**
     * Saves metabox data.
     * 
     * @since 1.0.0
     * @param int $post_id
     * @return integer A post id.
     */
    public function actionSavePost( $post_id ) {
        
        $post_type = $_POST['post_type'];
        if ( !in_array( $post_type, $this->postTypes ) ) return $post_id;
        
        // verify the nonce before proceeding
        $className = strtolower( get_class($this) );  
        $nonceName = $className . '_factory_nonce';
        $nonceValue = $className  . '_factory';
        
        if ( !isset( $_POST[$nonceName] ) || !wp_verify_nonce( $_POST[$nonceName], $nonceValue ) )
            return $post_id;
        
        if ( wp_is_post_revision( $post_id ) ) return $post_id;
        
        // get the post type object.
	$post_type = get_post_type_object( $post_type );

	// check if the current user has permission to edit the post.
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) return $post_id;
        
        // all right, save data.
        $this->save( $post_id );
    }
    
    /**
     * Shows content of a metabox.
     * 
     * @since 1.0.0
     * @return void
     */
    public function show() {

        // security nonce
        $className = strtolower( get_class($this) );
        wp_nonce_field( $className  . '_factory', $className . '_factory_nonce' );
        
        ob_start();
        $this->html();
        $content = ob_get_clean();
        
        echo $content;
    }
    
    /**
     * [virtual] Prints html content of a metabox.
     * 
     * The method must be overridden in the derived classes.
     * 
     * @since 1.0.0
     * @return void
     */
    public function html() {             
        echo 'Define the method "html" in your metabox class.';
    }
    
    /**
     * [virtual] Saves metabox data.
     * 
     * The method must be overridden in the derived classes.
     * 
     * @since 1.0.0
     * @param $postId A post id.
     * @return void
     */
    public function save( $postId ) {}
}