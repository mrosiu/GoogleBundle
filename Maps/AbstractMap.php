<?php

namespace AntiMattr\GoogleBundle\Maps;

use Doctrine\Common\Collections\Collection;

abstract class AbstractMap implements MapInterface
{
    protected $id;
    protected $markers = array();
    protected $meta = array();

    protected $apiKey;
    protected $sensor = false;
    protected $zoom = 1;

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setSensor($sensor)
    {
        $this->sensor = (bool) $sensor;
    }

    public function getSensor()
    {
        return $this->sensor;
    }

    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
    }

    public function getZoom()
    {
        return $this->zoom;
    }

    public function setId($id)
    {
        $this->id = (string) $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function hasMarkers()
    {
        if (!empty($this->markers)) {
            return true;
        }
        return false;
    }

    public function hasMarker(Marker $marker)
    {
        if ($this->markers instanceof Collection) {
            return $this->markers->contains($marker);
        } else {
            return in_array($marker, $this->markers, true);
        }
    }

    public function addMarker(Marker $marker)
    {
        $this->markers[] = $marker;
    }

    public function removeMarker(Marker $marker)
    {
        if (!$this->hasMarker($marker)) {
            return null;
        }
        if ($this->markers instanceof Collection) {
            return $this->markers->removeElement($marker);
        } else {
            unset($this->markers[array_search($marker, $this->markers, true)]);
            return $marker;
        }
    }

    public function setMarkers($markers)
    {
        $this->markers = $markers;
    }

    public function getMarkers()
    {
        return $this->markers;
    }

    public function hasMeta()
    {
        return !empty($this->meta);
    }

    public function setMeta(array $meta = array())
    {
        $this->meta = $meta;
    }

    public function getMeta()
    {
        return $this->meta;
    }
}
