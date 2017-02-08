<?php

namespace AppBundle\ValueObjects;

class ContactDetails
{

    private $emailadres;
    private $phoneNumber;

    private $street;
    private $houseNumber;
    private $houseNumberAddition;
    private $postalCode;
    private $city;
    private $country;

    /**
     * Address constructor.
     * @param $street
     * @param $number
     * @param $numberAddition
     * @param $postalCode
     * @param $city
     * @param $country
     */
    public function __construct($street, $houseNumber, $houseNumberAddition, $postalCode, $city, $country, $emailadres, $phoneNumber)
    {
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->houseNumberAddition = $houseNumberAddition;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
        $this->emailadres = $emailadres;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return mixed
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @return mixed
     */
    public function getHouseNumberAddition()
    {
        return $this->houseNumberAddition;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

}