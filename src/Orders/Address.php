<?php

namespace Fabrik\Plugin\Fabrik_form\Paypal\Helpers\Checkout\Orders;

/**
 * Default address implementation.
 */
class Address 
{
    /**
     * The two-letter country code.
     *
     * @var string
     */
    protected string $country_code;

    /**
     * The top-level administrative subdivision of the country.
     *
     * @var string
     */
    protected string $admin_area_1;

    /**
     * The second-level administrative subdivision of the country.
     *
     * @var string
     */
    protected string $admin_area_2;

    /**
     * The locality (i.e. city).
     *
     * @var string
     */
    protected string $locality;

    /**
     * The dependent locality (i.e. neighbourhood).
     *
     * @var string
     */
    protected string $dependentLocality;

    /**
     * The postal code.
     *
     * @var string
     */
    protected string $postal_code;

    /**
     * The sorting code.
     *
     * @var string
     */
    protected string $sortingCode;

    /**
     * The first line of the address block.
     *
     * @var string
     */
    protected string $address_line_1;

    /**
     * The second line of the address block.
     *
     * @var string
     */
    protected string $address_line_2;

    /**
     * The third line of the address block.
     *
     * @var string
     */


    /* Map our friendly properties to PayPal format */
    protected static $propertyMap = [
        'CountryCode' => 'country_code',
        'AdminArea1' => 'admin_area_1',
        'AdminArea2' => 'admin_area_2',
        'PostalCode' => 'postal_code',
        'AddressLine1' => 'address_line_1',
        'AddressLine2' => 'address_line_2',
    ];
    /**
     * Creates an Address instance.
     *
     * @param string $country_code          The two-letter country code.
     * @param string $admin_area_1           The administrative area.
     * @param string $admin_area_2           The administrative area.
     * @param string $postal_code           The postal code.
     * @param string $address_line_1        The first line of the address block.
     * @param string $address_line_2        The second line of the address block.
     */
    public function __construct( $params = []) 
    {
        foreach ($params as $key => $value) {
            if (array_key_exists($key, self::$propertyMap)) {
                $this->{self::$propertyMap[$key]} = $value;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryCode($country_code) 
    {
        $this->country_code = $country_code;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminArea1(): string
    {
        return $this->admin_area_1;
    }

    /**
     * {@inheritdoc}
     */
    public function setAdminArea1($admin_area_1)
    {
        $this->admin_area_1 = $admin_area_1;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminArea2(): string
    {
        return $this->admin_area_2;
    }

    /**
     * {@inheritdoc}
     */
    public function setAdminArea2($admin_area_2)
    {
        $this->admin_area_2 = $admin_area_2;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostalCode(): string
    {
        return $this->postal_code;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressLine1(): string
    {
        return $this->address_line_1;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddressLine1($address_line_1)
    {
        $this->address_line_1 = $address_line_1;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressLine2(): string
    {
        return $this->address_line_2;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddressLine2($address_line_2)
    {
        $this->address_line_2 = $address_line_2;
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this));
    }
}