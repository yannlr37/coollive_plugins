<?php

namespace Dreamteam\CoollivePlugins\Geocoding;

use Dreamteam\CoollivePlugins\Geocoding\Exceptions\NoResultException;

class GeocodingService
{
    /**
     * @var array
     */
    private $config = [
        'url' => '',
        'search_limit' => 10
    ];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            if (isset($this->config[$key])) {
                $this->config[$key] = $value;
            }
        }
    }

    /**
     * @param AddressFields $fields
     * @return float[]
     * @throws NoResultException
     */
    public function geocode(AddressFields $fields): array
    {
        $results = [
            'latitude' => 0.0,
            'longitude' => 0.0,
        ];
        $q = $this->formatQueryString($fields);
        $addresses = $this->makeCall($q);
        if (empty($addresses)) {
            throw new NoResultException('No data available');
        }
        $bestScore = $this->getBestScoreAddress($addresses);
        $results['latitude'] = $bestScore['geometry']['coordinates'][1];
        $results['longitude'] = $bestScore['geometry']['coordinates'][0];
        return $results;
    }

    /**
     * @param AddressFields $fields
     * @return string
     */
    public function formatQueryString(AddressFields $fields): string
    {
        $params = [];
        $fields->sanitizeFields();
        if (0 !== $fields->number()) {
            $params[] = $fields->number();
        }
        if ('' !== $fields->complement()) {
            $params = array_merge($params, explode(' ', $fields->complement()));
        }
        if ('' !== $fields->street()) {
            $params = array_merge($params, explode(' ', $fields->street()));
        }
        if ('' !== $fields->postalCode()) {
            $params[] = $fields->postalCode();
        }
        if ('' !== $fields->city()) {
            $params = array_merge($params, explode(' ', $fields->city()));
        }
        return implode('+', $params) . '&limit=' . $this->config['search_limit'];
    }

    /**
     * @param string $query
     * @return array
     */
    private function makeCall(string $query): array
    {
        $query = $this->config['url'] . 'q=' . $query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $query);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$data) {
            return [];
        }

        $addresses = json_decode($data, true);
        if (false === $addresses) {
            return [];
        }
        if (empty($addresses)) {
            return [];
        }
        return $addresses['features'];
    }

    /**
     * @param array $addresses
     * @return array
     */
    public function getBestScoreAddress(array $addresses): array
    {
        usort($addresses, function($a, $b) {
            if ($a['properties']['score'] === $b['properties']['score'] ) {
                return 0;
            }
            return ($a['properties']['score']  < $b['properties']['score'] ) ? -1 : 1;
        });
        return $addresses[0];
    }

    /**
     * @param float $latitude
     * @param float $longitude
     */
    public function reverse_geocode(float $latitude, float $longitude)
    {

    }
}