<?php
/**
 * Returns editable options for the Dandyish theme.
 * 
 * @see OnpSL_ThemeManager::getEditableOptions
 * 
 * @since 3.3.3
 * @return mixed[]
 */
function onp_sl_get_dandyish_theme_editable_options() {
    
    return array(
        array(__('Locker Container', 'sociallocker'), 'locker-box', array(
            
            // accordion           
            array(
                'type' => 'accordion',
                'items' => array(

                    // background                    
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Background', 'sociallocker'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'background_type',
                                'default' => 'color',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'sociallocker'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color',
                                                'name' => 'background_color',
                                                'title' => __('Set up color and opacity:', 'sociallocker'),
                                                'default' => '#f9f9f9'
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Gradient', 'sociallocker'),
                                        'name' => 'gradient',
                                        'items' => array(
                                            array(
                                                'type' => 'gradient',
                                                'name' => 'background_gradient',
                                                'title' => __('Set up gradient:', 'sociallocker')
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Pattern', 'sociallocker'),
                                        'name' => 'image',
                                        'items' => array(
                                            array(
                                                'type' => 'pattern',
                                                'name' => 'background_image',
                                                'title' => __('Set up pattern', 'sociallocker')
                                            )
                                        )
                                    ),
                                )
                            )
                        )
                    ),

                    // outer borders     
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Outer Border', 'sociallocker'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'outer_border_type',
                                'default' => 'image',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'sociallocker'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color-and-opacity',
                                                'name' => 'outer_border_color',
                                                'title' => __('Set up color for outer border:', 'sociallocker'),
                                                'default' => array('color' => '#e6e6e6', 'opacity' => 100)
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Gradient', 'sociallocker'),
                                        'name' => 'gradient',
                                        'items' => array(
                                            array(
                                                'type' => 'gradient',
                                                'name' => 'outer_border_gradient',
                                                'title' => __('Set up gradient for outer border:', 'sociallocker')
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Pattern', 'sociallocker'),
                                        'name' => 'image',
                                        'items' => array(
                                            array(
                                                'type' => 'pattern',
                                                'name' => 'outer_border_image',
                                                'title' => __('Set up pattern for outer border:', 'sociallocker'),
                                                'default' => array( 
                                                    'url' => get_site_url() . '/wp-content/plugins/sociallocker-next/assets/img/dandysh-border.png',
                                                    'color' => null
                                                ),
                                                'patterns' => array(
                                                    array(
                                                        'preview' => get_site_url() . '/wp-content/plugins/sociallocker-next/assets/img/dandysh-border.png',
                                                        'pattern' => get_site_url() . '/wp-content/plugins/sociallocker-next/assets/img/dandysh-border.png'
                                                    )
                                                )
                                            )
                                        )
                                    )
                            )),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'outer_border_size',
                                'title' => __('Outer border width', 'sociallocker'),
                                'range' => array(0, 99),                              
                                'default' => 7,
                                'units' => 'px'
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'outer_border_radius',
                                'title' => __('Outer border radius', 'sociallocker'),
                                'range' => array(0, 99),
                                'default' => 12,
                                'units' => 'px'
                            )
                        )
                    ),

                    // inner borders     
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Inner Border', 'sociallocker'),
                        'items' => array(
                            array(
                                'type' => 'control-group',
                                'name' => 'inner_border_type',
                                'default' => 'color',
                                'items' => array(
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Color', 'sociallocker'),
                                        'name' => 'color',
                                        'items' => array(
                                            array(
                                                'type' => 'color-and-opacity',
                                                'name' => 'inner_border_color',
                                                'title' => __('Set up color for inner border:', 'sociallocker'),
                                                'default' => array('color' => '#ffffff', 'opacity' => 100)
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Gradient', 'sociallocker'),
                                        'name' => 'gradient',
                                        'items' => array(
                                            array(
                                                'type' => 'gradient',
                                                'name' => 'inner_border_gradient',
                                                'title' => __('Set up gradient for inner border:', 'sociallocker')
                                            )
                                        )
                                    ),
                                    array(
                                        'type' => 'control-group-item',
                                        'title' => __('Pattern', 'sociallocker'),
                                        'name' => 'image',
                                        'items' => array(
                                            array(
                                                'type' => 'pattern',
                                                'name' => 'inner_border_image',
                                                'title' => __('Set up pattern for inner border:', 'sociallocker')
                                            )
                                        )
                                    )
                            )),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'inner_border_size',
                                'title' => __('Inner border width', 'sociallocker'),
                                'range' => array(0, 99),                              
                                'default' => 5,
                                'units' => 'px'
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'inner_border_radius',
                                'title' => __('Inner border radius', 'sociallocker'),
                                'range' => array(0, 99),
                                'default' => 10,
                                'units' => 'px'
                            )
                        )
                    ),

                // font options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Text', 'sociallocker'),
                    'items'     => array(
                        array(
                            'type'      => 'font',                                
                            'name'      => 'header_text',
                            'title'     => __('Header text', 'sociallocker'),
                            'default'   => array(
                                            'size' => 16, 
                                            'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                            'color' => '#111111'
                                        ),
                            'units'     => 'px'
                        ),
                        array(
                            'type'      => 'font',                                
                            'name'      => 'message_text',
                            'title'     => __('Message text', 'sociallocker'),
                            'default'   => array(
                                            'size' => 13, 
                                            'family' => 'Arial, "Helvetica Neue", Helvetica, sans-serif', 
                                            'color' => '#111111'
                                           ),
                            'units'     => 'px'
                        ),
                        array(
                            'type'      => 'checkbox',
                            'way'       => 'buttons',
                            'name'      => 'header_icon',
                            'title'     => __('Header icons', 'sociallocker'),
                            'default'   => 1
                        )
                    )
                ),

                //  paddings options                    
                array(
                    'type'      => 'accordion-item',
                    'title'     => __('Paddings', 'sociallocker'),
                    'items'     => array(
                        array(
                            'type'      => 'paddings-editor',
                            'name'      => 'container_paddings',
                            'title'     => __('Box paddings', 'sociallocker'),
                            'units'     => 'px',
                            'default'   => '30px 30px 30px 30px'
                        ),
                        array(
                            'type'      => 'integer',
                            'name'      => 'after_header_margin',
                            'way'       => 'slider',
                            'title'     => __('Margin after header', 'sociallocker'),
                            'units'     => 'px',
                            'default'   => '0'
                        ),
                        array(
                            'type'      => 'integer',
                            'name'      => 'after_message_margin',
                            'way'       => 'slider',
                            'title'     => __('Margin after message', 'sociallocker'),
                            'units'     => 'px',
                            'default'   => '5'
                        ),                            
                    )
                ))
            )
        )),
    
        array(__('Locker Buttons', 'sociallocker'), 'buttons', array(
            
            // accordion
            array(
                'type' => 'accordion',
                'items' => array(

                    // background options
                    array(
                        'type' => 'accordion-item',
                        'title' => __('Mounts', 'sociallocker'),
                        'items' => array(
                            array(
                                'type' => 'color',
                                'name' => 'button_mount_color',
                                'title' => __('Color and opacity', 'sociallocker'),
                                'default' => '#ffffff'
                            ),
                            array(
                                'type' => 'integer',
                                'way' => 'slider',
                                'name' => 'button_mount_radius',
                                'title' => __('Border radius', 'sociallocker'),
                                'range' => array(0, 99),
                                'default' => 7,
                                'units' => 'px'
                            )
                        )
                    )
                )
            )
        ))
    );
}

