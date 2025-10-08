<?php

namespace App\Tests\Repository;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactRepositoryTest extends KernelTestCase
{
    private ContactRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get(ContactRepository::class);
    }

    public function testSearchAndPaginateReturnsResults(): void
    {
        // Arrange : on crée et persiste un contact pour tester
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $contact = new Contact();
        $contact->setFirstName('John');
        $contact->setName('Doe');
        $contact->setMessage('Hello');
        $contact->setCreatedAt(new \DateTimeImmutable());
        $contact->setStatus('waiting');

        $entityManager->persist($contact);
        $entityManager->flush();

        // Act : on appelle la méthode de recherche
        $results = $this->repository->searchAndPaginate(1, 10, 'John');

        // Assert
        $this->assertNotEmpty($results);
        $this->assertInstanceOf(Contact::class, $results[0]);
        $this->assertSame('John', $results[0]->getFirstName());
    }
}
