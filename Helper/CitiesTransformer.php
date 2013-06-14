<?php

namespace AntiMattr\GoogleBundle\Helper;

use \stdClass;

class CitiesTransformer
{
    public function transform(stdClass $data)
    {
        $markers = array();
        if (!empty($data->results)) {
            foreach ($data->results as $result) {
                if (isset($result->geometry)
                    && isset($result->geometry->location)
                ) {
                    $location = $result->geometry->location;
                    $markers[] = array(
                        'lat'   => $location->lat,
                        'lng'   => $location->lng,
                    );
                }
            }
        }

        return $markers;
    }
}
