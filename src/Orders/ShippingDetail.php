<?php

/** 
 * The name and address of the person to whom to ship the items.
 **/
namespace Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Orders;

use Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Orders\Address;
use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;

const SHIPPING = 'SHIPPING';
const PICKUP_IN_PERSON = 'PICKUP_IN_PERSON';
const PICKUP_IN_STORE = 'PICKUP_IN_STORE';
const PICKUP_FROM_PERSON = 'PICKUP_FROM_PERSON';

class ShippingDetail implements Arrayable, Jsonable
{

    use CastsToJson;
    /**
     * The payment source definition.
     * https://developer.paypal.com/docs/api/orders/v2/#orders_create
     *
     * @var id
     */

    /**
     * A classification for the method of purchase fulfillment (e.g shipping, in-store pickup, etc). 
     * Either type or options may be present, but not both.
     *
     * @var string required
     */
    protected string $type = SHIPPING;

    /**
     * An array of shipping options that the payee or merchant offers to the payer to ship or pick up their items.
     * @var array|null
     */
    // CURRENTLY NOT IMPLIMENTED
    protected array $options;

    /**
     * The name of the person to whom to ship the items. Supports only the full_name property.
     *
     * @var object 
     */
    protected array $name = ['full_name' => ''];

    /**
     * The address of the person to whom to ship the items.
     *
     * @var Address|null 
     */
    protected Address $address;

    /**
     * creates a new order instance.
     */
    public function __construct(string $type = null)
    {
        if (!empty($type)) {
            $this->type = $type;
        }
    }

    public function setShippingType(string $type): self
    {
        $validOptions = [SHIPPING, PICKUP_IN_PERSON, PICKUP_IN_STORE, PICKUP_FROM_PERSON];
        if (!in_array($type, $validOptions)) {
            throw new InvalidShippingTypeException();
        }
        $this->type = $type;

        return $this;
    }

    public function getShippingType(): string
    {
        return $this->type;
    }

    public function setShippingName(string $name): self
    {
        $this->name['full_name'] = $name;

        return $this;
    }

    public function getShippingName(): string
    {
        return $this->name['full_name'];
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }
    /**
     * Get the instance as an array.
     * @return array
     */
    public function toArray(): array
    {

        return [
            'type' => $this->type,
            'name' => $this->name,
            'address' => $this->address->toArray(),
        ];
    }
}