<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
    * @return User[] Returns an array of User objects
    */
    public function findIfEmailExists($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $value)
            ->andWhere('u.isValid = TRUE')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function countUsersConnected(): int
    {
        $delay = new \DateTime('2 minutes ago');
        return $this->createQueryBuilder('u')
            ->andWhere('u.isValid = true')
            ->andWhere('u.lastActivityAt > :delay')
            ->setParameter('delay', $delay)
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function listUsersLastActivity()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.isValid = true')
            ->andWhere('u.lastActivityAt != 0')
            ->orderBy('u.lastActivityAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findAllUserByDownload()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.download', 'DESC')
            ->andWhere('u.download != 0')
            ->getQuery()
            ->setMaxResults(20)
            ->getResult()
        ;
    }
}
