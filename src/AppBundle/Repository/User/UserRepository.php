<?php

namespace AppBundle\Repository\User;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {
    /**
     * @param string $username
     * @return bool
     */
    public function checkUsername(string $username) {
        $statement = $this
            ->getEntityManager()
            ->getConnection()
            ->prepare('SELECT COUNT(*) as nb_users FROM users__user WHERE username = :username')
        ;
        $statement->execute(['username' => $username]);
        return (int) $statement->fetch(\PDO::FETCH_ASSOC)['nb_users'] === 0;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function checkEmail(string $email) {
        $statement = $this
            ->getEntityManager()
            ->getConnection()
            ->prepare('SELECT COUNT(*) as nb_users FROM users__user WHERE email = :email')
        ;
        $statement->execute(['email' => $email]);
        return (int) $statement->fetch(\PDO::FETCH_ASSOC)['nb_users'] === 0;
    }

    /**
     * @param string $identifier
     * @return \AppBundle\Entity\User
     */
    public function findOneByUsernameOrEmail($identifier) {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from($this->getEntityName(), 'u')
            ->where('u.username = :identifier')
            ->orWhere('u.email = :identifier')
            ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
