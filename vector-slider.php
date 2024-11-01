<?php
/**
Plugin name: Vector Slider
Plugin URI: https://www.codegearthemes.com/products/vector-slider/
Description: Responsive wordpress slider.
Version: 1.0.6
Author: CodeGearThemes
Author URI: http://www.codegearthemes.com
Text Domain: vector-slider
Domain Path: /languages/
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/*----------------------------------------------------------/
        DECLERATION OF NECESSARY CONSTANTS FOR PLUGINS
/----------------------------------------------------------*/
if( !defined( 'VSCGT_VERSION' ) ) define( 'VSCGT_VERSION', '1.0.6' );
if( !defined( 'VSCGT_DEFPATH' ) ) define( 'VSCGT_DEFPATH', plugin_dir_path( __FILE__ ) );
if( !defined( 'VSCGT_ASSDIR' ) ) define( 'VSCGT_ASSDIR', plugin_dir_url( __FILE__ ) . 'assets' ); 
if( !defined( 'VSCGT_CSSDIR' ) ) define( 'VSCGT_CSSDIR', plugin_dir_url( __FILE__ ) . 'assets/css' ); 
if( !defined( 'VSCGT_JSDIR' ) ) define( 'VSCGT_JSDIR', plugin_dir_url( __FILE__ ) . 'assets/js' );
if( !defined( 'VSCGT_IMGDIR' ) ) define( 'VSCGT_IMGDIR', plugin_dir_url( __FILE__ ) . 'assets/img' );
if( !defined( 'VSCGT_LANGDIR' ) ) define( 'VSCGT_LANGDIR', basename( dirname( __FILE__ ) ) . '/languages/' );
if( !defined( 'VSCGT_TEXTDOM' ) ) define( 'VSCGT_TEXTDOM', 'vector-slider' );


