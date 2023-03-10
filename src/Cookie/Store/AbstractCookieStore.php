<?php

declare(strict_types=1);

namespace IkonizerCore\Cookie\Store;

abstract class AbstractCookieStore implements CookieStoreInterface
{

    /** @var object */
    protected Object $cookieEnvironment;

    /**
     * Main class constructor
     *
     * @param object $cookieEnvironment
     */
    public function __construct(Object $cookieEnvironment)
    {
        $this->cookieEnvironment = $cookieEnvironment;
    }
}
