<?php

/**
 * Enums for Shipping Type
 */

namespace PayPal\Checkout\Enums;

enum ShippingType: string {
	case SHIPPING = 'SHIPPING';
	case PICKUP_IN_PERSON = 'PICKUP_IN_PERSON';
	case PICKUP_IN_STORE = 'PICKUP_IN_STORE';
	case PICKUP_FROM_PERSON = 'PICKUP_FROM_PERSON';
}
