<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Concerns\HasCollection;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Exceptions\MultiCurrencyOrderException;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-purchase_unit.
 */
class PurchaseUnit implements Arrayable, Jsonable
{
    use CastsToJson;
    use HasCollection;

    /**
     * The total order Amount with an optional breakdown that provides details,
     * such as the total item Amount, total tax Amount, shipping, handling, insurance,
     * and discounts, if any.
     *
     * @var Amount
     */
    protected Amount $amount;

    /**
     * An array of items that the customer purchases from the merchant.
     *
     * @var Item[]
     */
    protected array $items = [];

    protected ShippingDetail $shipping;
    /**
     * Create a new collection.
     */
    public function __construct(Amount $amount)
    {
        $this->amount = $amount;
    }

    /**
     *  push a new item into items array.
     * @param  Item[]  $items
     * @return PurchaseUnit
     * @throws MultiCurrencyOrderException
     */
    public function addItems(array $items): self
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }

        return $this;
    }

    /**
     *  push a new item into items array.
     * @param  Item  $item
     * @return PurchaseUnit
     * @throws MultiCurrencyOrderException
     */
    public function addItem(Item $item): self
    {
        if ($item->getAmount()->getCurrencyCode() != $this->amount->getCurrencyCode()) {
            throw new MultiCurrencyOrderException();
        }

        $this->items[] = $item;

        return $this;
    }

    /**
     * return's purchase unit items.
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }


    public function setShipping(ShippingDetail $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getShipping(): ShippingDetail
    {
        return $this->shipping;
    }

    /**
     * return's the purchase unit amount breakdown.
     * @return AmountBreakdown
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getTotalItemsAmount(): Amount 
    {
        $totalAmount = 0;
        foreach($this->items as $item) {
            $totalAmount += $item->getAmount()->getValue() * $item->getQuantity();
        }

        return new Amount($totalAmount, $this->amount->getCurrencyCode());
    }

    /**
     * convert a purchase unit instance to array.
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'amount' => $this->amount->toArray(),
            'items' => array_map(
                fn(Item $item) => $item->toArray(),
                $this->items
            )
        ];
        if (!empty($this->shipping)) {
            $data['shipping'] = $this->shipping->toArray();
        }

        return $data;
    }
    /**
     * Validate the purchase unit.
     * @return an array of errors which may be empty
     */
    public function validate(): ?array
    {
        $errors = [];
        $amount = $this->getAmount();
        if (empty($amount)) {
            $errors[] = "Missing amount for purchase unit.";
        } else {
            $errors = array_merge($errors, $amount->validate());
        }
        if (!empty($this->shipping)) {
            $errors = array_merge($errors, $this->shipping->validate());
        }
        if (empty($this->items)) {
            $errors[] = "Purchase unit must have at least one item";
        } else {
            foreach ($this->items as $item) {
                $errors = array_merge($errors, $item->validate());
            }
        }

        $totalItemsAmount = $this->getTotalItemsAmount();
        if ($amount && $totalItemsAmount) {
            if ($amount->getValue() != $totalItemsAmount->getValue()) {
                $errors[] = "Total Items Amount of " . $totalItemsAmount->getValue() . " does not equal the purchaseUnit amount of " . $amount->getValue();
            }
        }

        return array_filter($errors);
    }
}
