<?php

namespace Marello\Bundle\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Marello\Bundle\InvoiceBundle\Model\ExtendInvoiceItem;
use Marello\Bundle\OrderBundle\Model\QuantityAwareInterface;
use Marello\Bundle\ProductBundle\Entity\Product;
use Marello\Bundle\ProductBundle\Entity\ProductInterface;
use Marello\Bundle\ProductBundle\Model\ProductAwareInterface;
use Marello\Bundle\TaxBundle\Model\TaxAwareInterface;
use Oro\Bundle\CurrencyBundle\Entity\PriceAwareInterface;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation as Oro;

/**
 * @ORM\Entity()
 * @Oro\Config(
 *      defaultValues={
 *          "dataaudit"={
 *              "auditable"=true
 *          }
 *      }
 * )
 * @ORM\Table(name="marello_invoice_invoice_item")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("ALL")
 */
class InvoiceItem extends ExtendInvoiceItem implements
    QuantityAwareInterface,
    PriceAwareInterface,
    TaxAwareInterface,
    ProductAwareInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     */
    protected $id;

    /**
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="items")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Oro\ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "full"=true
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $invoice;

    /**
     * @var ProductInterface|Product
     *
     * @ORM\ManyToOne(targetEntity="Marello\Bundle\ProductBundle\Entity\Product")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     * @JMS\Expose
     */
    protected $product;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name",type="string", nullable=false)
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="product_sku",type="string", nullable=false)
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $productSku;

    /**
     * @var int
     *
     * @ORM\Column(name="price",type="money")
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     *
     * @JMS\Expose
     */
    protected $price;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity",type="integer",nullable=false)
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     *
     * @JMS\Expose
     */
    protected $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="tax",type="money")
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     *
     * @JMS\Expose
     */
    protected $tax;

    /**
     * @var double
     *
     * @ORM\Column(name="discount_amount", type="money", nullable=true)
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     *
     * @JMS\Expose
     */
    protected $discountAmount;

    /**
     * @var int
     *
     * @ORM\Column(name="row_total_incl_tax",type="money", nullable=false)
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     *
     * @JMS\Expose
     */
    protected $rowTotalInclTax;

    /**
     * @var int
     *
     * @ORM\Column(name="row_total_excl_tax",type="money", nullable=false)
     * @Oro\ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     *
     * @JMS\Expose
     */
    protected $rowTotalExclTax;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        // prevent overriding product name if already being set
        if (is_null($this->productName)) {
            $this->setProductName($this->product->getName());
        }
        $this->productSku  = $this->product->getSku();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param Invoice $invoice
     * @return InvoiceItem
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @return Product|ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product|ProductInterface $product
     * @return InvoiceItem
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     * @return InvoiceItem
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductSku()
    {
        return $this->productSku;
    }

    /**
     * @param string $productSku
     * @return InvoiceItem
     */
    public function setProductSku($productSku)
    {
        $this->productSku = $productSku;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return InvoiceItem
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return InvoiceItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param int $tax
     * @return InvoiceItem
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    /**
     * @param float $discountAmount
     * @return InvoiceItem
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    /**
     * @return int
     */
    public function getRowTotalInclTax()
    {
        return $this->rowTotalInclTax;
    }

    /**
     * @param int $rowTotalInclTax
     * @return InvoiceItem
     */
    public function setRowTotalInclTax($rowTotalInclTax)
    {
        $this->rowTotalInclTax = $rowTotalInclTax;

        return $this;
    }

    /**
     * @return int
     */
    public function getRowTotalExclTax()
    {
        return $this->rowTotalExclTax;
    }

    /**
     * @param int $rowTotalExclTax
     * @return InvoiceItem
     */
    public function setRowTotalExclTax($rowTotalExclTax)
    {
        $this->rowTotalExclTax = $rowTotalExclTax;

        return $this;
    }
}
