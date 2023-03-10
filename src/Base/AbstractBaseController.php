<?php
declare(strict_types=1);

namespace IkonizerCore\Base;

use IkonizerCore\Base\Traits\ControllerTrait;
use IkonizerCore\Container\ContainerInterface;
use IkonizerCore\EventDispatcher\ListenerProviderInterface;
use IkonizerCore\Service\Contracts\ServiceSubscriberInterface;

abstract class AbstractBaseController implements ServiceSubscriberInterface, ListenerProviderInterface
{
    /** @var Trait */
    use ControllerTrait;
    /** @var ContainerInterface */
    protected ContainerInterface $container;
    /** @var array */
    protected array $routeParams;

    /**
     * Abstract controller constructor method
     *
     * @param array $routeParams
     * @return void
     */
    public function __construct(array $routeParams)
    {
        if ($routeParams)
            $this->routeParams = $routeParams;
    }

    /**
     * @param ContainerInterface $container
     * @return ContainerInterface
     */
    public function setContainer(ContainerInterface $container)
    {
        $previous = $this->container;
        $this->container = $container;
        return $previous;
    }

    /**
     * @return array
     */
    public static function getSubscribedServices(): array
    {
        return [];
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        return [];
    }

    /**
     * Return the current controller name as a string
     * @return string
     */
    public function thisRouteController(): string
    {
        return isset($this->routeParams['controller']) ? strtolower($this->routeParams['controller']) : '';
    }

    /**
     * Return the current controller action as a string
     * @return string
     */
    public function thisRouteAction(): string
    {
        return isset($this->routeParams['action']) ? strtolower($this->routeParams['action']) : '';
    }

    /**
     * Return the current controller namespade as a string
     * @return string
     */
    public function thisRouteNamespace(): string
    {
        return isset($this->routeParams['namespace']) ? strtolower($this->routeParams['namespace']) : '';
    }

    /**
     * Return the current controller token as a string
     * @return string
     */
    public function thisRouteToken(): string|null
    {
        $token = isset($this->routeParams['token']) ? $this->routeParams['token'] : null;
        $token = (string)$token;
        return $token;
    }

    /**
     * Return the current controller route ID if set as a int
     * @return int|false
     */
    public function thisRouteID(): int|false
    {
        $ID = isset($this->routeParams['id']) ? $this->routeParams['id'] : false;
        $ID = intval($ID);
        return $ID;
    }

    /**
     * @param Object $data
     * @return array
     */
    public function toArray(Object $data)
    {
        return (array)$data;
    }
}
