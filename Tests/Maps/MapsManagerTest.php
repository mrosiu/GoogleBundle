<?php

namespace AntiMattr\GoogleBundle\Tests\Maps;

use AntiMattr\GoogleBundle\Maps\JavascriptMap;
use AntiMattr\GoogleBundle\Maps\StaticMap;
use AntiMattr\GoogleBundle\MapsManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MapsManagerTest extends WebTestCase
{
    /** @var MapsManager */
    private $googleMaps;

    /** @var Client */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->createClient();
        $this->googleMaps = static::$kernel->getContainer()->get('google.maps');
    }

    public function testCreateStaticMap()
    {
        /** @var StaticMap $map */
        $map = $this->googleMaps->create(MapsManager::MAP_STATIC, 'google_map_1');

        $this->assertInstanceOf(StaticMap::class, $map);
        $this->assertEquals('google_map_1', $map->getId());

        $map->setApiKey('API_KEY');

        $url = $map->getGoogleMapLibrary();
        $this->assertContains(StaticMap::API_ENDPOINT, $url);

        parse_str(parse_url($url, PHP_URL_QUERY), $parameters);

        $this->assertArrayHasKey('key', $parameters);
        $this->assertEquals($map->getApiKey(), $parameters['key']);
    }

    public function testCreateJavascriptMap()
    {
        /** @var JavascriptMap $map */
        $map = $this->googleMaps->create(MapsManager::MAP_JAVASCRIPT, 'google_map_1');

        $this->assertInstanceOf(JavascriptMap::class, $map);
        $this->assertEquals('google_map_1', $map->getId());

        $map->setApiKey('API_KEY');

        $url = $map->getGoogleMapLibrary();
        $this->assertContains(JavascriptMap::API_ENDPOINT, $url);

        parse_str(parse_url($url, PHP_URL_QUERY), $parameters);

        $this->assertArrayHasKey('key', $parameters);
        $this->assertEquals($map->getApiKey(), $parameters['key']);
    }
}
