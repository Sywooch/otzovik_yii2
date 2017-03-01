<?php
namespace app\models;

class GeoHelper
{
    public $address;
    public $coords;

    function __construct($address)
    {
        $this->address = $address;
    }

    public function getCoords()
    {
        $helper = json_decode(file_get_contents("https://geocode-maps.yandex.ru/1.x/?format=json&geocode={$this->address}"));
        $ar = $helper->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        $pos_r = explode(' ', $ar);
        $this->coords = json_encode(['lat' => $pos_r[0], 'lon' => $pos_r[1]]);

        return $this->coords;
    }

    static function encodeCoords($coords)
    {
        return json_encode(['lat' => $coords['lat'], 'lon' => $coords['lon']]);
    }
}