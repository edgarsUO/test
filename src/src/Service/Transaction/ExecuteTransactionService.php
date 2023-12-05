<?php declare(strict_types=1);

namespace App\Service\Transaction;

use App\Enum\TransactionTypeEnum;
use App\Model\TransactionExecutionInput;

final readonly class ExecuteTransactionService
{
    public function __construct(private PersistTransactionService $persistTransactionService)
    {
    }

    public function execute(TransactionExecutionInput $executionInput): void
    {
        $senderAccount = $executionInput->senderAccount();
        $receiverAccount = $executionInput->receiverAccount();
        $conversion = $executionInput->conversionResult();

        $this->persistTransactionService->persist(
            $senderAccount,
            $conversion->deductionAmount(),
            $conversion->deductionCurrency(),
            TransactionTypeEnum::OUTGOING
        );

        $this->persistTransactionService->persist(
            $receiverAccount,
            $conversion->additionAmount(),
            $conversion->additionCurrency(),
            TransactionTypeEnum::INCOMING
        );
    }
}
