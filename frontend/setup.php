<?php
add_action( 'wp_enqueue_scripts', 'jws_dd_frontend_all_scriptsandstyles' );
function jws_dd_frontend_all_scriptsandstyles() {
    wp_register_style( 'jws_dd_discountdisplay', plugins_url( '../css/style.css', __FILE__ ), array(), '', 'all' );
    wp_enqueue_style( 'jws_dd_discountdisplay');
    
    
    wp_register_script('jws_dd_discount-display-setup', plugins_url( 'js/discount-display-setup.js', __FILE__ ), array('jquery'),'1.1', true);
    wp_enqueue_script('jws_dd_discount-display-setup');
    
    if ($config_array = get_option( 'jwsdiscountdisplayforwcoptions' )):?>
        <?php if ('1' === $config_array['enabled']):
            foreach ($config_array as $key => $value){
                $config_array[$key] = esc_attr($value);
            }
            $jsString = "var jwsDDConfigArray = ".json_encode($config_array).";";
            $jsString .= "jwsDDConfigArray.currencySymbol = '" . html_entity_decode(get_woocommerce_currency_symbol())."';";
            $jsString .= "jwsDDConfigArray.offString = '" . esc_html__('Off','jwsdiscountdisplay')."';";
            wp_add_inline_script( 'jws_dd_discount-display-setup', 
                    $jsString,
                    'before' );
        endif; ?>
    <?php endif; 
}