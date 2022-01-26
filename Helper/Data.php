<?php

namespace Magelearn\Checkout\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $productFactory;
	
    /**
     * @param \Magento\Framework\App\Helper\Context              $context
     * @param \Magento\Catalog\Model\ProductFactory         $productFactory,
     * @param array                                              $data
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {
        parent::__construct($context);
        $this->productFactory = $productFactory;
    }

    /**
     * Function for getting product by SKU
     *
     * @param string $sku,
     */
    public function getProductBySku($sku)
    {
        $product = $this->productFactory->create();
        $productdata = $product->loadByAttribute('sku', $sku);
        return $productdata;
    }
}