<?php

/**
 * Enums for Payment Method
 */

namespace PayPal\Checkout\Enums;

enum PaymentMethod: string {
	case UNRESTRICTED = 'UNRESTRICTED';
	case IMMEDIATE_PAYMENT_REQUIRED = 'IMMEDIATE_PAYMENT_REQUIRED';
}
