<?php

namespace Jackai\EntityHelper\Tests;

use Jackai\EntityHelper\EntityHelpers;
use Jackai\EntityHelper\Tests\Fixtures\TestProduct;
use Jackai\EntityHelper\Tests\Fixtures\TestProductPictures;
use PHPUnit\Framework\TestCase;

class EntityHelpersTest extends TestCase
{
    /**
     * 測試設定及取出資料
     *
     * @throws \ReflectionException
     */
    public function testData()
    {
        $pictures = [
            new TestProductPictures('http://test.com/aaa'),
            new TestProductPictures('http://test.com/bbb'),
            new TestProductPictures('http://test.com/ccc'),
        ];

        $testProductData = [
            'name' => 'test_name',
            'pictures' => $pictures,
            'on_sale' => true,
            'created_at' => new \DateTime('2020-10-10'),
        ];

        $testProduct = new TestProduct();

        EntityHelpers::load($testProduct, $testProductData);

        $this->assertEquals(
            ['id' => null, 'name' => 'test_name', 'on_sale' => true, 'created_at' => '2020-10-10T00:00:00+0000'],
            $testProduct->toArray()
        );

        $this->assertEquals('test_name', $testProduct->getName());

        $testProduct->setName('test_name2');
        $this->assertEquals('test_name2', $testProduct->getName());

        $this->assertEquals(true, $testProduct->hasPictures());
        $this->assertEquals($pictures, $testProduct->getPictures());

        $testProduct->setPictures([]);
        $this->assertEquals([], $testProduct->getPictures());

        $this->assertEquals(true, $testProduct->isOnSale());

        $testProduct->setOnSale(false);
        $this->assertEquals(false, $testProduct->isOnSale());

    }
}
