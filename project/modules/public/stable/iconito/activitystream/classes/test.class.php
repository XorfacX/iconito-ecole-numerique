<?php

use ActivityStream\Client\Model\ResourceInterface;
use ActivityStream\Client\Model\Resource;

class Test implements ResourceInterface
{
    protected $id;

    protected $name;

    protected $url;

    public function __construct($id, $name, $url)
    {
        $this->id   = $id;
        $this->name = $name;
        $this->url  = $url;
    }

    /**
     * Return an resource from the current Object
     *
     * @return Resource
     */
    public function toResource()
    {
        $resource = new Resource($this->name, get_class($this));

        $resource->setId($this->id);
        $resource->setUrl($this->url);
        $resource->setAttributes(array(
            'key1' => 'test1',
            'key2' => 'test2'
        ));

        return $resource;
    }
}
