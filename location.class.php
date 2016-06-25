<?php


class Location
{
    private $id = '';
    private $info = '';
    private $items = [];
    private $timers = [];
    private $ways = [];

    /**
     * Location constructor.
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}