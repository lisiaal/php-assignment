<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractTestCase extends WebTestCase
{
    private string $path;
    private Generator $faker;
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    public function requestAction(
        string $method = 'GET',
        ?string $endpoint = '',
        ?array $params = [],
    ): void {
        $apiUrl = $this->path;

        if ($endpoint) {
            $endpoint = ltrim($endpoint, '/');
            if (!str_starts_with($endpoint, '?')) {
                $apiUrl .= '/';
            }
            $apiUrl .= $endpoint;
        }

        $server = [
            'HTTP_CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ];

        $this->client->request($method, $apiUrl, $params ?? [], [], $server);
    }

    protected function init(string $path): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);
        $this->faker = Factory::create();
        $this->path = $path;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
        unset($this->entityManager);
        unset($this->faker);
        unset($this->path);
    }

    public function getFaker(): ?Generator
    {
        return $this->faker;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getBrowserClient(): KernelBrowser
    {
        return $this->client;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}