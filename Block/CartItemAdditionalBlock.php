<?php

namespace Magelearn\Checkout\Block;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Block\Cart\Additional\Info as AdditionalBlockInfo;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template as ViewTemplate;
use Magento\Framework\View\Element\Template\Context;
/**
 * Class CartItemAdditionalBlock
 */
class CartItemAdditionalBlock extends ViewTemplate
{
    /**
     * Product
     *
     * @var ProductInterface|null
     */
    protected $product = null;
    /**
     * Product Factory
     *
     * @var ProductInterfaceFactory
     */
    protected $productFactory;
    /**
     * CartItemAdditionalBlock constructor
     *
     * @param Context $context
     * @param ProductInterfaceFactory $productFactory
     */
    public function __construct(
        Context $context,
        ProductInterfaceFactory $productFactory
    ) {
        parent::__construct($context);
        $this->productFactory = $productFactory;
    }
    /**
     * Get Product Brand Text
     *
     * @return string
     */
    public function getProductBrand(): string
    {
        $product = $this->getProduct();
        /** @var Product $product */
        $Manufacturer = (string)$product->getResource()->getAttribute('manufacturer')->getFrontend()->getValue($product);
        return $Manufacturer;
    }
    /**
     * Get product from quote item
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface
    {
        try {
            $layout = $this->getLayout();
        } catch (LocalizedException $e) {
            $this->product = $this->productFactory->create();
            return $this->product;
        }
        /** @var AdditionalBlockInfo $block */
        $block = $layout->getBlock('additional.product.info');
        if ($block instanceof AdditionalBlockInfo) {
            $item = $block->getItem();
            $this->product = $item->getProduct();
        }
        return $this->product;
    }
}