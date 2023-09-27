<?php

namespace App\Service;

use App\DataModel\InputDataModel;
use App\DataModel\UpdateDataModel;
use App\Entity\Client;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiService
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $em,
    ){}

    public function save(InputDataModel $inputDataModel, ?int $clientId = null): Client
    {
        $existingClient = $this->em->getRepository(Client::class)->getExisitingClient($inputDataModel);

        if (!$existingClient) {
            $existingClient = new Client();
            $existingClient->setEmail($inputDataModel->getEmail());
            $existingClient->setName($inputDataModel->getName());
            $existingClient->setPhone($inputDataModel->getPhone());
            $this->em->persist($existingClient);
        }

        $comment = new Comment();
        $comment->setComment($inputDataModel->getComment());
        $comment->setClient($existingClient);

        $this->em->persist($comment);
        $this->em->flush();

        return $existingClient;
    }

    public function update(UpdateDataModel $inputDataModel, ?int $clientId = null): Client
    {
        $existingClient = $this->em->getRepository(Client::class)->find($clientId);

        if (!$existingClient) {
            throw new BadRequestHttpException('Client not found');
        }

        $existingClient->setEmail($inputDataModel->getEmail() ?? $existingClient->getEmail());
        $existingClient->setName($inputDataModel->getName() ?? $existingClient->getName());
        $existingClient->setPhone($inputDataModel->getPhone() ?? $existingClient->getPhone());
        $this->em->persist($existingClient);

        $this->em->flush();

        return $existingClient;
    }

    public function getAllClients(): ?array
    {
        return $this->em->getRepository(Client::class)->findAll();
    }

    public function deleteClient(int $id): void
    {
        $client = $this->em->getRepository(Client::class)->find($id);
        $this->em->remove($client);
        $this->em->flush();
    }

    public function validateInput(mixed $input): void
    {
        $errors = $this->validator->validate($input);

        if (0 !== $errors->count()) {
            foreach ($errors as $message) {
                throw new BadRequestHttpException(sprintf('Incorrect parameter: %s', $message->getPropertyPath()));
            }
        }
    }

    public function getClient(int $id): ?Client
    {
        $client = $this->em->getRepository(Client::class)->find($id);
        if (!$client) {
            throw new BadRequestHttpException('Client not found');
        }

        return $client;
    }
}