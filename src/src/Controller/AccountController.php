<?php namespace App\Controller;

use App\DTO\AccountRequest\AccountRequestDTO;
use App\Entity\Account;
use App\Repository\AccountRepository;
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
class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account', methods: [Request::METHOD_GET])]
    #[OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: AccountRequestDTO::class), type: 'object'))]
    #[OA\Response(
        response: 200,
        description: 'Returns account and transactions',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Account::class, groups: ['account']))
        )
    )]
    #[OA\Response(response: 400, description: 'Request payload invalid')]
    #[OA\Response(response: 404, description: 'Account not found')]
    #[OA\Response(response: 422, description: 'Request validation failed')]
    public function account(
        #[MapRequestPayload] AccountRequestDTO $request,
        AccountRepository $accounts,
        Serializer $serializer
    ): JsonResponse {
        $account = $accounts->byUuid($request->uuid());
        if (null === $account) {
            throw new NotFoundHttpException('Account not found by provided uuid.');
        }

        $account->initializeTransactionsSubset($request->limit, $request->offset);
        $json = $serializer->serialize($account, 'json', ['groups' => ['account']]);

        return $this->json($json);
    }
}
