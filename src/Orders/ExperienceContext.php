<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Enums\LandingPage;
use PayPal\Checkout\Enums\PaymentMethod;
use PayPal\Checkout\Enums\ShippingPreference;
use PayPal\Checkout\Enums\UserAction;
use Paypal\Checkout\Exceptions\InvalidPaymentMethodPreferenceException;
use PayPal\Checkout\Orders\ApplicationContext;

class ExperienceContext extends ApplicationContext implements Arrayable, Jsonable
{

    use CastsToJson;

    /**
     *  The merchant-preferred payment methods.
     *
     * @var PaymentMethod|null
     */
    protected ?PaymentMethod $payment_method_preference = PaymentMethod::IMMEDIATE_PAYMENT_REQUIRED;


    public function __construct(
        ?string $brand_name = null,
        string $locale = 'en-US',
        LandingPage $landing_page = LandingPage::NO_PREFERENCE,
        ShippingPreference $shipping_preference = ShippingPreference::NO_SHIPPING,
        ?string $return_url = null,
        ?string $cancel_url = null,
        ?PaymentMethod $payment_method_preference = PaymentMethod::IMMEDIATE_PAYMENT_REQUIRED   // new argument
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
        LandingPage $landing_page = LandingPage::NO_PREFERENCE,
        ShippingPreference $shipping_preference = ShippingPreference::NO_SHIPPING,
        ?string $return_url = null,
        ?string $cancel_url = null,
        ?PaymentMethod $payment_method_preference = PaymentMethod::IMMEDIATE_PAYMENT_REQUIRED   // new argument
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
        $data = array_filter(parent::toArray());
        $paymentMethodPreference = $this->getPaymentMethodPreference();

        if ($paymentMethodPreference) {
            $data['payment_method_preference'] = $paymentMethodPreference->value;
        }

        return $data;
    }

    public function getPaymentMethodPreference(): PaymentMethod
    {
        return $this->payment_method_preference;
    }

    public function setPaymentMethodPreference(PaymentMethod $payment_method_preference): self
    {
        if ($payment_method_preference instanceof PaymentMethod === false) {
            throw new InvalidPaymentMethodPreferenceException();
        }
        $this->payment_method_preference = $payment_method_preference;

        return $this;
    }

}