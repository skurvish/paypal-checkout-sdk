<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class InvalidShippingTypeException extends RuntimeException
{
    /** @var string */
    protected $message = 'Shipping Details must have a valid Type.';
}
