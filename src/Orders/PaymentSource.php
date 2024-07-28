<?php

namespace PayPal\Checkout\Orders;

use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;
use PayPal\Checkout\Orders\PayPalPaymentSource;

/**
 *
 */
class PaymentSource implements Arrayable, Jsonable
{

    use CastsToJson;
    
    /**
     *  Indicates that PayPal Wallet is the payment source.
     *
     * @var object|null
     */
    protected ?PayPalPaymentSource $paypal = null;

    public function getPayPalSource(): ?PayPalPaymentSource
    {
        return $this->paypal;
    }

    public function setPayPalSource(PayPalPaymentSource $paypal): self
    {
        $this->paypal = $paypal;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return !empty($this->paypal) ? ['paypal' => ['experience_context' => $this->paypal->toArray()]] : [];
    }

    /**
     * Validate the payment source.
     * @return an array of errors which may be empty
     */
    public function validate(): ?array
    {
        $errors = [];
        if (empty($this->paypal)) {
            $errors[] = "PayPal payment source is required";
        } else {
            $errors = array_merge($errors, $this->paypal->validate());
       }

        return array_filter($errors);
    }
}