<?php

namespace App\Tests;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends AbstractTestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->init('/api');
    }

    public function testNewComment(): void
    {
        $faker = $this->getFaker();
        //test new client and new comment
        $params = [
            'clientId' => 'fc0b176d-20bd-483e-9f6e-0c5f88321f2f',
            'email' => 'api@api.com',
            'name' => $faker->name(),
            'comment' => $faker->sentence(),
            'phone' => $faker->phoneNumber()
        ];

        $this->requestAction('POST', 'comment', $params);
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->getBrowserClient()->getResponse()->getContent());
        $this->assertNotEmpty($response);

        //test input validation
        $params = [
            'email' => 'api@api.com',
            'name' => $faker->name(),
            'comment' => $faker->sentence(),
            'phone' => $faker->phoneNumber()
        ];

        $this->requestAction('POST', 'comment', $params);
        $this->assertEquals(400, $this->getBrowserClient()->getResponse()->getStatusCode());
    }

    public function testGetAll()
    {
        // Get all
        $this->requestAction('GET', 'clients');
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->getBrowserClient()->getResponse()->getContent());

        $this->assertGreaterThanOrEqual(0, count($response));
    }

    public function testDelete()
    {
        $client = $this->getEntityManager()->getRepository(Client::class)
            ->createQueryBuilder('c')
            ->where('c.deleted = false')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($client) {
            $this->requestAction('DELETE', 'delete/'.$client->getId());
            $this->assertResponseIsSuccessful();
        } else {
            $this->assertEmpty($client, 'Client is null');
        }
    }
}
