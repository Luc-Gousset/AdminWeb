<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Adresse;
use App\Entity\Habitant;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;

class HabitantControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testAddHabitant()
    {

        // Create a sample Adresse entity
        // Assemble data for the Habitant
        $habitantData = [
            'nom' => 'Doe',
            'prenom' => 'John',
            'date_naissance' => '1980-01-01',
            'genre' => 'homme',
            "numero_address" => "123",
            "rue_address" => "Main Street",
        ];

        $crawler = $this->client->request('POST', '/habitant/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($habitantData));

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateHabitant()
    {

        $adresse = new Adresse();
        $adresse->setNumero('123');
        $adresse->setNomRue('Main Street');
        $this->entityManager->persist($adresse);

        $habitant = new Habitant();
        $habitant->setNom('Doe');
        $habitant->setPrenom('John');
        $habitant->setDateNaissance(new \DateTime('1980-01-01'));
        $habitant->setGenre('homme');
        $habitant->setAdresseHabitant($adresse);
        $this->entityManager->persist($habitant);
        $this->entityManager->flush();

        // Update data for the Habitant
        $updateData = [
            'nom' => 'Doe',
            'prenom' => 'Jane', // Changed first name
            'date_naissance' => '1980-01-01',
            'genre' => 'femme', // Changed gender
            'numero_address' => '123',
            'rue_address' => 'Main Street',
        ];

        // Assuming the update endpoint is something like '/habitant/update/{id}'
        $crawler = $this->client->request('PUT', '/habitant/update/' . $habitant->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($updateData));

        $this->assertResponseIsSuccessful();

        $updatedHabitant = $this->entityManager->getRepository(Habitant::class)->find($habitant->getId());
        $this->assertEquals('Jane', $updatedHabitant->getPrenom());
        $this->assertEquals('femme', $updatedHabitant->getGenre());
    }

}
