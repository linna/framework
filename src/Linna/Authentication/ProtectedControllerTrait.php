<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Authentication;

use Linna\Authentication\Authentication;
use Linna\Authentication\Exception\AuthenticationException;

/**
 * Help protect a controller with login.
 *
 * This trait is designed to add to a controller, the ability to interrupt its own execution, in case authentication is required.
 *
 * This trait contains only private mothods.
 *
 * <pre><code class="php">
 * use Linna\Authentication\Authentication;
 * use Linna\Authentication\ProtectedControllerTrait;
 * use Linna\Mvc\Controller;
 * use Linna\Mvc\Model;
 *
 * class ProtectedController extends Controller
 * {
 *     use ProtectedControllerTrait;
 *
 *     public function __construct(Model $model, Authentication $authentication)
 *     {
 *         parent::__construct($model);
 *
 *         $this->protect($authentication, '/error');
 *     }
 *
 *     public function action(): bool
 *     {
 *         if ($this->authentication === false) {
 *             return false;
 *         }
 *
 *         return true;
 *     }
 * }
 * </code></pre>
 *
 */
trait ProtectedControllerTrait
{
    /**
     * @var bool Contain login status.
     */
    private $authentication = false;

    /**
     * Allow access to controller class or methods only if logged.
     * Return a status code, useful with AJAX requests.
     *
     * @param Authentication    $authentication Authentication class instance.
     * @param string            $route          Valid Route for Authentication exception.
     *
     * @return void
     *
     * @throws AuthenticationException          if user not authenticathed.
     */
    private function protect(Authentication $authentication, string $route): void
    {
        if (($this->authentication = $authentication->isLogged()) === false) {
            throw new AuthenticationException($route);
        }
    }

    /**
     * Allow access to controller class or methods only if logged
     * and do an HTTP redirection if not.
     *
     * @param Authentication    $authentication     Authentication class instance.
     * @param string            $location           Valid url for Location header.
     * @param string            $route              Valid Route for Authentication exception.
     *
     * @return void
     *
     * @throws AuthenticationException              if user not authenticathed.
     */
    private function protectWithRedirect(Authentication $authentication, string $location, string $route): void
    {
        if (($this->authentication = $authentication->isLogged()) === false) {
            \header('Location: '.$location);
            throw new AuthenticationException($route);
        }
    }
}
