<?php declare(strict_types=1);

namespace App\Model;

use App\Entity\Account;

final readonly class TransactionExecutionInput
{
    public function __construct(
        private Account $senderAccount,
        private Account $receiverAccount,
        private ConversionResult $conversionResult
    ) {
    }

    public function senderAccount(): Account
    {
        return $this->senderAccount;
    }

    public function receiverAccount(): Account
    {
        return $this->receiverAccount;
    }

    public function conversionResult(): ConversionResult
    {
        return $this->conversionResult;
    }
}
