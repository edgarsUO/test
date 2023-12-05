<?php declare(strict_types=1);

namespace App\Service\Transaction;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Enum\CurrencyEnum;
use App\Enum\DecimalPrecisionEnum;
use App\Enum\TransactionTypeEnum;
use App\Repository\AccountRepository;
use Decimal\Decimal;

final readonly class PersistTransactionService
{
    public function __construct(private AccountRepository $accounts)
    {
    }

    public function persist(
        Account $account,
        Decimal $amount,
        CurrencyEnum $currency,
        TransactionTypeEnum $transactionType
    ): void {
        $balance = $account->getBalance();

        if (TransactionTypeEnum::OUTGOING === $transactionType) {
            $balance = $balance->sub($amount);
        } elseif (TransactionTypeEnum::INCOMING === $transactionType) {
            $balance = $balance->add($amount);
        }

        $transaction = new Transaction($currency, $amount, $transactionType, $account);
        $account->setBalance($balance->round(DecimalPrecisionEnum::SCALE->value));
        $account->addTransaction($transaction);

        $this->accounts->update($account);
    }
}
