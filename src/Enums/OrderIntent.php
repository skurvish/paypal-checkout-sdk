<?php

/**
 * Enums for Order Intent
 */

namespace PayPal\Checkout\Enums;

enum OrderIntent: string {
	case CAPTURE = 'CAPTURE';
	case AUTHORIZE = 'AUTHORIZE';
}
