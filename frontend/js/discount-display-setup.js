// Manually define as 'bubble', 'corner', or 'box'
var jwsDiscountDisplayStyle = ''; 

// Manually define as 'percent' or 'nominal'
var jwsDiscountDisplayMode = ''; 

// jwsDDConfigArray defined in setup.php 
var jwsDDConfigArray = jwsDDConfigArray;

var jwsDiscountDisplay = {
    normalPrice: function(jthis){
        var selector = 'del span.amount'; 
        return Math.abs(Number((jthis.find(selector).text()).replace(/[^0-9\.]+/g,'')));
    },
    specialPrice: function(jthis){
        var selector = 'ins span.amount'; 
        return Math.abs(Number((jthis.find(selector).text()).replace(/[^0-9\.]+/g,'')));
    }, 
    mode: function(){
        var mode =  jwsDDConfigArray.discountmode;

        // if defined manually above
        if ( "undefined" === ! typeof(jwsDiscountDisplayMode)){
            if ("percent" === jwsDiscountDisplayMode || "nominal" === jwsDiscountDisplayMode){
                var mode =  jwsDiscountDisplayMode;
            }
        }

        // if specified in the url (parameter name: DiscountDisplayStyle)
        if ("undefined" !== location.search ){
            urlparams = location.search;
            if (-1 !== urlparams.search("DiscountDisplayMode=percent")){
                var mode =  "percent";
            }
            if (-1 !== urlparams.search("DiscountDisplayMode=nominal")){
                var mode =  "nominal";
            }
        }
        return mode;
     },
    style: function(){
        var style =  jwsDDConfigArray.display_style;

        // if defined manually above
        if ("bubble" === jwsDiscountDisplayStyle|| "corner" ===jwsDiscountDisplayStyle || "box"  ===jwsDiscountDisplayStyle){
            var style =  jwsDiscountDisplayStyle;
        }

        // if specified in the url (parameter name: DiscountDisplayStyle)
        if ( "undefined" !==  location.search){
            urlparams = location.search;
            if (-1 !== urlparams.search("DiscountDisplayStyle=bubble") ){
                var style =  "bubble";
            }
            if (-1 !==  urlparams.search("DiscountDisplayStyle=corner")){
                var style =  "corner";
            }
            if (-1 !== urlparams.search("DiscountDisplayStyle=box")){
                var style =  "box";
            }
        }
        return style;
    },
    nominalDiscount: function(jthis){
           return Math.floor(jwsDiscountDisplay.normalPrice(jthis) - jwsDiscountDisplay.specialPrice(jthis));
    },
    percentDiscount: function(jthis){
           return Math.floor(jwsDiscountDisplay.nominalDiscount(jthis)/jwsDiscountDisplay.normalPrice(jthis)*100);
    },
    discountBubble: function(jthis){
        var myElementString = "<span class='jws-discount-display'><span class='discount'></span><span class='off'>"+jwsDDConfigArray.offString+"</span></span>";
        var ele = jQuery(myElementString); 
            switch(jwsDiscountDisplay.style()) {
                case 'corner':
                    ele.addClass('corner');
                    break;
                case 'box':
                    ele.addClass('box');
            }

            /* Discount or Percent*/
            switch(jwsDiscountDisplay.mode()) {
                case 'nominal':
                    ele.find('span.discount').text(jwsDDConfigArray.currencySymbol+jwsDiscountDisplay.nominalDiscount(jthis));  
                    break;
                case 'percent':
                    ele.find('span.discount').text(jwsDiscountDisplay.percentDiscount(jthis)+"%");
            }

            /* AddBoxShadow */
            if ('1' === jwsDDConfigArray.boxShadow  ){
                ele.addClass('boxshadow');
            }

            for (var k in jwsDDConfigArray){
                if (( -1 !== k.search('css')) || ("" === jwsDDConfigArray[k] )){
                    var prop = k.replace('css','');
                    ele.css(prop,jwsDDConfigArray[k]);
                }
            }
        return ele;
    }, 
    adjustCornerPosition: function(){

        // handle position of corner style at different border widths
        var discountCornerElement = jQuery('.jws-discount-display.corner');
        if (discountCornerElement.length > 0){
            var originalTop = discountCornerElement.css('top').replace('px','');
            var originalRight = discountCornerElement.css('right').replace('px','');
            var borderWidthVal = discountCornerElement.first().css('border-bottom-width').replace('px','');
            jQuery(discountCornerElement).each(function(){
                jQuery(this).css({
                    'top':originalTop-borderWidthVal+'px',
                    'right':originalRight-borderWidthVal+'px'
                });
            }); 
        }
    }, 
    render: function(){
        var saleProductSelector = 'ul.products li.product, .summary';
        jQuery(saleProductSelector).each(function(){
            var jthis = jQuery(this);

            // if there is a <del> (containing 'normal price') && a special price can be calculated 
            if (( 1 === jthis.find('.price del').length) && (jwsDiscountDisplay.specialPrice(jthis)) ){
                    
                // if element is the (product detail) summary
                if (jthis.hasClass('summary')){
					
					// if discount display is enabled for (product detail) summary
					if ('1' === jwsDDConfigArray.useInProductDetail){
                        // attach the discount display element to the main element
                        var ele = jQuery("main");
                        if ('corner' === jwsDiscountDisplay.style() ){
                            ele.css('overflow', 'hidden');
                        }
                        ele.prepend(jwsDiscountDisplay.discountBubble(jthis));
					}
					
                // product thumbnails
                } else { 
                    jthis.prepend(jwsDiscountDisplay.discountBubble(jthis));
                }
            }
        });
        jwsDiscountDisplay.adjustCornerPosition();
    }
};

if( 'undefined' !== typeof jwsDDConfigArray ){
    if ('1' === jwsDDConfigArray.enabled) {
            jwsDiscountDisplay.render();
    }
}
