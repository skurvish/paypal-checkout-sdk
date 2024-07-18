<?php

/**
 * Enums for Shipping Preference
 */

namespace PayPal\Checkout\Enums;

enum ShippingPreference: string {
	case GET_FROM_FILE = 'GET_FROM_FILE';
	case NO_SHIPPING = 'NO_SHIPPING';
	case SET_PROVIDED_ADDRESS = 'SET_PROVIDED_ADDRESS';
}
