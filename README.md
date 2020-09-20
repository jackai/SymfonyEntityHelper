# Jackai Entity Helper

1. Automatically generate `Getter` / `Setter` / `Isser` / `Hasser` functions through annotation
1. Automatically generate through `@ORM\Column` judgment to generate toArray return.
1. Auto load values to entity. 

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

### Auto load values
```
namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Jackai\EntityHelper\EntityHelpers;

class TestController extends AbstractController
{
    /**
     * @Route("/test")
     */
    public function test(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        try {
            $query = $request->query->all();
            
            // validate your values
            
            $product = new Product();
            
            // auto load your values
            EntityHelpers::load($product, $query);

            $entityManager->persist($product);
            $entityManager->flush();

            return new JsonResponse([
                'code' => 'ok',
                'ret' => $product->toArray(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
            ]);
        }
    }
}

```

### Automatically generate `Getter` / `Setter` / `Isser` / `Hasser` functions
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
