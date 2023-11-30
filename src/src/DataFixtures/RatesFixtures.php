<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\DTO\RatesResponse\Factory;
use App\Entity\Rate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RatesFixtures extends Fixture implements FixtureGroupInterface
{
    public const GROUP = 'rates';

    public function load(ObjectManager $manager): void
    {
        $ratesData = json_decode(file_get_contents('tests/Data/rates_response.json'), true);
        $response = Factory::create($ratesData);

        foreach ($response->rates() as $rate) {
            $rate = new Rate(
                $response->base(),
                $rate->currency,
                $rate->value,
                $response->timestamp()
            );

            $manager->persist($rate);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [self::GROUP];
    }
}
