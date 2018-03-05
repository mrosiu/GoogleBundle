<?php

namespace AntiMattr\GoogleBundle\Service;

class SearchCity
{
    const URL   = '//maps.google.com/maps/api/staticmap?';

    protected $curlManager;

    public function __construct(CurlManager $curlManager)
    {
        $this->curlManager  = $curlManager;
    }

    /**
     * @param string $query
     *
     * @return \stdClass
     */
    public function getCityData($query)
    {
        $query = array(
            'address' => trim($query)
        );

        return json_decode(
            $this->curlManager->get(
                self::URL,
                $query
            )
        );
    }
}
