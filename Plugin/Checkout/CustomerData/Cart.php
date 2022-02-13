<?php
namespace Magelearn\Checkout\Plugin\Checkout\CustomerData;
 
class Cart
{
    protected $checkoutSession;
    protected $checkoutHelper;
    protected $quote;
    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $priceHelper;
    protected $_logger;
    
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->checkoutHelper = $checkoutHelper;
        $this->priceHelper = $priceHelper;
        $this->_logger = $logger;
    }
    
    /**
     * Get active quote.
     *
     * @return Quote
     */
    protected function getQuote()
    {
        if ($this->quote === null) {
            $this->quote = $this->checkoutSession->getQuote();
        }
 
        return $this->quote;
    }
    
    protected function getDiscountAmount()
    {
        $discountAmount = 0;
        foreach($this->getQuote()->getAllVisibleItems() as $item){
            $discountAmount += ($item->getDiscountAmount() ? $item->getDiscountAmount() : 0);
        }
        return $discountAmount;
    }
    
    /**
     * Add grand total to result
     *
     * @param \Magento\Checkout\CustomerData\Cart $subject
     * @param array $result
     * @return array
     */
    public function afterGetSectionData(
        \Magento\Checkout\CustomerData\Cart $subject,
        $result
        ) {
            $savedAmount = 0;
            $items = $this->getQuote()->getAllVisibleItems();
            
            if (is_array($result['items'])) {
                foreach ($result['items'] as $key => $itemAsArray) {
                    $savedAmount += $itemAsArray['saved_amount_value'] ?? 0;
                }
            }
            $result['saved_amount_no_html'] = $savedAmount;
            
            $result['discount_amount_no_html'] = -$this->getDiscountAmount();
            $result['discount_amount'] = $this->checkoutHelper->formatPrice(-$this->getDiscountAmount());
            
            $result['saved_amount'] = $this->checkoutHelper->formatPrice($savedAmount);
            
            return $result;
    }
}