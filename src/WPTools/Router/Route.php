<?php

namespace TwinDigital\WPTools\Router;

class Route
{

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string|null
     */
    protected $hook;

    /**
     * @var string|null
     */
    protected $template;

    /**
     * Route constructor.
     * @param string      $path
     * @param string|null $hook
     * @param string|null $template
     */
    public function __construct(string $path, $hook = null, $template = null)
    {
        $this->hook     = $hook;
        $this->path     = $path;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function hasHook()
    {
        return $this->hook !== null;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function hasTemplate()
    {
        return $this->template !== null;
    }

}
