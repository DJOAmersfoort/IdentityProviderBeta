<?php
declare(strict_types=1);

namespace IdpBundle\Entity;

use IdpBundle\Entity\Address;

/**
 * An address, inserted into other Entities
 *
 * @author Roelof Roos <github@roelof.io>
 */
class Address
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $country;

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress(?string $address) : self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() : ?string
    {
        return $this->address;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     * @return User
     */
    public function setZipCode(?string $zipCode) : self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode() : ?string
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity(?string $city) : self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity() : ?string
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry(?string $country) : self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry() : ?string
    {
        return $this->country;
    }

    /**
     * Returns a string version of this address, in the below format.
     *
     * ```
     * <address>
     * <zip-code> <city>
     * <country>
     * ```
     *
     * @return string
     */
    public function __toString() : string
    {
        return vsprintf("%s\n%s %s\n%s", [
            $this->getAddress(),
            $this->getZipCode(),
            $this->getCity(),
            strtoupper($this->getCountry() ?? 'the netherlands')
        ]);
    }
}
