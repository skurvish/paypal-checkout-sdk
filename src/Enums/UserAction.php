<?php

/**
 * Enums for User Action
 */

namespace PayPal\Checkout\Enums;

enum UserAction: string {
	case ACTION_CONTINUE = 'ACTION_CONTINUE';
	case ACTION_PAY_NOW = 'ACTION_PAY_NOW';
}
