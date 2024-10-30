<?php

add_action('admin_menu', 'jws_dd_register_submenu_page');
function jws_dd_register_submenu_page() {
    
    add_submenu_page( 'woocommerce', 'Discount Display', 'Discount Display', 'manage_options', 'discount-display', 'jws_dd_submenu_page_callback' ); 
}

require dirname( __FILE__ ) . '/options.php';
require dirname( __FILE__ ) . '/form.php';
require dirname( __FILE__ ) . '/validation.php';



