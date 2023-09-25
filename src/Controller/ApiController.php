<?php

namespace App\Controller;

use App\DataModel\InputDataModel;
use App\DataModel\UpdateDataModel;
use App\Entity\Client;
use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * ApiController.
 * @Route("/api", name="api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/comment", name="comment", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, ApiService $apiService): JsonResponse
    {
        $params = !empty($request->request->all()) ? json_encode($request->request->all()) : $request->getContent();
        /** @var InputDataModel $inputs */
        $input = $serializer->deserialize($params, InputDataModel::class, 'json');

        $apiService->validateInput($input);

        $client = $apiService->save($input);

        return $this->json($client);
    }

    /**
     * @Route("/client/{id}", name="updateComment", methods={"PUT"})
     */
    public function update(Request $request, int $id, SerializerInterface $serializer, ApiService $apiService): JsonResponse
    {
        /** @var InputDataModel $inputs */
        $input = $serializer->deserialize($request->getContent(), UpdateDataModel::class, 'json');

        $apiService->validateInput($input);

        $client = $apiService->update($input, $id);

        return $this->json($client);
    }

    /**
     * @Route("/clients", name="getData", methods={"GET"})
     */
    public function list(ApiService $apiService): JsonResponse
    {
        $clients = $apiService->getAllClients();

        return $this->json($clients);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id, ApiService $apiService): JsonResponse
    {
        $apiService->deleteClient($id);

        return $this->json(['results' => 'Deleted successfully!']);
    }
}