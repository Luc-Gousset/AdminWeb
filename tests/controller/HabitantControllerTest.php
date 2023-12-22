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

        $habitant = $this->entityManager->getRepository(Habitant::class)->findOneBy([
            'Nom' => 'Doe',
            'Prenom' => 'John',
            'DateNaissance' => new \DateTime('1980-01-01'),
        ]);

        $this->assertNotNull($habitant);
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
            'prenom' => 'Jane', 
            'date_naissance' => '1980-01-01',
            'genre' => 'femme', // Changed gender
            'numero_address' => '123',
            'rue_address' => 'Main Street',
        ];

        $crawler = $this->client->request('PUT', '/habitant/update/' . $habitant->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($updateData));

        $this->assertResponseIsSuccessful();

        $updatedHabitant = $this->entityManager->getRepository(Habitant::class)->find($habitant->getId());
        $this->assertEquals('Jane', $updatedHabitant->getPrenom());
        $this->assertEquals('femme', $updatedHabitant->getGenre());
    }





    public function testListHabitants()
    {
        $crawler = $this->client->request('GET', '/habitant/get/all');

        $this->assertResponseIsSuccessful();
        // Additional assertions to check the contents of the response
    }

    public function testAddHabitantWithInvalidData()
    {
        $invalidData = [
            'nom' => 'D',
            'prenom' => '', 
            'date_naissance' => '1980-01-01',
            'genre' => 'femme', // Changed gender
            'numero_address' => '123',
            'rue_address' => 'Main Street',

        ];

        $crawler = $this->client->request('POST', '/habitant/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($invalidData));

        $this->assertResponseStatusCodeSame(406); // Assuming 400 is the response code for invalid data
    }

    public function testUpdateNonExistentHabitant()
    {
        $nonExistentId = 999; // An ID that does not exist in the database
        $updateData = [
            // Valid update data
        ];

        $crawler = $this->client->request('PUT', '/habitant/update/' . $nonExistentId, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($updateData));

        $this->assertResponseStatusCodeSame(404); // Assuming 404 is the response code for non-existent resource
    }


}
