<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$vsdefault_settings = get_option( 'vsdefault_settings' );
$vs_section = array( 'slider' , 'guide' , 'about' );

?>
<header class="cgt-header clearfix">
    
</header>
<section class="vs-settings">
    <div class="container">
        <div class="row">
            <div class="primary col-md-8">
                <div class="wrapper">
                    <ul class="nav clearfix">
                        <li id="slider-settings" class="cgt-nav-tabs active"><a href="javascript:void(0)"><?php _e('Slider Settings', 'vector-slider') ?></a></li>
                        <li id="guide-settings" class="cgt-nav-tabs"><a href="javascript:void(0)"><?php _e('How to use', 'vector-slider'); ?></a></li>
                        <li id="about-settings" class="cgt-nav-tabs"><a href="javascript:void(0)"><?php _e('About', 'vector-slider'); ?></a></li>
                    </ul>
                    <div class="vs-metabox postbox clearfix">
                        <form class="vs-settings-form" method="post" action="<?php echo admin_url() . 'admin-post.php' ?>">
                            <input type="hidden" name="action" value="vector_slider_settings_action"/>
                            <?php
                                foreach( $vs_section as $key ):
                                    include_once('sections/'.$key.'-settings.php');
                                    include_once('sections/'.$key.'-settings.php');
                                    include_once('sections/'.$key.'-settings.php');
                                endforeach;
                            ?>
                            <div class="cgt-submit">
                                <?php wp_nonce_field('vector_slider_settings_action', 'vector_slider_settings_nonce'); ?>
                                <input type="submit" class="button button-primary" value="Save Changes" name="submit"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="secondary col-md-4">
                <div class="wrapper">
                    <h3><?php _e('More Resource', 'vector-slider') ?></h3>
                    <a href="<?php echo esc_url('https://www.codegearthemes.com/products/vector'); ?>" target="_blank" title="<?php _e('Vector WooCoommerce Theme' , 'vector-slider'); ?>">
                    <img src="<?php echo VSCGT_IMGDIR.'/vectortheme.png'; ?>"/>
                    </a>
                    <a class="more" href="<?php echo esc_url('https://www.codegearthemes.com'); ?>" title="<?php _e('Vector WooCoommerce Theme' , 'vector-slider'); ?>" target="_blank">
                        <?php _e('Visit Us' , 'vector-slider'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>