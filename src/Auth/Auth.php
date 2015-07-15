<?php

namespace Orbit\Machine\Auth;

use App\Model\User;
use Orbit\Machine\Auth\Exception;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\User\Component;


class Auth extends Component
{

    const COOKIE_RMU = 'RMU',
        COOKIE_RMT = 'RMT';

    protected $model;

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Check user credentialas.
     *
     * @param  array $credentials
     *              <code>
     *              array(
     *                  'username' => ...,
     *                  'password' => ...,
     *                  'remember_me' => ... {optional:bool}
     *              );
     *              </code
     * @return bool
     * @throws \Orbit\Machine\Auth\Exception
     */
    public function check(array $credentials)
    {
        if(!$this->model instanceof Model)

            $user = false;
        if(filter_var($credentials['username'], FILTER_VALIDATE_EMAIL)) {
            $user = $this->model->findFirstByEmail($credentials['username']);
        } else {
            $user = $this->model->findFirstByUsername($credentials['username']);
        }

        if(!$user) {
            throw new Exception('Username or password doesn\'t match.');
        }

        // check user status
        $this->checkUserFlag($user);

        // check password
        if(!$this->security->checkHash($credentials['password'], $user->password)) {
            throw new Exception('Username or password doesn\'t match.');
        }

        // check is remeber_me index key exist.
        if(isset($credentials['remember_me'])) {
            $this->createRememberResource($user);
        }

    }

    /**
     * Create remember me resource to cookie.
     *
     * @param  User $user [description]
     */
    public function createRememberResource(User $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $remeber = new RemeberTokens;
        $remember->userId = $user->user_id;
        $remeber->token = $user->token;
        $remeber->userAgent = $user->user_agent;

        if($remeber->save()) {
            $expire = time() + 86400 * 8;
            $this->cookies->set(static::COOKIE_RMU, $user->id, $expire);
            $this->cookies->set(static::COOKIE_RMT, $token, $expire);
        }

    }

    /**
     * Check user flag. is_active, is_banned, is_suspended.
     *
     * @param  User $user
     * @return bool|mixed
     * @throws \Orbit\Machine\Auth\Exception
     */
    public function checkUserFlag(User $user)
    {
        if(1 !== $user->is_active) {
            throw new Exception("The use in-active");
        }

        if(1 === $user->is_banned) {
            throw new Exception('The user is banned');
        }

        if(1 === $user->is_suspended) {
            throw new Exception('The user is suspended');
        }

        return true;
    }

    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {

    }


}
