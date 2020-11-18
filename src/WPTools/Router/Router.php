<?php

namespace TwinDigital\WPTools\Router;

/**
 * Class Router
 * @package TwinDigital\WPTools\Router
 */
class Router
{

    /**
     * @var self
     */
    private static $instance;

    /**
     * @var \TwinDigital\WPTools\Router\Route
     */
    private $matchedRoute = null;

    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * @var string
     */
    private $routeVariable = 'td_wptools_route';

    /**
     * Make instance or return existing one.
     * @return self
     */
    public static function init(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->hooks();
    }

    private function hooks()
    {
        add_action('init', [$this, 'registerRoutes']);
        add_action('parse_request', [$this, 'matchRequest']);
        add_action('template_include', [$this, 'loadRouteTemplate']);
        add_action('parse_request', [$this, 'callRouteHook']);
    }

    public function registerRoutes()
    {
        $routes = apply_filters($this->routeVariable . '_routes', $this->routes);
        foreach ($routes as $name => $route) {
            $this->addRoute($name, $route);
        }
        $this->compile();

        $hash = md5(serialize($this->routes));

        if ($hash != get_option($this->routeVariable . '_hash')) {
            flush_rewrite_rules();
            update_option($this->routeVariable . '_hash', $hash);
        }
    }

    /**
     * @param \WP $environment
     * @return void
     */
    public function matchRequest(\WP $environment)
    {
        $matchedRoute = $this->match($environment->query_vars);

        if ($matchedRoute instanceof Route) {
            $this->matchedRoute = $matchedRoute;
        }

        if (
            $matchedRoute instanceof \WP_Error
            && in_array('route_not_found', $matchedRoute->get_error_codes())
        ) {
            wp_die($matchedRoute, 'Route Not Found', ['response' => 404]);
        }
    }

    /**
     * @param $template
     * @return string
     */
    public function loadRouteTemplate($template)
    {
        if (!$this->matchedRoute instanceof Route || !$this->matchedRoute->hasTemplate()) {
            return $template;
        }

        $routeTemplate = get_query_template($this->matchedRoute->getTemplate());

        if (!empty($routeTemplate)) {
            $template = $routeTemplate;
        }

        return $template;
    }

    public function callRouteHook()
    {
        if (!$this->matchedRoute instanceof Route || !$this->matchedRoute->hasHook()) {
            return;
        }
        do_action($this->matchedRoute->getHook());
    }

    /**
     * @param string $name
     * @param Route  $route
     * @return void
     */
    public function addRoute(string $name, Route $route)
    {
        $this->routes[$name] = $route;
    }

    public function compile()
    {
        add_rewrite_tag('%' . $this->routeVariable . '%', '(.+)');

        foreach ($this->routes as $name => $route) {
            $this->addRule($name, $route);
        }
    }

    /**
     * Adds a new WordPress rewrite rule for the given Route.
     *
     * @param string $name
     * @param Route  $route
     * @param string $position
     */
    private function addRule($name, Route $route, $position = 'top')
    {
        add_rewrite_rule(
            $this->generateRouteRegex($route),
            'index.php?' . $this->routeVariable . '=' . $name,
            $position
        );
    }

    /**
     * Generates the regex for the WordPress rewrite API for the given route.
     *
     * @param Route $route
     *
     * @return string
     */
    private function generateRouteRegex(Route $route)
    {
        return '^' . ltrim(trim($route->getPath()), '/') . '$';
    }

    /**
     * Tries to find a matching route using the given query variables. Returns the matching route
     * or a WP_Error.
     *
     * @param array $queryVariables
     *
     * @return Route|\WP_Error
     */
    public function match(array $queryVariables)
    {
        if (empty($queryVariables[$this->routeVariable])) {
            return new \WP_Error('missing_route_variable');
        }

        $routeName = $queryVariables[$this->routeVariable];

        if (!isset($this->routes[$routeName])) {
            return new \WP_Error('route_not_found');
        }

        return $this->routes[$routeName];
    }

    /**
     * @return string
     */
    public function getRouteVariable(): string
    {
        return $this->routeVariable;
    }

    public function deleteRoute(string $name)
    {
        if (array_key_exists($name, $this->routes)) {
            unset($this->routes[$name]);
        }
    }
}
