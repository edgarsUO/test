<?php declare(strict_types=1);

namespace App\Entity;

use App\Enum\CurrencyEnum;
use App\Enum\DecimalPrecisionEnum;
use App\Enum\TransactionTypeEnum;
use App\Repository\TransactionRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Decimal\Decimal;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    #[Groups(['account'])]
    private int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    #[Groups(['account'])]
    private UuidInterface $uuid;

    #[ORM\Column(length: 3)]
    #[Groups(['account'])]
    private CurrencyEnum $currency;

    #[ORM\Column(
        type: 'php_decimal',
        precision: DecimalPrecisionEnum::PRECISION->value,
        scale: DecimalPrecisionEnum::SCALE->value,
        nullable: false
    )]
    #[Groups(['account'])]
    private Decimal $amount;

    #[ORM\Column(length: 8)]
    #[Groups(['account'])]
    private TransactionTypeEnum $transactionType;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    #[Groups(['account'])]
    private DateTimeInterface $createdAt;

    public function __construct(
        CurrencyEnum $currency,
        Decimal $amount,
        TransactionTypeEnum $transactionType,
        Account $account
    ) {
        $this->uuid = Uuid::uuid4();
        $this->currency = $currency;
        $this->amount = $amount;
        $this->transactionType = $transactionType;
        $this->account = $account;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function setCurrency(CurrencyEnum $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAmount(): Decimal
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getTransactionType(): TransactionTypeEnum
    {
        return $this->transactionType;
    }

    public function setTransactionType(TransactionTypeEnum $transactionType): self
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
