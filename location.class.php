<?php


class Location
{
    /**
     * Location ID.
     *
     * @var string
     */
    private $id = '';

    /**
     * Location title.
     *
     * @var string
     */
    private $title = '';

    /**
     * Territory type.
     *
     * 0 - neutral
     * 1 - with guards
     * 2 - Templars
     * 3 - Pirates
     *
     * @var int
     */
    private $type = 0;

    /**
     * Location description.
     *
     * @var string
     */
    private $about = '';

    /**
     * List of game objects(items, NPC, users).
     *
     * @var array
     */
    private $objects = [];

    /**
     * List of timers.
     *
     * @var array
     */
    private $timers = [];

    /**
     * List of ways.
     *
     * @var array
     */
    private $ways = [];

    /**
     * Location constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @return array
     */
    public function getTimers()
    {
        return $this->timers;
    }

    /**
     * @return array
     */
    public function getWays()
    {
        return $this->ways;
    }
}