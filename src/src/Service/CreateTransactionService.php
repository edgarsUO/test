<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionRequest\TransactionRequestDTO;
use App\Entity\Transaction;
use App\Enum\DecimalPrecisionEnum;
use App\Enum\TransactionTypeEnum;
use App\Exception\AccountException;
use App\Exception\TransactionException;
use App\Repository\AccountRepository;

final readonly class CreateTransactionService
{
    public function __construct(
        private AccountRepository $accounts,
        private ConversionService $conversionService
    ) {
    }

    public function create(TransactionRequestDTO $request): void
    {
        $sender = $request->sender();
        $receiver = $request->receiver();
        $currency = $request->currency();
        $amount = $request->amount();

        $senderAccount = $this->accounts->byUuid($sender);
        if (null === $senderAccount) {
            throw AccountException::senderNotFound($sender);
        }

        $receiverAccount = $this->accounts->byUuid($request->receiver());
        if (null == $receiverAccount) {
            throw AccountException::receiverNotFound($receiver);
        }

        if ($receiverAccount->getCurrency() !== $currency) {
            throw TransactionException::currencyMismatch($currency->value);
        }

        $conversion = $this->conversionService->convert(
            $senderAccount->getCurrency(),
            $receiverAccount->getCurrency(),
            $amount
        );

        $senderBalance = $senderAccount->getBalance();
        $receiverBalance = $receiverAccount->getBalance();
        $deductionAmount = $conversion->deductionAmount;
        $additionAmount = $conversion->additionAmount;

        if ($senderBalance < $deductionAmount) {
            throw TransactionException::lowBalance($deductionAmount);
        }

        $senderBalance = $senderBalance->sub($deductionAmount)->round(DecimalPrecisionEnum::SCALE->value);
        $outgoingTransaction = new Transaction(
            $conversion->deductionCurrency,
            $deductionAmount,
            TransactionTypeEnum::OUTGOING,
            $senderAccount
        );
        $senderAccount->setBalance($senderBalance);
        $senderAccount->addTransaction($outgoingTransaction);

        $receiverBalance = $receiverBalance->add($additionAmount)->round(DecimalPrecisionEnum::SCALE->value);
        $incomingTransaction = new Transaction(
            $conversion->additionCurrency,
            $additionAmount,
            TransactionTypeEnum::INCOMING,
            $receiverAccount
        );
        $receiverAccount->setBalance($receiverBalance);
        $receiverAccount->addTransaction($incomingTransaction);

        $this->accounts->update($senderAccount);
        $this->accounts->update($receiverAccount);
    }
}
