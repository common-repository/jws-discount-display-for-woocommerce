<?php
// security measure so that data is only dropped when plugin being deleted for real
if ( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')){
    exit();
} else {
    delete_option ('jwsdiscountdisplayforwcoptions');
}
