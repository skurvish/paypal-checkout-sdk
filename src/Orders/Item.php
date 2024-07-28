<?php

namespace PayPal\Checkout\Orders;

use Brick\Money\Exception\UnknownCurrencyException;
//use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Amount as AmountContract;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Enums\ItemCategory;
use PayPal\Checkout\Exceptions\InvalidItemCategoryException;


/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-item.
 */
class Item implements Arrayable//, Jsonable
{
//    use CastsToJson;

    /**
     * The item name or title.
     *
     * @var string
     */
    protected string $name;

    /**
     * The item price or rate per unit.
     * If you specify unit_amount, purchase_units[].amount.breakdown.item_total is required.
     * Must equal unit_amount * quantity for all items.
     *
     * @var AmountContract
     */
    protected AmountContract $unit_amount;

    /**
     * The item tax for each unit.
     * If tax is specified, purchase_units[].amount.breakdown.tax_total is required.
     * Must equal tax * quantity for all items.
     *
     * @var AmountContract|null
     */
    protected ?AmountContract $tax = null;

    /**
     * The item quantity. Must be a whole number.
     *
     * @var int
     */
    protected int $quantity;

    /**
     * The stock keeping unit (SKU) for the item.
     *
     * @var string
     */
    protected string $sku;

    /**
     * The detailed item description.
     *
     * @var string
     */
    protected string $description = '';

    /**
     * The item category type. The possible values are:.The item category type. The possible values are:
     *     - DIGITAL_GOODS. Goods that are stored, delivered, and used in their electronic format.
     *     - PHYSICAL_GOODS. A tangible item that can be shipped with proof of delivery.
     *
     * @var string
     */
    protected ItemCategory $category = ItemCategory::DIGITAL_GOODS;

    /**
     * create a new item instance.
     */
    public function __construct(string $name, AmountContract $amount, int $quantity = 1)
    {
        $this->name = $name;
        $this->unit_amount = $amount;
        $this->quantity = $quantity;
        $this->sku = uniqid();
    }

    /**
     * create a new item instance.
     * @throws UnknownCurrencyException
     */
    public static function create(string $name, string $value, string $currency_code = 'USD', int $quantity = 1): Item
    {
        $amount = Amount::of($value, $currency_code);
        return new self($name, $amount, $quantity);
    }

    /**
     * set's item amount.
     */
    public function setUnitAmount(AmountContract $unit_amount): self
    {
        $this->unit_amount = $unit_amount;

        return $this;
    }

    /**
     * return's item sku.
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * set's item sku.
     */
    public function setSku(string $sku) 
    {
        $this->sku = $sku;
    }

    /**
     * return's item sku.
     */
    public function getAmount(): AmountContract
    {
        return $this->unit_amount;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->getName(),
            'unit_amount' => $this->unit_amount->toArray(),
            'quantity' => $this->getQuantity()
        ];
        $optionalItems = ['description' => 'string', 'sku' => 'string', 'url' => 'string', 'image_url' => 'string',
                            'category' => 'enum',
                            'tax' => 'money',
                            'upc' => 'upc'
        ];
        foreach ($optionalItems as $optionalItem => $type) {
            if (empty($this->$optionalItem)) {
                continue;
            }
            switch ($type) {
                case 'string':
                    $data[$optionalItem] = $this->$optionalItem;
                    break;
                case 'enum':
                    $data[$optionalItem] = $this->$optionalItem->value;
                    break;
                case 'money':
                    $data[$optionalItem] = $this->$optionalItem->getAmount();
                    break;
                case 'upc':
                // TBD
            }
        }
        return $data;
    }

    /**
     * return's item name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * set's item name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * return's item quantity.
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * set's item quantity.
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * return's item description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * set's item description.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * return's item category.
     */
    public function getCategory(): ?ItemCategory
    {
        return $this->category;
    }

    /**
     * set's item category.
     */
    public function setCategory(ItemCategory $category): self
    {
        if ($category instanceof ItemCategory === false) {
            throw new InvalidItemCategoryException();
        }
        $this->category = $category;

        return $this;
    }

    /**
     * Validate the item.
     * @return an array of errors which may be empty
     */
    public function validate(): ?array
    {
        $errors = [];
        if (empty($this->getAmount())) {
            $errors[] = "Item requires an amount";
        }
        if (empty($this->getName())) {
            $errors[] = "Item requires a name";
        }
        if (empty($this->getQuantity())) {
            $errors[] = "Item requires a quantity";
        }

        return array_filter($errors);
    }
}
