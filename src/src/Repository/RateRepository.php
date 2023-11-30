<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rate>
 *
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[]    findAll()
 * @method Rate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByCurrency(string $currency): ?Rate
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.currency = :currency')
            ->setParameter('currency', $currency)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function add(Rate $rate): void
    {
        $this->_em->persist($rate);
        $this->_em->flush();
    }

    public function update(Rate $rate): void
    {
        $this->_em->persist($rate);
        $this->_em->flush();
    }
}
