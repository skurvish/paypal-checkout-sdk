<?php

/**
 * Enums for Item Category
 */

namespace PayPal\Checkout\Enums;

enum ItemCategory: string {
	case DIGITAL_GOODS = 'DIGITAL_GOODS';
	case PHYSICAL_GOODS = 'PHYSICAL_GOODS';
}
