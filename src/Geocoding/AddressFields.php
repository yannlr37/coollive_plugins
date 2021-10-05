<?php

namespace Dreamteam\CoollivePlugins\Geocoding;

class AddressFields
{
    private $number = 0;
    private $complement = '';
    private $street = '';
    private $postal_code = '';
    private $city = '';

    public function __construct(string $street, string $postal_code, string $city, int $number = 0, string $complement = '')
    {
        $this->number = $number;
        $this->complement = $complement;
        $this->street = $street;
        $this->postal_code = $postal_code;
        $this->city = $city;
    }

    /**
     * @return int
     */
    public function number(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function complement(): string
    {
        return $this->complement;
    }

    /**
     * @param string $complement
     */
    public function setComplement(string $complement): void
    {
        $this->complement = $complement;
    }

    /**
     * @return string
     */
    public function street(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function postalCode(): string
    {
        return $this->postal_code;
    }

    /**
     * @param string $postal_code
     */
    public function setPostalCode(string $postal_code): void
    {
        $this->postal_code = $postal_code;
    }

    /**
     * @return string
     */
    public function city(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return void
     */
    public function sanitizeFields()
    {
    }
}