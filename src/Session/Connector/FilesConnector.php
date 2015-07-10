<?php

namespace Orbit\Machine\Session\Connector;

use Orbit\Machine\Session\ConnectorInterface;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class FilesConnector implements ConnectorInterface
{
    /**
     * @inheritdoc
     */
    public function connect(array $config)
    {
        $session = new SessionAdapter();

        $id = di('crypt')->encrypt($config['app']['key']);
        $name = $config['session']['cookie'];

        //$session->setId($id);
        session_name($name);

        $session->setOptions([
            'uniqueId' => $id,
        ]);

        if( ! $session->isStarted()) {
            $session->start();
        }

        return $session;
    }
}