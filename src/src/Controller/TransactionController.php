<?php namespace App\Controller;

use App\DTO\TransactionRequest\TransactionRequestDTO;
use App\Exception\AccountException;
use App\Exception\TransactionException;
use App\Service\Transaction\ExecuteTransactionService;
use App\Service\Transaction\PrepareTransactionService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api')]
class TransactionController extends AbstractController
{
    #[Route('/transaction', name: 'app_transaction', methods: [Request::METHOD_GET])]
    #[OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: TransactionRequestDTO::class), type: 'object'))]
    #[OA\Response(response: 200, description: 'Transaction successful')]
    #[OA\Response(response: 400, description: 'Request payload invalid')]
    #[OA\Response(response: 404, description: 'Sender or receiver account not found')]
    #[OA\Response(response: 406, description: 'Transaction could not be performed')]
    #[OA\Response(response: 422, description: 'Request validation failed')]
    public function create(
        #[MapRequestPayload] TransactionRequestDTO $request,
        PrepareTransactionService $prepareTransactionService,
        ExecuteTransactionService $executeTransactionService
    ): Response {
        try {
            $executionInput = $prepareTransactionService->prepareTransaction($request);
            $executeTransactionService->execute($executionInput);
        } catch (AccountException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        } catch (TransactionException $exception) {
            throw new NotAcceptableHttpException($exception->getMessage(), $exception);
        }

        return new Response('Transaction successful');
    }
}
