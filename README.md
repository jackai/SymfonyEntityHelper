# Jackai Entity Helper

Automatically generate `Getter` / `Setter` / `Isser` / `Hasser` function through annotation，And through `@ORM\Column` judgment to generate toArray return.

## Installation
1.Open a command console, enter your project directory and execute the following command to download the latest version of this bundle:

```
composer require jackai/symfony-entity-helper
```

2.Open config/services.yaml and add this config:

```
services:
    Jackai\EntityHelper\ClearEntityHelperCache:
        tags:
          - { name: kernel.cache_clearer }
```

## Useage
```
namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Jackai\EntityHelper\Annotations as EH;
use Jackai\EntityHelper\EntityHelperTrait;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
}
```
