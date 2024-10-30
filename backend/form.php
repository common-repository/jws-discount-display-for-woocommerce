<?php

function jws_dd_cmp($a, $b) {
    if ($a['order'] == $b['order']) {
        return 0;
    }
    return ($a['order'] < $b['order']) ? -1 : 1;
}

function jws_dd_getInlineJsString(){
    
    require dirname( __FILE__ ) . '/helper.php';
    
    // setup inline script content string
    $jsString =  "var dollar_discount = '" .$dollar_discount."';";
    $jsString .= "var percent_discount = '" .$percent_discount."';";
    $jsString .= "var currencySymbol = '" . html_entity_decode(get_woocommerce_currency_symbol())."';";
    $jsString .= "jwsBackendConfig = {dollar_discount:dollar_discount, percent_discount:percent_discount, currencySymbol:currencySymbol};";

    // array for CSS color-related configurable properities
    for ($x = 0; $x < count($jws_dd_select_array); $x++): 
        if( stripos($jws_dd_select_array[$x]['slug'], 'color') !== false):
            $colorCssProperties[] = $jws_dd_select_array[$x]['slug'];
        endif;
    endfor;
    // update preview based values returned from color pickers on change
    for ($x = 0; $x < count($colorCssProperties); $x++):
        $jsString .= "var ".$colorCssProperties[$x]."Options = {";
            $jsString .="change: function(event, ui){";
                 $cssProp = str_replace("css","",$colorCssProperties[$x]);
                 $jsString .= "jQueryCssProp = '$cssProp';";
                $jsString .= "jQuery('.jws-discount-display').css( jQueryCssProp, ui.color.toString());";
            $jsString .= "}";
        $jsString .= "};";
    endfor;
    
    $jsString .= "jQuery(document).ready(function($){";
        for ($x = 0; $x < count($colorCssProperties); $x++):

            // Register Color Pickers
            $jsString .= "jQuery('.$colorCssProperties[$x]').wpColorPicker($colorCssProperties[$x]Options);";

            // update preview based values returned from color pickers on Page Load
            $cssProp = str_replace("css","",$colorCssProperties[$x]);
            $jsString .= "jQueryCssProp = '$cssProp';";
            $jsString .= "jQuery('.jws-discount-display').css( jQueryCssProp, jQuery('.$colorCssProperties[$x]').val());";
        endfor;
    $jsString .= "});";
    
    return $jsString;
    
}

add_action('admin_enqueue_scripts', 'jws_dd_backend_scripts');
function jws_dd_backend_scripts($hook){
    if ( 'woocommerce_page_discount-display' != $hook ) {
        return;
    }
    wp_register_script('jws_discountdisplay_backend_script', plugins_url('js/admin.js', __FILE__), array('jquery'),'1.1', true);
    wp_enqueue_script('jws_discountdisplay_backend_script');
    wp_add_inline_script( 'jws_discountdisplay_backend_script', 
        jws_dd_getInlineJsString(),
		'before' );
}

// load styles - 'admin_enqueue_scripts' is used for both admin css and js (https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts)
add_action('admin_enqueue_scripts', 'jws_dd_backend_styles');
function jws_dd_backend_styles($hook){
    if ( 'woocommerce_page_discount-display' != $hook ) {
        return;
    }
    wp_register_style( 'jws_discountdisplay_backend_preview', plugins_url( 'css/style.css', __FILE__ ), array(), '', 'all' );
    wp_register_style( 'jws_discountdisplay_shared', plugins_url( '../css/style.css', __FILE__ ), array(), '', 'all' );
    wp_enqueue_style( 'jws_discountdisplay_backend_preview');
    wp_enqueue_style( 'jws_discountdisplay_shared');
}

/*============================================================
 *  Call Color Picker
==============================================================*/
add_action( 'admin_enqueue_scripts', 'jws_dd_mw_enqueue_color_picker' );
function jws_dd_mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker', plugins_url('load-scipts.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

