<?php namespace App\Controller;

use App\DTO\ClientRequest\ClientRequestDTO;
use App\Entity\Client;
use App\Repository\ClientRepository;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api')]
class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client', methods: [Request::METHOD_GET])]
    #[OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: ClientRequestDTO::class), type: 'object'))]
    #[OA\Response(
        response: 200,
        description: 'Returns client and accounts',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Client::class, groups: ['client']))
        )
    )]
    #[OA\Response(response: 400, description: 'Request payload invalid')]
    #[OA\Response(response: 404, description: 'Client not found')]
    #[OA\Response(response: 422, description: 'Request validation failed')]
    public function client(
        #[MapRequestPayload] ClientRequestDTO $request,
        ClientRepository $clients,
        Serializer $serializer
    ): JsonResponse {
        $client = $clients->byUuid($request->uuid());
        if (null === $client) {
            throw new NotFoundHttpException('Client not found by provided uuid.');
        }

        $json = $serializer->serialize($client, 'json', ['groups' => ['client']]);

        return $this->json($json);
    }
}
