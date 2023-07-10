<?php


namespace App\Classes;
use App\Models\City;
use Location\Coordinate;

class Polygon
{
    public static function arrayToWKT($array = [])
    {
        $wktString = 'POLYGON((';
        $wktString .= implode(",",$array);
        return $wktString.'))';
    }

    public static function WKTToArray($wkt = null)
    {
        if(!$wkt){
           return [];
        }
        $wktString = str_replace(['MULTI','POLYGON','MULTIPOLYGON','POINT', '(', ')'], ['','','','', '', ''], $wkt);
        return explode(',', trim($wktString));
    }


    public static function getCityByCoordinates($latitude, $longitude)
    {
        $cities = City::where('active', 1)->get();
        foreach ($cities as $city) {
            if (!empty($city->polygon) && self::checkPointInPolygon(array('latitude' => $latitude, 'longitude' => $longitude), json_decode($city->polygon))) {
                return $city;
            }
        }
        
        return false;
    }

    protected static function checkPointInPolygon($pointArray, $polygonArray)
    {
        $geofence = new \Location\Polygon();
        foreach ($polygonArray as $polygonPoint) {
            $convertToArray = explode(' ', trim($polygonPoint));
            if(count($convertToArray) == 2){
                $geofence->addPoint(new Coordinate((float)$convertToArray[1], (float)$convertToArray[0]));
            }
        }
        
        return $geofence->contains(new Coordinate((float)$pointArray['latitude'], (float)$pointArray['longitude']));
    }
}
