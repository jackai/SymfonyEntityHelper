<?php

namespace Jackai\EntityHelper\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Jackai\EntityHelper\Annotations as EH;
use Jackai\EntityHelper\EntityHelperTrait;

/**
 * @ORM\Entity
 */
class TestProduct
{
    use EntityHelperTrait;

    /**
     * @EH\Getter
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @EH\Getter
     * @EH\Setter
     * @ORM\Column(type = "string", length = 255)
     */
    private $name;

    /**
     * @EH\Getter
     * @EH\Hasser
     * @EH\Setter
     * @ORM\OneToMany(targetEntity="App\Entity\Pictures", mappedBy="product")
     */
    private $pictures;

    /**
     * @EH\Isser
     * @EH\Setter
     * @ORM\Column(type = "boolean", name="on_sale")
     */
    private $onSale;

    /**
     * @EH\Getter
     * @EH\Setter
     * @ORM\Column(type = "datetime", length = 255)
     */
    private $createdAt;
}
