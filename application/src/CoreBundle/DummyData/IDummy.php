<?php
namespace CoreBundle\DummyData;


use Symfony\Component\Console\Output\OutputInterface;

interface IDummy
{
    /**
     * generate dummy data
    */
    public function generate(OutputInterface $output, $i = null);
}