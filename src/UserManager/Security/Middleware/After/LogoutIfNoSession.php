<?php

declare(strict_types=1);

namespace IkonizerCore\UserManager\Security\Middleware\After;

use Closure;
use IkonizerCore\Middleware\AfterMiddleware;

class LogoutIfNoSession extends AfterMiddleware
{
    /**
     * Clean up after logout will only execute when the logout action called
     * and will attempt to clean up database and left over cookie crumbs
     * if needs doing else will just return the next middleware
     *
     * @param object $middleware
     * @param Closure $next
     * @return void
     */
    public function middleware(object $middleware, Closure $next)
    {
        if (
            $middleware->thisRouteController() === 'security' && 
            $middleware->thisRouteAction() === 'logout') {
            if ($middleware->getSession()->get('user_id') === null) {
                $middleware->redirect('/login');
            }
        }
        return $next($middleware);
    }
}