/**
 * Registers links for the Secrets theme between form controls and CSS.
 */
function onp_sl_register_dandyish_theme_options_to_css( $rules, $theme ) {
    if ( $theme !== 'dandyish') return $rules;
    
    return array(   
        
        // background 
        'background_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-inner-wrap'
        ),
        'background_image' => array(
            'css' => array(
                'background-image: url("{value}");',
                'background-repeat: repeat;'
            ),
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-inner-wrap'
        ),
        'background_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-inner-wrap'
        ),
        // end background
        
        // outer border
        'outer_border_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector'  => '.onp-sociallocker-dandyish'
        ),
        'outer_border_image' => array(
            'css' => array(
                'background-image: url("{value}");',
                'background-repeat: repeat;'
            ),
            'selector'  => '.onp-sociallocker-dandyish'
        ),
        'outer_border_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector'  => '.onp-sociallocker-dandyish'
        ),
        'outer_border_size' => array(
            'css' => 'padding: {value}px;',
            'selector' => '.onp-sociallocker-dandyish'
        ),
        'outer_border_radius' => array(
            'css' => array(
                'border-radius: {value}px;',
                '-moz-border-radius:{value}px;',
                '-webkit-border-radius:{value}px;'
            ),
            'selector' => '.onp-sociallocker-dandyish'
        ),
        // end outer border
        
        // inner border
        'inner_border_color' => array(
            'css' => 'background: {value|onp_to_rgba};',
            'selector'  => '.onp-sociallocker-dandyish .onp-sociallocker-outer-wrap'
        ),
        'inner_border_image' => array(
            'css' => array(
                'background-image: url("{value}");',
                'background-repeat: repeat;'
            ),
            'selector'  => '.onp-sociallocker-dandyish .onp-sociallocker-outer-wrap'
        ),
        'inner_border_gradient' => array(
            'css' => array(
                'background: {value|onp_to_gradient};',
                'background: -webkit-{value|onp_to_gradient};',
                'background: -moz-{value|onp_to_gradient};',
                'background: -o-{value|onp_to_gradient};',
            ),
            'selector'  => '.onp-sociallocker-dandyish .onp-sociallocker-outer-wrap'
        ),
        'inner_border_size' => array(
            'css' => 'padding: {value}px;',
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-outer-wrap'
        ),
        'inner_border_radius' => array(
            'css' => array(
                'border-radius: {value}px;',
                '-moz-border-radius:{value}px;',
                '-webkit-border-radius:{value}px;'
            ),
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-outer-wrap, .onp-sociallocker-dandyish onp-sociallocker-inner-wrap'
        ),
        // end inner border

        // text
        'header_icon' => array(
            'css' => 'display:none !important;',
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-strong::before, .onp-sociallocker-dandyish .onp-sociallocker-strong::after'
        ),
        'header_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-text .onp-sociallocker-strong'
        ),
        'message_text' => array(
            'css' => array(
                'font-family: {family|stripcslashes};',
                'font-size: {size}px;',
                'color: {color}; text-shadow:none;'
            ),
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-text, .onp-sociallocker-dandyish .onp-sociallocker-timer'
        ),
        //end text
        
        // paddings 
        'container_paddings' => array(
            'css' => 'padding: {value};',
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-inner-wrap'
        ),
        'after_header_margin' => array(
            'css' => 'margin-bottom: {value}px;',
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-text .onp-sociallocker-strong'
        ),
        'after_message_margin' => array(
            'css' => 'margin-top: {value}px;',
            'selector' => '.onp-sociallocker-dandyish .onp-sociallocker-text + .onp-sociallocker-buttons'
        ),
        // end paddings 
     
        //button
        'button_mount_color' => array(
            'css' => 'background: {value};',
            'selector'  => '.onp-sociallocker-dandyish .onp-sociallocker-button-inner-wrap' 
        ),
        'button_mount_radius' => array(         
            'css'       => array(
                            'border-radius: {value}px;',
                            '-moz-border-radius:{value}px;',
                            '-webkit-border-radius:{value}px;'                           
                           ),
            'selector'  => '.onp-sociallocker-dandyish .onp-sociallocker-button, .onp-sociallocker-dandyish .onp-sociallocker-button-inner-wrap' 
        )       
        
    );
}

