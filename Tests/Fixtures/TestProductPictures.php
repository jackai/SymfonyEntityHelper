<?php

namespace Jackai\EntityHelper\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Jackai\EntityHelper\Annotations as EH;
use Jackai\EntityHelper\EntityHelperTrait;

/**
 * @ORM\Entity
 */
class TestProductPictures
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
    private $url;

    /**
     * TestProductPictures constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }
}
