/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */ 
define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data',
    'Magento_Customer/js/customer-data',
    'mage/translate'],
function($, ko, Component, quote, checkoutData, customerData, $t){
     return Component.extend({
		initialize: function () {
           this._super();
       	},
       	defaults: {
            template: 'Magelearn_Checkout/sales/checkout/mycustomtext'
        },
        getTotalSavings: function (){
			var cartdata = customerData.get('cart');
			//var customText = $t('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged');
            return cartdata().saved_amount;
        }
     });
});