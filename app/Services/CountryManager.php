<?php
namespace PanteraFox\Services;

use PanteraFox\Country;

class CountryManager
{

    /** @var Country */
    private $defaultCountry;

    public function __construct()
    {
        $this->defaultCountry = Country::where('name', 'Ukraine')->first();
    }

    /**
     * @param string $countryName
     * @return string
     */
    public function getCountryIdByName($countryName)
    {
        $existsCountry = Country::where('name', $countryName)->first();

        if($existsCountry)
        {
            return $existsCountry->id;
        }
        else
        {
            return $this->defaultCountry->id;
        }
    }

    /**
     * @param string $countryId
     * @return string
     */
    public function getCountryNameById($countryId)
    {
        return ($this->getCountryById($countryId))->name;
    }

    /**
     * @param string $countryId
     * @return string
     */
    public function getCountryShortById($countryId)
    {
        return ($this->getCountryById($countryId))->short;
    }

    /**
     * @param IpManager $ipManager
     * @return string
     */
    public function getCountryShortByIp(IpManager $ipManager)
    {
        $ipLocation = $ipManager->getIpLocation();
        if (isset($ipLocation['country']['code']))
        {
            return $ipLocation['country']['code'];
        }

        return $this->defaultCountry->short;
    }

    /**
     * @param string $countryId
     * @return Country
     */
    private function getCountryById($countryId)
    {
        $existsCountry = Country::find($countryId);

        if($existsCountry)
        {
            return $existsCountry;
        }
        else
        {
            return $this->defaultCountry;
        }
    }
}