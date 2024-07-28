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

class ExperienceContext implements Arrayable, Jsonable
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

        $this->brand_name = $brand_name;
        $this->locale = $locale;
        $this->landing_page = $landing_page;
        $this->shipping_preference = $shipping_preference;
        $this->return_url = $return_url;
        $this->cancel_url = $cancel_url;
        $this->payment_method_preference = $payment_method_preference;
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
        $arrayable = [
            'brand_name' => $this->getBrandName() ?? null,
            'locale' => $this->getLocale() ?? null,
            'shipping_preference' => $this->getShippingPreference()?->value ?? null,
            'landing_page' => $this->getLandingPage()?->value ?? null,
            'user_action' => $this->getUserAction()?->value ?? null,
            'return_url' => $this->getReturnUrl() ?? null,
            'cancel_url' => $this->getCancelUrl() ?? null,
            'payment_method_preference' => $this->getPaymentMethodPreference() ?? null,
        ];

        return array_filter(
            $arrayable,
            function ($item) {
                return null !== $item;
            }
        );
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(string $brand_name): self
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getShippingPreference(): ShippingPreference
    {
        return $this->shipping_preference;
    }

    public function setShippingPreference(ShippingPreference $shipping_preference): self
    {
        if ($shipping_preference instanceof ShippingPreference === false) {
            throw new InvalidShippingPreferenceException();
        }

        $this->shipping_preference = $shipping_preference;

        return $this;
    }

    public function getLandingPage(): LandingPage
    {
        return $this->landing_page;
    }

    public function setLandingPage(LandingPage $landing_page): self
    {
        if ($landing_page instanceof LandingPage === false) {
            throw new InvalidLandingPageException();
        }

        $this->landing_page = $landing_page;

        return $this;
    }

    public function getUserAction(): UserAction
    {
        return $this->user_action;
    }

    public function setUserAction(UserAction $user_action): self
    {
        if ($user_action instanceof UserAction === false) {
            throw new InvalidUserActionException();
        }
        $this->user_action = $user_action;

        return $this;
    }

    public function getReturnUrl(): ?string
    {
        return $this->return_url;
    }

    public function setReturnUrl(string $return_url): self
    {
        $this->return_url = $return_url;

        return $this;
    }

    public function getCancelUrl(): ?string
    {
        return $this->cancel_url;
    }

    public function setCancelUrl(string $cancel_url): self
    {
        $this->cancel_url = $cancel_url;

        return $this;
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

    /**
     * Validate the experience context.
     * @return an array of errors which may be empty
     */
    public function validate(): ?array
    {
        $errors = [];
        if (empty($this->user_action) || $this->user_action instanceof UserAction === false) {
            $errors[] = "Missing or invalid user action in Experience Context";
        }
        if (empty($this->payment_method_preference) || $this->payment_method_preference instanceof PaymentMethod === false) {
            $errors[] = "Missing or invalid payment method preference in Experience Context";
        }
        if (empty($this->landing_page) || $this->landing_page instanceof LandingPage === false) {
            $errors[] = "Missing or invalid landing page in Experience Context";
        }
        if (empty($this->shipping_preference) || $this->shipping_preference instanceof ShippingPreference === false) {
            $errors[] = "Missing or invalid shipping preference in Experience Context";
        }
        if (empty($this->return_url)) {
            $errors[] = "Missing return URL in Experience Context";
        }

        return array_filter($errors);
    }
}