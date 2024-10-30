<?php
function jws_dd_getConfigurationOptions(){
    
    /*============================================================
     *  Assign Option Sets
    ==============================================================*/
    $yesNoOptionList = array(
        array('1','Yes'),
        array('0','No'),
    );
    
    $displayStyleOptionList = array(
        array('bubble','Bubble'),
        array('corner','Corner'),
        array('box','Box'),
    );
    
    $discountModeOptionList = array(
        array('percent','Percent Off'),
        array('nominal','Dollar Off'),
    );
    
    $borderWidthOptionList = array(
        array('0', '0 (no border)'),
        array('1px', '1 pixel '),
        array('2px', '2 pixels '),
        array('3px', '3 pixels '),
        array('4px', '4 pixels '),
        array('5px', '5 pixels ')
    );
    
    $borderStyleOptionList = array(
        array('solid',  'Solid'),
        array('double',  'Double'),
        array('dotted',  'Dotted'),
        array('dashed',  'Dashed'),
        array('groove',  'Groove'),
        array('ridge',  'Ridge'),
        array('inset',  'Inset'),
        array('outset',  'Outset'),
    );
    
    /*============================================================
     *  Other Option Lists
    ==============================================================*/
    
    $colorOptionList = array();// empty because color selection is being handled by color pickers
    
    $product_ids = wc_get_product_ids_on_sale();
    $productPreviewList = array();
    foreach($product_ids as $product_id){
        array_push($productPreviewList,array($product_id,  wc_get_product($product_id)->get_title()));
    }
    
    /*============================================================
     *  Configuration Object
    ==============================================================*/
    $jsw_select_array = array();
    
    array_push($jsw_select_array, array(
                "order"=>1,
                "slug"=>"enabled",
                "name"=>"Enabled",
                "options"=> $yesNoOptionList
                )
    );
    
    array_push($jsw_select_array, array(
                "order"=>2,
                "slug"=>"display_style",
                "name"=>"Display Style",
                "options"=> $displayStyleOptionList
                )
    );
    
    array_push($jsw_select_array, array(
                "order"=>3,
                "slug"=>"discountmode",
                "name"=>"Discount Mode",
                "options"=> $discountModeOptionList
                )
    );

    array_push($jsw_select_array, array(
                "order"=>4,
                "slug"=>"csscolor",
                "name"=>"Font Color",
                "options"=> $colorOptionList
                )
    );
    
    array_push($jsw_select_array, array(
                "order"=>5,
                "slug"=>"cssbackgroundColor",
                "name"=>"Background Color",
                "options"=> $colorOptionList
                )
    );
    
    array_push($jsw_select_array, array(
                "order"=>6,
                "slug"=>"boxShadow",
                "name"=>"Box Shadow",
                "options"=> $yesNoOptionList
                )
    );
    
    array_push($jsw_select_array, array(
                "order"=>7,
                "slug"=>"cssborderWidth",
                "name"=>"Border Width",
                "options"=> $borderWidthOptionList
                )
    );
    
    array_push($jsw_select_array, array(
                "order"=>8,
                "slug"=>"cssborderColor",
                "name"=>"Border Color",
                "options"=> $colorOptionList
                )
    );
    
    
    array_push($jsw_select_array, array(
                "order"=>9,
                "slug"=>"cssborderStyle",
                "name"=>"Border Style",
                "options"=> $borderStyleOptionList
                )
    );
    
    array_push($jsw_select_array, array(
                "order"=>11,
                "slug"=>"useInProductDetail",
                "name"=>"Use In Product Detail",
                "options"=> $yesNoOptionList
                )
    );
    
    array_push($jsw_select_array, array(
                "order"=>12,
                "slug"=>"previewProduct",
                "name"=>"Product To Use In Preview",
                "options"=> $productPreviewList
                )
    );
    
    return $jsw_select_array;
}