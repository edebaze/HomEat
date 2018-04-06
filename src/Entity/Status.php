<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatusRepository")
 */
class Status
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Orders", mappedBy="status")
     */
    private $orders;





    // ------------------------------------------------- GETTERS & SETTERS



    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $names
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    public function getId()
    {
        return $this->id;
    }
}
