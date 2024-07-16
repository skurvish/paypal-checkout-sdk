<?php

namespace Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Orders;

use Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Orders\ExperienceContext;

use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Arrayable;
use PayPal\Checkout\Contracts\Jsonable;

class PayPalPaymentSource implements Arrayable, Jsonable
{

    use CastsToJson;
    
    /**
     *  Customizes the payer experience during the approval process for payment with PayPal.
     *
     * @var ExperienceContext|null
     */
    protected ?ExperienceContext $experience_context = null;

    /**
     * creates a new PayPal PaymentSource instance.
     */
    public function __construct(ExperienceContext $experience_context = NULL)
    {
        $this->setExperienceContext($experience_context);
    }

    public function getExperienceContext(): ?ExperienceContext
    {
        return $this->experience_context;
    }

    public function setExperienceContext(ExperienceContext $experience_context): self
    {
        $this->experience_context = $experience_context;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return !empty($this->experience_context) ? $this->experience_context->toArray() : [];
    }

}    
