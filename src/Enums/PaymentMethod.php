<?php

/**
 * Enums for Payment Method
 */

namespace PayPal\Checkout\Enums;

enum PaymentMethod: string {
	case METHOD_UNRESTRICTED = 'METHOD_UNRESTRICTED';
	case METHOD_IMMEDIATE_PAYMENT_REQUIRED = 'METHOD_IMMEDIATE_PAYMENT_REQUIRED';
}
