/*============================================================
 *  Handle Preview 
==============================================================*/

// Box Shadow
jQuery('select[id*="enabled"]').change(function(){
    jthis = jQuery(this);
    var value = jthis.val();
    if ( '1' === value ){
        jQuery('.jws-discount-display').css('display','block');
    } else {
        jQuery('.jws-discount-display').css('display','none');
    }
});

// Discount Mode
jQuery('select[id*="discountmode"]').change(function(){
    jthis = jQuery(this);
    var mode = jthis.val();
    if ( 'percent' === mode ){
        jQuery('.jws-discount-display .discount').text(jwsBackendConfig.percent_discount+'%');
    }
    if ( 'nominal' === mode ){
        jQuery('.jws-discount-display .discount').text(jwsBackendConfig.currencySymbol+jwsBackendConfig.dollar_discount);
    }
});

// Style
jQuery('select[id*="style"]').change(function(){
    jthis = jQuery(this);
    var style = jthis.val();
    if ( '' !== style ){
        
        // 'bubble' is default
        if ( 'bubble' === style ){ 
            jQuery('.jws-discount-display').removeClass('corner').removeClass('box');
        }
        if ('corner' === style ){
            jQuery('.jws-discount-display').removeClass('box').addClass('corner');
        }
        if ('box' === style ){
            jQuery('.jws-discount-display').removeClass('corner').addClass('box');
        }
    }
});

// Box Shadow
jQuery('select[id*="boxShadow"]').change(function(){
    jthis = jQuery(this);
    var value = jthis.val();
    if ('1' === value){
        jQuery('.jws-discount-display').addClass('boxshadow');
    } else {
        jQuery('.jws-discount-display').removeClass('boxshadow');
    }
});


// Options that direcly control CSS
jQuery('select[id*="css"]').change(function(){
    var jthis = jQuery(this);
    var value = jthis.val();
    var idString = jthis.attr('id');
    var attribute = idString.substr(idString.indexOf('css') + 3);
    if ('' !== value ) {
        jQuery('.jws-discount-display').css(attribute, value);
    }
});

// on page load trigger all the above 'change' listeners by calling 'change' on each form select
jQuery('.entry-edit select').change(); 

/*============================================================
 *  Hiding / Showing Relevant Table Rows
==============================================================*/

// Border Options - if plugin enabled and border-width not '0' then show border color and style form selects
var borderOptionRows = jQuery('#tr-cssborderColor,#tr-cssborderStyle');
if ( '0' !== jQuery('#enabled').val() ){
    if ('0' !== jQuery('#cssborderWidth').val()){
        borderOptionRows.show();
    }
}

// Border Options - if border width is changed to '0' then hide borderColor and borderStyle form selects
// else: show borderColor and borderStyle form selects
jQuery('#cssborderWidth').change(function(){
   if ('0' !== jQuery(this).val()){
        borderOptionRows.show();
   } else{
        borderOptionRows.hide();       
   }
});

// Hide all form selects but 'enanabled' select if plugin disabled 
var optionRows = jQuery('.entry-edit tr:not("#tr-enabled"), .entry-edit h2.advanced,.entry-edit div.instruction');
if ('0' !== jQuery('#enabled').val() ){
    optionRows.show();
    jQuery('#cssborderWidth').change();
}

// When 'enabled' set to '1' show all rows then call 'change' on borderWidth select 
// to show or hide borderStyle and border Color
jQuery('#enabled').change(function(){
   if ('1'  === jQuery(this).val() ){
        optionRows.show();
        jQuery('#cssborderWidth').change();
   } else{
        optionRows.hide();
   }
});

/*============================================================
 *  Handle Positioning of Corner Style Display at different Border Widths
==============================================================*/

// handle position of corner style at different border widths
var borderWidthSelect = jQuery('#cssborderWidth');
var displayStyleSelect = jQuery('#display_style');

// continuity problems get in the way of programtically measuring the original top & right positions, so hardcoding them
var originalTop = '-11';
var originalRight = '-41';

function jws_dd_adjustCornerPostion(){
    
    // reset inline top and right position
    jQuery('.product .jws-discount-display').css('right','');
    jQuery('.product .jws-discount-display').css('top','');
    
    // measure border width then adjust position accordingly
    var discountBubbleElement = jQuery('.product .jws-discount-display.corner');
    var borderWidthSelectVal = borderWidthSelect.val().replace('px','');
    if (discountBubbleElement){
        discountBubbleElement.css({
            'top':originalTop-borderWidthSelectVal+'px',
            'right':originalRight-borderWidthSelectVal+'px'
        });
    }
}
borderWidthSelect.change(function(){
    jws_dd_adjustCornerPostion();
});
displayStyleSelect.change(function(){
    jws_dd_adjustCornerPostion();
});
jws_dd_adjustCornerPostion();
