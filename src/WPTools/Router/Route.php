<?php

namespace TwinDigital\WPTools\Router;

class Route
{

    protected $hook     = null;

    protected $path     = null;

    protected $template = null;

    /**
     * Route constructor.
     * @param string $path
     * @param null   $hook
     * @param null   $template
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
