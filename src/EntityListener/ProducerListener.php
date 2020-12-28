<?php

namespace App\EntityListener;

use App\Entity\Farm;
use App\Entity\Producer;
use Ramsey\Uuid\Uuid;

class ProducerListener
{
    /**
     * @param Producer $producer
     */
    public function prePersist(Producer $producer): void
    {
        //$producer->setFarm(new Farm());
    }
}
