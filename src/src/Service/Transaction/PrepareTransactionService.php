<?php declare(strict_types=1);

namespace App\Service\Transaction;

use App\DTO\TransactionRequest\TransactionRequestDTO;
use App\Exception\AccountException;
use App\Exception\TransactionException;
use App\Model\TransactionExecutionInput;
use App\Repository\AccountRepository;
use App\Service\ConversionService;

final readonly class PrepareTransactionService
{
    public function __construct(
        private AccountRepository $accounts,
        private ConversionService $conversionService,
    ) {
    }

    public function prepareTransaction(TransactionRequestDTO $request): TransactionExecutionInput
    {
        $sender = $request->sender();
        $receiver = $request->receiver();
        $currency = $request->currency();

        $senderAccount = $this->accounts->byUuid($sender);
        $receiverAccount = $this->accounts->byUuid($receiver);

        if (null === $senderAccount) {
            throw AccountException::senderNotFound($sender);
        }

        if (null == $receiverAccount) {
            throw AccountException::receiverNotFound($receiver);
        }

        if ($senderAccount === $receiverAccount) {
            throw TransactionException::sameAccountTransaction();
        }

        if ($receiverAccount->getCurrency() !== $currency) {
            throw TransactionException::currencyMismatch($currency->value);
        }

        $conversion = $this->conversionService->convert(
            $senderAccount->getCurrency(),
            $receiverAccount->getCurrency(),
            $request->amount()
        );

        $senderBalance = $senderAccount->getBalance();
        $deductionAmount = $conversion->deductionAmount();

        if ($senderBalance < $deductionAmount) {
            throw TransactionException::lowBalance($deductionAmount);
        }

        return new TransactionExecutionInput(
            $senderAccount,
            $receiverAccount,
            $conversion
        );
    }
}
