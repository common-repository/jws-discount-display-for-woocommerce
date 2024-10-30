<?php

    /*============================================================
     *  Sort configuration rows by 'order' key
    ==============================================================*/
    $jws_dd_select_array =  jws_dd_getConfigurationOptions();
    usort($jws_dd_select_array, "jws_dd_cmp");

    /*============================================================
     *  Assign Option Defaults
    ==============================================================*/
    $options = get_option( 'jwsdiscountdisplayforwcoptions' );
    if ( ! is_array($options)){
		$options = [];
    }
    $options['display_style'] = isset($options['display_style']) ? $options['display_style'] : "bubble" ;
    $options['enabled'] = isset($options['enabled']) ? $options['enabled'] : "0" ;
    $options['discountmode'] = isset($options['discountmode']) ? $options['discountmode'] : "percent" ;
    $options['csscolor'] = isset($options['csscolor']) ? $options['csscolor'] : "#c90d00" ;
    $options['cssbackgroundColor'] = isset($options['cssbackgroundColor']) ? $options['cssbackgroundColor'] : "#27f4e0" ;
    $options['boxShadow'] = isset($options['boxShadow']) ? $options['boxShadow'] : "0" ;
    $options['cssborderWidth'] = isset($options['cssborderWidth']) ? $options['cssborderWidth'] : "2px" ;
    $options['cssborderColor'] = isset($options['cssborderColor']) ? $options['cssborderColor'] : "#108c00" ;
    $options['cssborderStyle'] = isset($options['cssborderStyle']) ? $options['cssborderStyle'] : "dashed" ;
    $options['useInProductDetail'] = isset($options['useInProductDetail']) ? $options['useInProductDetail'] : "1" ;
    $options['previewProduct'] = isset($options['previewProduct']) ? $options['previewProduct'] : wc_get_product_ids_on_sale()[0] ;
    
    /*============================================================
     *  Set Product For Preview
    ==============================================================*/
    $product_id = $options['previewProduct'];
    $product = wc_get_product($product_id);
    $title =  esc_html($product->get_title());
    $regular_price_int = $product->get_regular_price();
    $sale_price_int = $product->get_sale_price();
    
    $dollar_discount = esc_html(floor($regular_price_int - $sale_price_int));
    $percent_discount = esc_html(floor((($regular_price_int - $sale_price_int) / $regular_price_int)*100));
    
            
    $regular_price = esc_html(number_format((float)$product->get_regular_price(),2, '.', ''));
    $sale_price = esc_html(number_format((float)$product->get_sale_price(),2, '.', ''));
    $img_src = esc_attr(wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' )[0]);