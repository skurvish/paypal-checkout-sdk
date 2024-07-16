<?php

namespace PayPal\Checkout\Orders;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.
 */
class AmountBreakdown extends Amount
{
    /**
     * The subtotal for all items. Required if the request includes purchase_units[].items[].unit_amount.
     * Must equal the sum of (items[].unit_amount * items[].quantity) for all items.
     * item_total.value can not be a negative number.
     * @var Money
     */
    protected Money $item_total;

    /**
     * The discount for all items within a given purchase_unit. discount.value can not be a negative number.
     * @var Money|null
     */
    protected ?Money $discount = null;

    /**
     * The shipping fee for all items within a given purchase_unit. shipping.value can not be a negative number.
     */
    protected ?Money $shipping = null;

    /**
     * The handling fee for all items within a given purchase_unit. handling.value can not be a negative number.
     */
    protected ?Money $handling = null;

    /**
     * The total tax for all items. Required if the request includes purchase_units.items.tax. 
     * Must equal the sum of (items[].tax * items[].quantity) for all items. tax_total.value can not be a negative number.
     */
    protected ?Money $tax_total = null;

    /**
     * The insurance fee for all items within a given purchase_unit. insurance.value can not be a negative number.
     */
    protected ?Money $insurance = null;

    /**
     * The shipping discount for all items within a given purchase_unit. shipping_discount.value can not be a negative number.
     */
    protected ?Money $shipping_discount = null;

    /**
     * create a new AmountBreakdown instance.
     * @param  string  $value
     * @param  string  $currency_code
     * @throws UnknownCurrencyException
     */
    public function __construct(string $value, string $currency_code = 'USD')
    {
        parent::__construct($value, $currency_code);
        $this->item_total = Money::of($value, $currency_code);
    }

    /**
     * @param  string  $value
     * @param  string  $currency_code
     * @return AmountBreakdown
     * @throws UnknownCurrencyException
     */
    public static function of(string $value, string $currency_code = 'USD'): self
    {
        return new self($value, $currency_code);
    }

    /**
     * Get the instance as an array.
     * @return array
     */
    public function toArray(): array
    {
        $breakDownItems = ['item_total', 'shipping', 'handling', 'tax_total', 'insurance', 'shipping_discount', 'discount'];

        $data = [
            'currency_code' => $this->getCurrencyCode(),
            'value' => $this->getValue(),
        ];

        foreach ($breakDownItems as $breakDownItem) {
            if (empty($this->$breakDownItem)) {
                continue;
            }
            $data['breakdown'][$breakDownItem] = [
                'currency_code' => $this->$breakDownItem->getCurrency()->getCurrencyCode(),
                'value' => (string) $this->$breakDownItem->getAmount(),
            ];
        }

        return $data;
    }

    public function hasDiscount(): bool
    {
        return (bool) $this->discount;
    }

    public function getDiscount(): ?Money
    {
        return $this->discount;
    }

    public function setDiscount(Money $discount): AmountBreakdown
    {
        $this->discount = $discount;

        return $this;
    }

    public function getItemTotal(): Money
    {
        return $this->item_total;
    }

    public function setItemTotal(Money $item_total): AmountBreakdown
    {
        $this->item_total = $item_total;

        return $this;
    }

    public function hasShipping(): bool
    {
        return (bool) $this->shipping;
    }

    public function getShipping(): ?Money
    {
        return $this->shipping;
    }

    public function setShipping(Money $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function hasHandling(): bool
    {
        return (bool) $this->handling;
    }

    public function getHandling(): ?Money
    {
        return $this->handling;
    }

    public function setHandling(Money $handling): self
    {
        $this->handling = $handling;

        return $this;
    }

    public function hasTaxTotal(): bool
    {
        return (bool) $this->tax_total;
    }

    public function getTaxTotal(): ?Money
    {
        return $this->tax_total;
    }

    public function setTaxTotal(Money $tax_total): self
    {
        $this->tax_total = $tax_total;

        return $this;
    }

    public function hasInsurance(): bool
    {
        return (bool) $this->insurance;
    }

    public function getInsurance(): ?Money
    {
        return $this->insurance;
    }

    public function setInsurance(Money $insurance): self
    {
        $this->insurance = $insurance;

        return $this;
    }

    public function hasShippingDiscount(): bool
    {
        return (bool) $this->shipping_discount;
    }

    public function setShippingDiscount(Money $shipping_discount): self
    {
        $this->shipping_discount = $shipping_discount;

        return $this;
    }

    public function getShippingDiscount(): ?Money
    {
        return $this->shipping_discount;
    }

}
