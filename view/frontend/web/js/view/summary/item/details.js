/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'escaper'
], function (Component, escaper) {
    'use strict';
	var quoteItemData = window.checkoutConfig.quoteItemData;
    return Component.extend({
        defaults: {
            template: 'Magelearn_Checkout/summary/item/details',
            allowedTags: ['b', 'strong', 'i', 'em', 'u']
        },
		quoteItemData: quoteItemData,
        /**
         * @param {Object} quoteItem
         * @return {String}
         */
        getNameUnsanitizedHtml: function (quoteItem) {
            var txt = document.createElement('textarea');

            txt.innerHTML = quoteItem.name;

            return escaper.escapeHtml(txt.value, this.allowedTags);
        },

        /**
         * @param {Object} quoteItem
         * @return {String}Magento_Checkout/js/region-updater
         */
        getValue: function (quoteItem) {
            return quoteItem.name;
        },
        getManufacturer: function(quoteItem) {
            var item = this.getItem(quoteItem.item_id);
            return item.manufacturer;
        },
        getRegularPrice: function(quoteItem) {
            var item = this.getItem(quoteItem.item_id);
            return item.regular_price_value;
        },
        getRegularPriceHtml: function(quoteItem) {
            var item = this.getItem(quoteItem.item_id);
            return item.regular_price;
        },
        getCurrentPrice: function(quoteItem) {
            var item = this.getItem(quoteItem.item_id);
            return item.current_price_value;
        },
        getItem: function(item_id) {
            var itemElement = null;
            _.each(this.quoteItemData, function(element, index) {
                if (element.item_id == item_id) {
                    itemElement = element;
                }
            });
            return itemElement;
        }
    });
});
