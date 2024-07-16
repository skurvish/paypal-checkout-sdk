<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class InvalidPaymentSourceException extends RuntimeException
{
    /** @var string */
    protected $message = 'Orders must have at least one valid Payment Source.';
}
