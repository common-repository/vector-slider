<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

#SLIDE HEIGHT
if(  isset($vsdefault_settings['vs_slide_height']) && $vsdefault_settings['vs_slide_height'] !='' ):
    $vs_slide_height = $vsdefault_settings['vs_slide_height'];
else:
    $vs_slide_height = '481';
endif;

#SLIDE EFFECT
if(  isset($vsdefault_settings['vs_slide_effect']) && $vsdefault_settings['vs_slide_effect'] !='' ):
    $vs_slide_effect = $vsdefault_settings['vs_slide_effect'];
else:
    $vs_slide_effect = 'slide';
endif;

#SLIDE DELAY
if(  isset($vsdefault_settings['vs_slide_delay']) && $vsdefault_settings['vs_slide_delay'] !='' ):
    $vs_slide_delay = $vsdefault_settings['vs_slide_delay'];
else:
    $vs_slide_delay = '1000';
endif;

#SLIDE DURATION
if(  isset($vsdefault_settings['vs_slide_duration']) && $vsdefault_settings['vs_slide_duration'] !='' ):
    $vs_slide_duration = $vsdefault_settings['vs_slide_duration'];
else:
    $vs_slide_duration = '2000';
endif;
?>

<div id="vs-section-slider-settings" class="vs-section">
    <div class="wrapper">
        <div class="vs-field-wrapper">
            <label for='vs_slide_height'><strong><?php _e('SLIDER HEIGHT', 'vector-slider'); ?></strong></label>
             <br />
            <div class="cgt-field">
                <input type="text" name="vs[vs_slide_height]" value="<?php echo esc_attr( $vs_slide_height );?>"/><span>Px</span>
            </div>
        </div>
        <div class="vs-field-wrapper">
            <label for='vs_slide_delay'><strong><?php _e('SLIDER EFFECT', 'vector-slider'); ?></strong></label>
            <br />
            <div class="cgt-field">
                <select name="vs[vs_slide_effect]">
                    <option value="slide" <?php if( $vs_slide_effect == 'slide' ){ echo 'selected="selected"'; }?>><?php _e('Slide', 'vector-slider'); ?></option>
                    <option value="fade" <?php if( $vs_slide_effect == 'fade' ){ echo 'selected="selected"'; }?>><?php _e('Fade', 'vector-slider'); ?></option>
                </select>
            </div>
        </div>
        <div class="vs-field-wrapper">
            <label for='vs_slide_delay'><strong><?php _e('SLIDER DELAY', 'vector-slider'); ?></strong></label>
             <br />
            <div class="cgt-field">
                <input type="text" name="vs[vs_slide_delay]" value="<?php echo esc_attr( $vs_slide_delay );?>"/>
            </div>
        </div>
        <div class="vs-field-wrapper">
            <label for='vs_slide_delay'><strong><?php _e('SLIDER DURATION', 'vector-slider'); ?></strong></label>
             <br />
            <div class="cgt-field">
                <input type="text" name="vs[vs_slide_duration]" value="<?php echo esc_attr( $vs_slide_duration );?>"/>
            </div>
        </div>
    </div>
</div>