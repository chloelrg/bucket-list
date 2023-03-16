<?php

namespace App\Tests\Entity;

use App\Entity\Wish;
use PHPUnit\Framework\TestCase;

class WishTest extends TestCase
{
    public function testGetterSetter(): void
    {
        $wish = (new Wish())
            ->setAuthor('clo')
            ->setDescription('une description')
            ->setTitle('un souhait')
            ->setIsPublished('true');
        $this ->assertSame('clo',$wish->getAuthor()); //identique en type et valeur
        $this ->assertNotNull($wish->getDateCreated());
        $this ->assertTrue($wish->isIsPublished());
        $this ->assertSame('un souhait',$wish->getTitle());
        $this ->assertSame('une description',$wish->getDescription());
        $maintenant = new \DateTime();
        $wish ->setDateCreated($maintenant);
    }
}
