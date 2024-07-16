<?php

namespace PayPal\Checkout\Exceptions;

use RuntimeException;

class MissingOrderIdException extends RuntimeException
{
    /** @var string */
    protected $message = 'OrderDetail requires an order ID on construction';
}