if( !class_exists( 'vectorSliderClass' ) ):
    
    class vectorSliderClass {
        var $vsdefault_settings;
        
        #PLUGIN INITILIZATION
        function __construct() {
            $this->vsdefault_settings = get_option( 'vsdefault_settings' );
            register_activation_hook( __FILE__, array($this, 'vector_slider_load_default_settings') );
            add_action( 'init', array($this, 'vector_slider_plugin_text_domain') );
            add_action( 'init', array( $this , 'vector_slider_register'));
            add_action( 'admin_menu', array($this, 'vector_slider_admin_menu') );
            add_action( 'admin_enqueue_scripts', array($this, 'vector_slider_register_admin_assets') );
            add_action( 'wp_enqueue_scripts', array($this, 'vector_slider_register_assets') );
            add_action( 'init', array($this, 'vector_slider_metaboxes') );
            add_action( 'init', array($this, 'vector_slider_images_size') );
            add_shortcode( 'vector_slider', array($this, 'vector_slider_cb') );
            add_action( 'admin_post_vector_slider_settings_action', array($this, 'vector_slider_save_settings') );
            add_filter( 'manage_edit-slider_columns', array( $this, 'vector_slider_columns') );
            add_action( 'manage_posts_custom_column', array( $this, 'vector_slider_load_columns' ));
	        add_filter( 'pre_get_posts', array( $this, 'vector_slider_column_order' ));	
            
            register_deactivation_hook( __FILE__, 'vector_slider_deactivation' );
            register_uninstall_hook( __FILE__, 'vector_slider_uninstall' );
        }
        
        
        #LOADING PLUGIN TRANSLATION
        function vector_slider_plugin_text_domain() {
            load_plugin_textdomain( 'vector-slider', false, VSCGT_LANGDIR .'/languages/' );
        } 
        
        #LOADING DEFAULT SETTINGS
        function vector_slider_load_default_settings() {
            flush_rewrite_rules();
            if( !get_option( 'vsdefault_settings' ) ) {
                $vsdefault_settings = $this->get_default_settings();
                update_option( 'cgtdefault_settings', $vsdefault_settings );
            }
        }
        
        #VECTOR SLIDER REGISTER POST TYPE
        function vector_slider_register() {
        	$labels = array(
        		'name'                 => __( 'Slider', 'vector-slider' ),
        		'singular_name'        => __( 'Slider', 'vector-slider' ),
        		'all_items'            => __( 'All Slides', 'vector-slider' ),
        		'add_new'              => __( 'Add New Slide', 'vector-slider' ),
        		'add_new_item'         => __( 'Add New Slide', 'vector-slider' ),
        		'edit_item'            => __( 'Edit Slide', 'vector-slider' ),
        		'new_item'             => __( 'New Slide', 'vector-slider' ),
        		'view_item'            => __( 'View Slide', 'vector-slider' ),
        		'search_items'         => __( 'Search Slides', 'vector-slider' ),
        		'not_found'            => __( 'No Slide found', 'vector-slider' ),
        		'not_found_in_trash'   => __( 'No Slide found in Trash', 'vector-slider' ), 
        		'parent_item_colon'    => ''
        		
        	);
        	
        	$args = array(
        		'labels'               => $labels,
        		'public'               => true,
        		'publicly_queryable'   => true,
        		'_builtin'             => false,
        		'show_ui'              => true, 
        		'query_var'            => true,
        		'rewrite'              => array( "slug" => "slider" ),
        		'capability_type'      => 'post',
        		'hierarchical'         => false,
        		'menu_position'        => 20,
        		'supports'             => array( 'title' , 'thumbnail' , 'page-attributes' ),
        		'taxonomies'           => array(),
        		'has_archive'          => true,
        		'show_in_nav_menus'    => false,
       		    'menu_icon'   => 'dashicons-images-alt2',
        	);
        	
        	register_post_type( 'slider', $args );
        }
        
        #FLUSH PERMALINKS ON DEACTIVATION
        function vector_slider_deactivation() {
            flush_rewrite_rules();
        }
        
        #DELETE SLIDER SETTINGS
        function vector_slider_uninstall() {
        	delete_option( 'vsdefault_settings' );	
        }
        
        function vector_slider_admin_menu() {
        	add_submenu_page( 'edit.php?post_type=slider', __( 'Slider Settings', 'vector-slider' ), __( 'Slider Settings', 'vector-slider' ), 'manage_options', 'vector-slider-settings', array( $this , 'vector_slider_settings_page' ) );
        }
        
        #ENQUEUE ADMIN ASSETS
        function vector_slider_register_admin_assets(){
            wp_enqueue_style( 'vector-slider-admin', VSCGT_CSSDIR . '/vs-slider-admin.css', false, VSCGT_VERSION, 'all' );
            wp_enqueue_script( 'vector-slider-admin', VSCGT_JSDIR . '/vs-slider-admin.js', array( 'jquery' ), VSCGT_VERSION , true );
        }
        
        #ENQUEUE FRONTEND ASSETS
        function vector_slider_register_assets(){
            $vsdefault_settings = get_option( 'vsdefault_settings' );
            wp_enqueue_style( 'vector-slick', VSCGT_ASSDIR . '/lib/slider/slick.css', false, VSCGT_VERSION, 'all' );
            wp_enqueue_style( 'vector-fonts', VSCGT_ASSDIR . '/lib/fontawesome/css/font-awesome.css', false, VSCGT_VERSION, 'all' );
            wp_enqueue_style( 'vector-slick-theme', VSCGT_ASSDIR . '/lib/slider/slick-theme.css', false, VSCGT_VERSION, 'all' );
            wp_enqueue_style( 'vector-slider', VSCGT_CSSDIR . '/vs-slider.css', false, VSCGT_VERSION, 'all' );
            
            wp_enqueue_script( 'vector-slick', VSCGT_ASSDIR . '/lib/slider/slick.js', array( 'jquery' ), VSCGT_VERSION , true );
            wp_enqueue_script( 'vector-slider-vs', VSCGT_JSDIR . '/vs-slider.js', array( 'jquery' ), VSCGT_VERSION , true );
            
            #VARIABLES FOR JS
        	wp_localize_script( 'vector-slider-vs', 'vslider', array(
                'height'    => $vsdefault_settings['vs_slide_height'],
        		'effect'    => $vsdefault_settings['vs_slide_effect'],
        		'delay'     => $vsdefault_settings['vs_slide_delay'],
        		'duration'  => $vsdefault_settings['vs_slide_duration'],		
        	) );
        }
        
        
        #DEFAULT VARIABLE ACCESS
        function get_default_settings() {
    		$vsdefault_settings = array(
                'vs_slide_height'    => '481',	
    			'vs_slide_effect'    => 'fade',
    			'vs_slide_delay'     => '1000',
    			'vs_slide_duration'  => '2000'	
    		);
            return $vsdefault_settings;
        }
        
        function vector_slider_metaboxes(){
             add_action( 'add_meta_boxes', 'vector_slider_meta_options' );
             if( ! function_exists( 'vector_slider_meta_options' ) ):
             function  vector_slider_meta_options() {
                add_meta_box(
                             'vector_slide_link',
                             'Slider Caption',
                             'vector_slider_link_callback',
                             'slider',
                             'normal',
                             'default'
                             );
             }
             endif;
             
             function vector_slider_link_callback(){
                global $post ;
                wp_nonce_field( basename( __FILE__ ), 'vector_slider_link_nonce' ); 
                ?>
            
                <table class="vector-slider-metabox">
                    <tr>
                         <td colspan="2">
                            <?php $vs_content = get_post_meta( $post->ID, 'vs_content', true ); ?>
                            <label><?php _e('Slider Content' , 'vector-slider' ) ?></label>
                            <textarea rows="10" name="vs_content"><?php echo $vs_content; ?></textarea>
                         </td>
                    </tr>
                    <tr>
                        <td>
                            <?php $vs_btn_text = get_post_meta( $post->ID, 'vs_btn_text', true ); ?>
                            <?php $vs_btn_link = get_post_meta( $post->ID, 'vs_btn_link', true ); ?>
                            <label><?php _e('Button Label' , 'vector-slider' ) ?></label>
                            <input type="text" name="vs_btn_text" value="<?php echo $vs_btn_text; ?>" placeholder="View More"/>
                        </td>
                        <td>
                            <label><?php _e('Button Link' , 'vector-slider' ) ?></label>
                            <input type="text" name="vs_btn_link" value="<?php echo $vs_btn_link; ?>" placeholder="https://"/>
                        </td>
                    </tr>
                </table>
            
            <?php 
            }
            
            function vector_slider_metadata_save( $post_id ){
                // Bail if we're doing an auto save
                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
                // if our nonce isn't there, or we can't verify it, bail
                if( !isset( $_POST['vector_slider_link_nonce'] ) || !wp_verify_nonce( $_POST['vector_slider_link_nonce'], basename( __FILE__ ) ) ) return;
     
                // if our current user can't edit this post, bail
                if( !current_user_can( 'edit_post' ) ) return;
                
                $cgt_old = sanitize_textarea_field(get_post_meta( $post_id, 'vs_content', true)); 
                $cgt_new = sanitize_textarea_field( $_POST['vs_content'] );
                if ( $cgt_new && '' == $cgt_new ){
                    add_post_meta( $post_id, 'vs_content', $cgt_new );
                }elseif ($cgt_new && $cgt_new != $cgt_old) {  
                    update_post_meta($post_id, 'vs_content', $cgt_new);  
                } elseif ('' == $cgt_new && $cgt_old) {  
                    delete_post_meta($post_id,'vs_content', $cgt_old);  
                }
                
                $cgt_old = sanitize_text_field( get_post_meta( $post_id, 'vs_btn_text', true) ); 
                $cgt_new = sanitize_text_field( $_POST['vs_btn_text'] );
                if ( $cgt_new && '' == $cgt_new ){
                    add_post_meta( $post_id, 'vs_btn_text', $cgt_new );
                }elseif ($cgt_new && $cgt_new != $cgt_old) {  
                    update_post_meta($post_id, 'vs_btn_text', $cgt_new);  
                } elseif ('' == $cgt_new && $cgt_old) {  
                    delete_post_meta($post_id,'vs_btn_text', $cgt_old);  
                } 
                $cgt_old =  esc_url( get_post_meta( $post_id, 'vs_btn_link', true) ); 
                $cgt_new =  esc_url( $_POST['vs_btn_link'] );
                if ( $cgt_new && '' == $cgt_new ){
                    add_post_meta( $post_id, 'vs_btn_link', $cgt_new );
                }elseif ($cgt_new && $cgt_new != $cgt_old) {  
                    update_post_meta($post_id, 'vs_btn_link', $cgt_new);  
                } elseif ('' == $cgt_new && $cgt_old) {  
                    delete_post_meta($post_id,'vs_btn_link', $cgt_old);  
                }
            }
            add_action('save_post', 'vector_slider_metadata_save' , 5);
        }
        
        function vector_slider_images_size() {
        	add_image_size( 'slider-thumbnail', 1500 , 580 , true );	
        }
        
        #SAVING DATA TO DATABASE
        function vector_slider_save_settings() {
            if( !empty( $_POST ) && wp_verify_nonce( $_POST['vector_slider_settings_nonce'], 'vector_slider_settings_action' ) ) {
                if( check_admin_referer('vector_slider_settings_action', 'vector_slider_settings_nonce') ){
                    foreach( $_POST['vs'] as $key => $val ){
                    	$$key = sanitize_text_field($val);
                    }
                    $vsdefault_settings = array();
                    $vsdefault_settings['vs_slide_height'] = $vs_slide_height;
                    $vsdefault_settings['vs_slide_effect'] = $vs_slide_effect;
                    $vsdefault_settings['vs_slide_delay'] = $vs_slide_delay;
                    $vsdefault_settings['vs_slide_duration'] = $vs_slide_duration;
                    update_option( 'vsdefault_settings', $vsdefault_settings);
                    wp_redirect( admin_url().'edit.php?post_type=slider&page=vector-slider-settings' );
                    exit();
                    
                    
                }else{
                    die('Silence is golden');
                }
            }else{
                die('Silence is golden');
            }
        }
        
        #LOADING SLIDER SHORTCODE
        function vector_slider_cb(){
            $slides = new WP_Query( array( 
                                'post_type' => 'slider', 
                                'order' => 'ASC', 
                                'orderby' => 'menu_order' )
                                 );
        	$slider = '';
        	
        	if ( $slides->have_posts() ) :
        		
        		$slider = '<div class="vector-slider">';
        		
        			$slider .= '<ul id="vs_slider" class="slides">';
        				
        			while ( $slides->have_posts() ) : $slides->the_post();
        			
        				$slider .= '<li class="vs-banner">';
        				   
        					$slider .= '<div id="slide-' . get_the_ID() . '" class="slide">';
        						
        						global $post;
        						
        							if ( has_post_thumbnail() ) {
        
        								if ( get_post_meta( $post->ID, "vs_btn_link", true ) ) 
        									$slider .= '<a href="' . get_post_meta( $post->ID, "vs_btn_link", true ) . '" title="' .  the_title_attribute ( array( 'echo' => 0 ) ) . '" >';
        
        									$slider .= get_the_post_thumbnail( $post->ID, 'slider-thumbnail', array( 'class' =>'slide-thumbnail' ) );
        
        								if ( get_post_meta( $post->ID, "vs_btn_link", true ) ) 
        									$slider .= '</a>';
        
        							}
        						$slider .= '<div class="vs-caption">';
        						$slider .= '<h2 class="slide-title">' . get_the_title() . '</h2>';
                                $slider .= '<div class="content">'. get_post_meta( $post->ID, "vs_content", true ) .'</div>';
                                $slider .= '<div class="vs-more"><a href="'.get_post_meta( $post->ID, "vs_btn_link", true ).'">'.get_post_meta( $post->ID, "vs_btn_text", true ).'</a></div>';
                                $slider .= '</div>';
        					$slider .= '</div>';
        				
        				$slider .= '</li>';
        			endwhile;
        			
        			$slider .= '</ul>';
        			
        		$slider .= '</div>';
        	
        	endif;
        
        	wp_reset_query();
        
        	return $slider;
        }
        
        
        function vector_slider_columns( $columns ) {
        	$columns = array(
        		'cb'       => '<input type="checkbox" />',
        		'image'    => __( 'Image', 'vector-slider' ),
        		'title'    => __( 'Title', 'vector-slider' ),
        		'order'    => __( 'Order', 'vector-slider' ),
        		'link'     => __( 'Link', 'vector-slider' ),
        		'date'     => __( 'Date', 'vector-slider' )
        	);
        
        	return $columns;
        }
        
        function vector_slider_load_columns( $column ) {

        	global $post;
        	
        	/* Get the post edit link for the post. */
        	$edit_link = get_edit_post_link( $post->ID );
        
        	/* Add column 'Image'. */
        	if ( $column == 'image' )		
        		echo '<a href="' . $edit_link . '" title="' . $post->post_title . '">' . get_the_post_thumbnail( $post->ID, array( 60, 60 ), array( 'title' => trim( strip_tags(  $post->post_title ) ) ) ) . '</a>';
        	
        	/* Add column 'Order'. */	
        	if ( $column == 'order' )		
        		echo '<a href="' . $edit_link . '">' . $post->menu_order . '</a>';
        	
        	/* Add column 'Link'. */
        	if ( $column == 'link' )		
        		echo '<a href="' . get_post_meta( $post->ID, "vs_btn_link", true ) . '" target="_blank" >' . get_post_meta( $post->ID, "vs_btn_link", true ) . '</a>';		
        }

        
        function vector_slider_column_order($wp_query) {
        	
        	if( is_admin() ) {
        		
        		$post_type = $wp_query->query['post_type'];
        		
        		if( $post_type == 'slider' ) {
        			$wp_query->set( 'orderby', 'menu_order' );
        			$wp_query->set( 'order', 'ASC' );
        		}
        	}	
        }
        
        #LOADING SLIDER SETTINGS
        function vector_slider_settings_page(){
            include( 'inc/core/vector-slider-index.php' );
        }
        
          
    }
    $vectorSlider = new vectorSliderClass(); 
endif;