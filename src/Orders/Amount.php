<?php

namespace PayPal\Checkout\Orders;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use PayPal\Checkout\Concerns\CastsToJson;
use PayPal\Checkout\Contracts\Amount as AmountContract;
use PayPal\Checkout\Orders\AmountBreakdown;

/**
 * https://developer.paypal.com/docs/api/orders/v2/#definition-Amount_breakdown.
 */
class Amount implements AmountContract
{
    use CastsToJson;

    /**
     * The three-character ISO-4217 currency code that identifies the currency.
     *
     * @var Money
     */
    protected Money $money;

    /**
     *  The breakdown of the amount. Breakdown provides details such as total item amount, total tax amount, shipping, handling, insurance, and discounts, if any.
    **/
    protected ?AmountBreakdown $breakdown = null;

    /**
     * create a new amount instance.
     * @param  string  $value
     * @param  string  $currency_code
     * @throws UnknownCurrencyException
     */
    public function __construct(string $value, string $currency_code = 'USD')
    {
        $this->money = Money::of($value, $currency_code);
    }

    /**
     * @param  string  $value
     * @param  string  $currency_code
     * @return Amount
     * @throws UnknownCurrencyException
     */
    public static function of(string $value, string $currency_code = 'USD'): Amount
    {
        return new self($value, $currency_code);
    }

    /**
     * convert amount to an array.
     * @return array
     */
    public function toArray(): array
    {
        $return = [
            'currency_code' => $this->getCurrencyCode(),
            'value' => $this->getValue(),
        ];

        if ($this->breakdown) {
            $return['breakdown'] = $this->breakdown->toArray();
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->money->getCurrency()->getCurrencyCode();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this->money->getAmount();
    }
    
    public function setAmountBreakdown(AmountBreakdown $breakdown): self
    {
        $this->breakdown = $breakdown;

        return $this;
    }

    public function getAmountBreakdown(): AmountBreakdown
    {
        return $this->breakdown;
    }
}
