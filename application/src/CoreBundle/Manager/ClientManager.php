<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Client;

class ClientManager extends AbstractManager
{

    public function dummy(Client $client)
    {
        $this->save($client);
    }
}