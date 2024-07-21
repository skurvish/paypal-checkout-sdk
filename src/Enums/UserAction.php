<?php

/**
 * Enums for User Action
 */

namespace PayPal\Checkout\Enums;

enum UserAction: string {
	case CONTINUE = 'CONTINUE';
	case PAY_NOW = 'PAY_NOW';
}
