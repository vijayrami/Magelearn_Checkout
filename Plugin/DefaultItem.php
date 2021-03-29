<?php

namespace Magelearn\Checkout\Plugin;

use Magento\Framework\Pricing\PriceCurrencyInterface as CurrencyInterface;
use Magento\Quote\Model\Quote\Item;

class DefaultItem
{
    protected $currencyInterface;

    public function __construct(
        CurrencyInterface $currencyInterface
    ) {
        $this->currencyInterface = $currencyInterface;
    }

    public function aroundGetItemData($subject, \Closure $proceed, Item $item)
    {
        $data = $proceed($item);
        $atts = [];
        $discount = 0; 
        $save = 0; 
        $currentPrice = $item->getProduct()->getPriceInfo()->getPrice('final_price')->getAmount()->getValue(); 
        $originalPrice = $item->getProduct()->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
        if ($currentPrice != $originalPrice) {
            $discount = round((($originalPrice - $item->getPrice()) / $originalPrice) * 100, 0);
            $save = $originalPrice - $currentPrice;
        }

        $atts = [
            "regular_price_value" => $originalPrice, 
            "regular_price" => $this->currencyInterface->format($originalPrice, false, 2),
            "discount_percentage" => $discount, 
            "saved_amount" => $this->currencyInterface->format($save, false, 2)
        ];

        return array_merge($data, $atts);
    }
}
