<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Client;
use App\Entity\Transaction;
use App\Enum\CurrencyEnum;
use App\Enum\TransactionTypeEnum;
use Decimal\Decimal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public const GROUP = 'app';

    public function load(ObjectManager $manager): void
    {
        $clients = self::generateClients();

        foreach ($clients as $client) {
            $manager->persist($client);
        }

        $manager->flush();
    }

    public static function generateClients(): iterable
    {
        $clients = new ArrayCollection();

        foreach (range(0, 9) as $v) {
            $client = new Client();
            $accounts = self::generateAccounts($client);

            foreach ($accounts as $account) {
                $client->addAccount($account);
            }

            $clients->add($client);
        }

        return $clients;
    }

    public static function generateAccounts(Client $client): iterable
    {
        $accounts = new ArrayCollection();
        $currencies = [
            CurrencyEnum::SEK,
            CurrencyEnum::BTC,
            CurrencyEnum::EUR,
            CurrencyEnum::USD,
            CurrencyEnum::GBP,
            CurrencyEnum::STD,
            CurrencyEnum::VEF,
            CurrencyEnum::CLF,
            CurrencyEnum::HKD,
        ];

        foreach ($currencies as $currency) {
            $account = new Account(
                $client,
                $currency,
                new Decimal(1000)
            );

            $transactions = self::generateTransactions($account, $currency);
            foreach ($transactions as $transaction) {
                $account->addTransaction($transaction);
            }

            $accounts->add($account);
        }

        return $accounts;
    }

    public static function generateTransactions(Account $account, CurrencyEnum $currency): iterable
    {
        $transactions = new ArrayCollection();
        $transactionTypes = TransactionTypeEnum::cases();

        foreach ($transactionTypes as $transactionType) {
            $transaction = new Transaction(
                $currency,
                new Decimal(100),
                $transactionType,
                $account
            );

            $transactions->add($transaction);
        }

        return $transactions;
    }

    public static function getGroups(): array
    {
        return [self::GROUP];
    }
}
