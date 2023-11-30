<?php declare(strict_types=1);

namespace App\Tests;

trait DoctrineFixturesReloadingTrait
{
    public static function setUpBeforeClass(): void
    {
        self::reloadFixtures();
        parent::setUpBeforeClass();
    }

    public static function reloadFixtures(): void
    {
        array_walk(
            self::$fixturesGroups,
            static function ($group) use (&$groupsString): void {
                $groupsString .= sprintf(' --group=%s', $group);
            }
        );

        $command = sprintf(
            'yes 2>/dev/null | php "/var/www/bin/console" doctrine:fixtures:load%s 2>/dev/null',
            $groupsString
        );

        passthru($command);
    }
}
