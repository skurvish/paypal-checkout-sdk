<?php

namespace Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Orders;

use Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Orders\PayPalPaymentSource;

use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;

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
}