<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Client;
use Symfony\Component\Console\Output\OutputInterface;

class ClientDummy extends BaseDummy implements IDummy
{
    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $client = new Client();
        $client->setRandomId('3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4');
        $client->setSecret('4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k');
        $client->setRedirectUris([]);
        $client->setAllowedGrantTypes(['password']);
        $this->manager->dummy($client);
        return $client;
    }
}