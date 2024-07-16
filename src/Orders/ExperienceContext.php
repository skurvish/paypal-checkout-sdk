<?php

namespace Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Orders;

use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Orders\ApplicationContext;

use Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Exceptions\InvalidPaymentMethodPreferenceException;

// landing_page
const LOGIN = 'LOGIN';
const BILLING = 'BILLING';
const NO_PREFERENCE = 'NO_PREFERENCE';

// shipping_preference
const GET_FROM_FILE = 'GET_FROM_FILE';
const NO_SHIPPING = 'NO_SHIPPING';
const SET_PROVIDED_ADDRESS = 'SET_PROVIDED_ADDRESS';

// user_action
const ACTION_CONTINUE = 'CONTINUE';
const ACTION_PAY_NOW = 'PAY_NOW';

// payment_method
const METHOD_UNRESTRICTED = 'UNRESTRICTED';
const METHOD_IMMEDIATE_PAYMENT_REQUIRED = 'IMMEDIATE_PAYMENT_REQUIRED';

class ExperienceContext extends ApplicationContext implements Arrayable, Jsonable
{

    use CastsToJson;

    /**
     *  The merchant-preferred payment methods.
     *
     * @var string|null
     */
    protected ?string $payment_method_preference = METHOD_IMMEDIATE_PAYMENT_REQUIRED;


    public function __construct(
        ?string $brand_name = null,
        string $locale = 'en-US',
        string $landing_page = NO_PREFERENCE,
        string $shipping_preference = NO_SHIPPING,
        ?string $return_url = null,
        ?string $cancel_url = null,
        ?string $payment_method_preference = METHOD_IMMEDIATE_PAYMENT_REQUIRED   // new argument
        ) {
        $args = func_get_args(); // Get all passed arguments
        
        // Get the number of parameters the parent constructor accepts
        $reflect = new \ReflectionMethod('\PayPal\Checkout\Orders\ApplicationContext', '__construct');
        $numParentArgs = $reflect->getNumberOfParameters();

        if (count($args) >= $numParentArgs) {
            $this->payment_method_preference = array_pop($args);
        }

        // Call the parent constructor with the remaining arguments
        parent::__construct(...$args);
    }

    public static function create(
        ?string $brand_name = null,
        string $locale = 'en-US',
        string $landing_page = NO_PREFERENCE,
        string $shipping_preference = NO_SHIPPING,
        ?string $return_url = null,
        ?string $cancel_url = null,
        ?string $payment_method_preference = METHOD_IMMEDIATE_PAYMENT_REQUIRED   // new argument
    ): ExperienceContext {
        return new self(
            $brand_name,
            $locale,
            $landing_page,
            $shipping_preference,
            $return_url,
            $cancel_url,
            $payment_method_preference
        );
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $arrayable = parent::toArray() + ['payment_method_preference' => $this->getPaymentMethodPreference()];

        return array_filter(
            $arrayable,
            function ($item) {
                return null !== $item;
            }
        );
    }

    public function getPaymentMethodPreference(): string
    {
        return $this->payment_method_preference;
    }

    public function setPaymentMethodPreference(string $payment_method_preference): self
    {
        $validOptions = [METHOD_UNRESTRICTED, METHOD_IMMEDIATE_PAYMENT_REQUIRED];
        if (!in_array($payment_method_preference, $validOptions)) {
            throw new InvalidPaymentMethodPreferenceException();
        }
        $this->payment_method_preference = $payment_method_preference;

        return $this;
    }

}