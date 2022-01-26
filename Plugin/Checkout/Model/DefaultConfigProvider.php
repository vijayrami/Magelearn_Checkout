<?php
namespace Magelearn\Checkout\Plugin\Checkout\Model;
 
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\PriceCurrencyInterface as CurrencyInterface;
 
class DefaultConfigProvider
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;
    
    protected $productFactory;
    
    protected $currencyInterface;
 
    /**
     * Constructor
     *
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        CurrencyInterface $currencyInterface
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->productFactory = $productFactory;
        $this->currencyInterface = $currencyInterface;
    }
 
    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        array $result
    ) {
        $items = $result['totalsData']['items'];
        foreach ($items as $index => $item) {
            $quoteItem = $this->checkoutSession->getQuote()->getItemById($item['item_id']);
            $result['quoteItemData'][$index]['manufacturer'] = $quoteItem->getProduct()->getAttributeText('manufacturer');
            
            $product_type = $quoteItem->getProduct()->getTypeId();
            $sku = $quoteItem->getProduct()->getSku();
            $qty = $quoteItem->getQty();
            
            if($product_type == 'configurable') {
                $product = $this->productFactory->create();
                $productdata = $product->loadByAttribute('sku', $sku);
                $result['quoteItemData'][$index]['current_price_value'] = $productdata->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
                $result['quoteItemData'][$index]['regular_price_value'] = $productdata->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
                $originalPrice = $productdata->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue() * $qty;
                $result['quoteItemData'][$index]['regular_price'] = $this->currencyInterface->format($originalPrice, false, 2);
            } else {
                $result['quoteItemData'][$index]['current_price_value'] = $quoteItem->getProduct()->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
                $result['quoteItemData'][$index]['regular_price_value'] = $quoteItem->getProduct()->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
                $originalPrice = $quoteItem->getProduct()->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue() * $qty;
                $result['quoteItemData'][$index]['regular_price'] = $this->currencyInterface->format($originalPrice, false, 2);
            }
            
            
        }
        return $result;
    }
}