<?php

namespace App\Services;
use App\Entity\User;
use App\Exceptions\ObjectCantSaveException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class UserService extends AbstractEntityService
{
    protected ObjectRepository $userRepository;

    protected function init()
    {
        $this->userRepository = $this->doctrine->getRepository(User::class);
    }

    /**
     * @throws ObjectCantSaveException
     */
    public function createUser(string $login, string $password): User
    {
        try {
            $user = new User($login, $password);
            $this->save($user);
            return $user;
        } catch (\Exception $e) {
            throw new ObjectCantSaveException('User not saved', previous: $e);
        }
    }

    public function createEnableUser(string $login, string $password)
    {
        $user = $this->createUser( $login,  $password);
        $user->setStateEnable();
        $this->save($user);
        return $user;
    }
}