<?php

use ActivityStream\Client\Model\ResourceInterface;
use ActivityStream\Client\Model\Resource;

class Test implements ResourceInterface
{
    protected $displayName;

    protected $objectType;

    protected $id;

    protected $url;

    protected $attributes;

    public function __construct($displayName, $objectType, $id = null, $url = null, array $attributes = array())
    {
        $this->displayName = $displayName;
        $this->objectType  = $objectType;
        $this->id          = $id;
        $this->url         = $url;
        $this->attributes  = $attributes;
    }

    /**
     * Return an resource from the current Object
     *
     * @return Resource
     */
    public function toResource()
    {
        return new Resource($this->displayName, $this->objectType, $this->id, $this->url, $this->attributes);
    }
}
