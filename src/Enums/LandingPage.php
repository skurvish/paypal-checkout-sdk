<?php

/**
 * Enums for Landing Page
 */

namespace PayPal\Checkout\Enums;

enum LandingPage: string {
	case LOGIN = 'LOGIN';
	case BILLING = 'BILLING';
	case NO_PREFERENCE = 'NO_PREFERENCE';
}
