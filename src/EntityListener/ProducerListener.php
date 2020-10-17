<?php


namespace App\EntityListener;

use App\Entity\Farm;
use App\Entity\Producer;
<<<<<<< HEAD
=======
use Ramsey\Uuid\Uuid;
>>>>>>> 4b5f9cc... Update farm info (#8)

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
