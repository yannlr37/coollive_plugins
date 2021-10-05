<?php

namespace Dreamteam\CoollivePlugins\Tests\Geocoding;

use Dreamteam\CoollivePlugins\Geocoding\AddressFields;
use Dreamteam\CoollivePlugins\Geocoding\GeocodingService;
use PHPUnit\Framework\TestCase;

class GeocodingTest extends TestCase
{
    /**
     * @var GeocodingService
     */
    public $service;

    public function setUp(): void
    {
        $config = [
            'url' => 'https://api-adresse.data.gouv.fr/search/?',
            'search_limit' => 10
        ];
        $this->service = new GeocodingService($config);
    }

    public function test_format_request()
    {
        $fields = new AddressFields('Boulevard Charles de Gaulle', '37540', 'Saint Cyr Sur Loire', 1);
        $query = $this->service->formatQueryString($fields);
        $this->assertEquals('1+Boulevard+Charles+de+Gaulle+37540+Saint+Cyr+Sur+Loire&limit=10', $query);
    }

    public function test_format_request_with_accents()
    {
        $fields = new AddressFields('Boulevard Tonnelé', '37000', 'Tours', 17);
        $query = $this->service->formatQueryString($fields);
        $this->assertEquals('17+Boulevard+Tonnelé+37000+Tours&limit=10', $query);
    }

    public function test_geocode_address()
    {
        $fields = new AddressFields('Boulevard Charles de Gaulle', '37540', 'Saint Cyr Sur Loire', 1);
        $latLng = $this->service->geocode($fields);
        $this->assertIsFloat($latLng['latitude']);
        $this->assertIsFloat($latLng['longitude']);
        $this->assertTrue(round($latLng['latitude'], 1) > 0.0);
        $this->assertTrue(round($latLng['longitude'], 1) > 0.0);
    }
}