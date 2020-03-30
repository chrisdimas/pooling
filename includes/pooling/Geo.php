<?php
namespace PLGLib;

/**
 * Geo
 */
class Geo
{
    public static function random_float($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }

    public static function distanceGeoPoints($lat1, $lng1, $lat2, $lng2)
    {

        $earthRadius = 3958.75;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLng / 2) * sin($dLng / 2);
        $c    = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $dist = $earthRadius * $c;

        // from miles
        $meterConversion  = 1609.344;
        $geopointDistance = $dist * $meterConversion;

        return $geopointDistance;
    }

    // Create random markers
    public static function random_markers($lat_min, $lat_max, $lng_min, $lng_max, $pow_of_ten = 3)
    {
        $longlats = [];
        for ($i = 0; $i < 10 ** $pow_of_ten; $i++) {
            $longlats[] = ['lng' => PLGLib\Geo::random_float($lng_min, $lng_max), 'lat' => PLGLib\Geo::random_float($lat_min, $lat_max)];
        }
        return $longlats;
    }
}
