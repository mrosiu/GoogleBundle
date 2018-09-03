<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Maps\MapInterface;
use Doctrine\Common\Collections\Collection;

class MapsManager
{
    const MAP_JAVASCRIPT = 'map_javascript';
    const MAP_STATIC = 'map_static';

    private $config = array();
    private $maps = array();
    private $templating;

    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    public function setKey($key)
    {
        $this->config['key'] = $key;
    }

    public function getKey()
    {
        if (array_key_exists('key', $this->config)) {
            return $this->config['key'];
        }
    }

    public function hasMaps()
    {
        if (!empty($this->maps)) {
            return true;
        }
        return false;
    }

    public function hasMap(MapInterface $map)
    {
        if ($this->maps instanceof Collection) {
            return $this->maps->contains($map);
        } else {
            return in_array($map, $this->maps, true);
        }
    }

    public function addMap(MapInterface $map)
    {
        $this->maps[] = $map;
    }

    public function removeMap(MapInterface $map)
    {
        if (!$this->hasMap($map)) {
            return null;
        }
        if ($this->maps instanceof Collection) {
            return $this->maps->removeElement($map);
        } else {
            unset($this->maps[array_search($map, $this->maps, true)]);
            return $map;
        }
    }

    public function setMaps($maps)
    {
        $this->maps = $maps;
    }

    public function getMaps()
    {
        return $this->maps;
    }

    public function getMapById($id)
    {
        foreach ($this->maps as $map) {
            if ($id == $map->getId()) {
                return $map;
            }
        }
    }

    /**
     * create Google Map instance 
     * available options:
     *   - MapsManager::MAP_JAVASCRIPT
     *   - MapsManager::MAP_STATIC
     * 
     * @param mixed $type 
     * @return AbstractMap instance
     */
    public function create($type, $id) {
        switch ($type)
        {
            case self::MAP_JAVASCRIPT:
                $map = new Maps\JavascriptMap();
                break;

            case self::MAP_STATIC;
                $map = new Maps\StaticMap();
                break;

            default:
                throw \InvalidArgumentException(sprintf('Google Map\'s type: %s is not supported', $type));
        }

        $map->setId($id);
        $map->setApiKey($this->getKey('api_key'));

        return $map;
    }
}
