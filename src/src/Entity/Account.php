<?php declare(strict_types=1);

namespace App\Entity;

use App\Enum\CurrencyEnum;
use App\Enum\DecimalPrecisionEnum;
use App\Repository\AccountRepository;
use Decimal\Decimal;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\UniqueConstraint(fields: ['client', 'currency'])]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    #[Groups(['client', 'account'])]
    private int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    #[Groups(['client', 'account'])]
    private UuidInterface $uuid;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\Column(length: 3)]
    #[Groups(['client', 'account'])]
    private CurrencyEnum $currency;

    #[ORM\Column(
        type: 'php_decimal',
        precision: DecimalPrecisionEnum::PRECISION->value,
        scale: DecimalPrecisionEnum::SCALE->value,
        nullable: false
    )]
    #[Groups(['client', 'account'])]
    private Decimal $balance;

    #[ORM\OneToMany(
        mappedBy: 'account',
        targetEntity: Transaction::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[ORM\OrderBy(['id' => 'DESC'])]
    #[Groups(['account'])]
    private Collection $transactions;

    public function __construct(
        Client $client,
        CurrencyEnum $currency,
        Decimal $balance
    ) {
        $this->uuid = Uuid::uuid4();
        $this->client = $client;
        $this->currency = $currency;
        $this->balance = $balance;
        $this->transactions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
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

    public function getBalance(): Decimal
    {
        return $this->balance;
    }

    public function setBalance($balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setAccount($this);
        }

        return $this;
    }

    public function initializeTransactionsSubset(?int $limit = 20, ?int $offset = 0): void
    {
        $criteria = Criteria::create()
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $this->transactions = $this->transactions->matching($criteria);
    }
}
