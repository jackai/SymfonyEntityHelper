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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @EH\Getter
     */
    private $id;

    /**
     * @ORM\Column(type = "string", length = 255)
     * @EH\Getter
     * @EH\Setter
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pictures", mappedBy="product")
     * @HA\Getter
     * @HA\Hasser
     * @HA\Setter
     */
    private $pictures;
    
    /**
     * @ORM\Column(type = "boolean", name="on_sale")
     * @HA\Isser
     * @HA\Setter
     */
    private $onSale;
}
```