function jws_dd_submenu_page_callback() {

    require dirname( __FILE__ ) . '/helper.php';
    
    ?>
    <div class="wrap entry-edit">
        <div class ="discount-display-form">
            <?php settings_errors() ?>

            <form method="post" action="options.php">
                <?php settings_fields( 'settings-group' ); ?>
                <h2><?php echo esc_html__( 'General Settings', 'jwsdiscountdisplay' ) ?></h2>
                <table class="form-table">
                    <?php 
                    
                    // For the top 'General' Settings
                    for ($x = 0; $x < count($jws_dd_select_array)-2; $x++): 
                        
                        // If Color Property
                        if( stripos($jws_dd_select_array[$x]['slug'], 'color') === false):?> 
                            <tr valign="top" id="tr-<?php echo $jws_dd_select_array[$x]['slug'];?>">
                                <th scope="row"><?php echo esc_html__( $jws_dd_select_array[$x]['name'], 'jwsdiscountdisplay' ) ?></th>
                                <td>
                                    <select id="<?php echo ($jws_dd_select_array[$x]['slug']);?>" name="jwsdiscountdisplayforwcoptions[<?php echo $jws_dd_select_array[$x]['slug'] ?>]" class="select">
                                        <?php foreach($jws_dd_select_array[$x]['options'] as $option): ?>
                                            <option <?php selected( $options[$jws_dd_select_array[$x]['slug']], $option[0] ); ?> value="<?php echo $option[0];?>"><?php echo esc_html__($option[1]);?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php 
                        
                        // Else (Non-Color Property)
                        else: ?> 
                            <tr valign="top" id="tr-<?php echo $jws_dd_select_array[$x]['slug'];?>">
                                <th scope="row"><?php echo esc_html__($jws_dd_select_array[$x]['name'], 'jwsdiscountdisplay' ) ?></th>
                                <td>
                                    <input type="text" id="<?php echo ($jws_dd_select_array[$x]['slug']);?>" name="jwsdiscountdisplayforwcoptions[<?php echo $jws_dd_select_array[$x]['slug'] ?>]"  value="<?php echo $options[$jws_dd_select_array[$x]['slug']];?>" class="<?php echo $jws_dd_select_array[$x]['slug'];?>" />
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endfor; ?>
                </table>
                
                <h2 class='advanced'><?php echo esc_html__( 'Advanced Settings', 'jwsdiscountdisplay' ) ?></h2>
                <table class="form-table">
                    <?php 
                    
                    // Last 2 'Advanced' Settings
                    for ($x =  count($jws_dd_select_array)-2; $x < count($jws_dd_select_array); $x++): ?>
                        <tr valign="top" id="tr-<?php echo $jws_dd_select_array[$x]['slug'];?>">
                            <th scope="row"><?php echo esc_html__( $jws_dd_select_array[$x]['name'], 'jwsdiscountdisplay' ) ?></th>
                            <td>
                                <select id="<?php echo ($jws_dd_select_array[$x]['slug']);?>" name="jwsdiscountdisplayforwcoptions[<?php echo $jws_dd_select_array[$x]['slug'] ?>]" class=" select">
                                    <?php foreach($jws_dd_select_array[$x]['options'] as $option): ?>
                                        <option <?php selected( $options[$jws_dd_select_array[$x]['slug']], $option[0] ); ?> value="<?php echo $option[0];?>"><?php echo esc_html($option[1], 'jwsdiscountdisplay' );?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </table>
                <div class = 'instruction'><span class = 'ast'>*</span>Save Changes to update the preview product.</div>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php echo esc_html__( 'Save Changes', 'jwsdiscountdisplay' ); ?>" />
                </p>

            </form>
        </div>
        <div class = "discount-display-preview">
            <h2><?php echo esc_html__( 'Preview', 'jwsdiscountdisplay' ) ?></h2>
            <ul class = "products">
                <li class="product type-product has-post-thumbnail product_cat-posters instock sale  purchasable ">
                    <span class="jws-discount-display" style="display:none;"><span class="discount"></span><span class="off"><?php echo esc_html__('Off', 'jwsdiscountdisplay'); ?></span></span>
                    <a href="javascript:void(0)" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                        <img width="300" height="300" src="<?php echo $img_src; ?>" class="attachment-shop_catalog size-shop_catalog wp-post-image" alt="">
                        <h2 class="woocommerce-loop-product__title"><?php echo $title; ?></h2>
                        <span class="onsale">Sale!</span>
                        <span class="price">
                            <del>
                                <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $regular_price;?></span>
                            </del> 
                            <ins>
                                <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $sale_price; ?></span>
                            </ins>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
<?php
}