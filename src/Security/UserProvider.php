<?php

namespace App\Security;

use App\Document\User; 
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException; 

class UserProvider implements UserProviderInterface{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $userIdentifier 
     * @return UserInterface
     * @throws UserNotFoundException 
     */
    public function loadUserByIdentifier(string $userIdentifier): UserInterface{
        $user = $this->userRepository->findByEmail($userIdentifier);
        if (!$user) {
            throw new UserNotFoundException(sprintf('User with email "%s" not found.', $userIdentifier));
        }

        return $user;
    }

    /**
     * @param UserInterface $user 
     * @return UserInterface
     * @throws UnsupportedUserException
     * @throws UserNotFoundException
     */
    public function refreshUser(UserInterface $user): UserInterface{

        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s". Expected "%s".', get_class($user), User::class));
        }

        $reloadedUser = $this->userRepository->find($user->getId());

        if (!$reloadedUser) {
            throw new UserNotFoundException(sprintf('User with ID "%s" not found after refresh.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class): bool{
        return User::class === $class || is_subclass_of($class, User::class);
    }
}