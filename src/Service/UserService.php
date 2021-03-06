<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Subscription;
use App\Repository\UserRepository;
use App\Repository\SubscriptionRepository;
use App\Service\MailerService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;

    private SubscriptionRepository $subscriptionRepository;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        UserRepository              $userRepository,
        SubscriptionRepository      $subscriptionRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->userRepository         = $userRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userPasswordHasher     = $userPasswordHasher;
    }

    /**
     * @return User Returns an User object
     */
    public function register(
        string $email,
        string $fio,
        string $password,
        array  $rights,
        ?int   $dateTime = null,
        int    $isBanned = 0,
        bool   $flush    = true
    ): User
    {
        if (empty($dateTime)) {
            $dateTime = time();
        }

        $user = new User();
        $user
            ->setEmail($email)
            ->setFio($fio)
            ->setPassword(
                $this
                    ->userPasswordHasher
                    ->hashPassword($user, $password)
            )
            ->setDateTime($dateTime)
            ->setRoles($rights)
            ->setIsBanned($isBanned);
        $this
            ->userRepository
            ->add($user, $flush);
        
        return $user;
    }

    // /**
    //  * @return bool Returns true if email sended
    //  */
    // public function sendMailsToSubscribers(Post $post): bool
    // {
    //     $toAddresses = $this->getSubscribedUsersEmails($post->getUser());

    //     if (empty($toAddresses)) {
    //         return false;
    //     }

    //     if ($this->mailer->sendMailsToSubscribers($toAddresses, $post->getUser(), $post->getId())) {
    //         return true;
    //     }

    //     return false;
    // }

    // /**
    //  * @return bool Returns true if email sended
    //  */
    // public function sendMailToRecoveryPassword(User $user): bool
    // {
    //     $url = $this->getSecretCipherForUser($user);

    //     if ($this->mailer->sendMailToRecoveryPassword($user->getEmail(), $user->getFio(), $url)) {
    //         return true;
    //     }

    //     return false;
    // }

    // /**
    //  * @return bool Returns true if email sended
    //  */
    // public function sendMailToVerifyUser(User $user): bool
    // {
    //     $url = $this->getSecretCipherForUser($user);

    //     if ($this->mailer->sendMailToVerifyUser($user->getEmail(), $user->getFio(), $url)) {
    //         return true;
    //     }

    //     return false;
    // }

    // /**
    //  * @return User Returns User if exists
    //  */
    // public function isUserExists(string $userInfo): ?User
    // {
    //     return $this
    //         ->userRepository
    //         ->findOneByPassword($userInfo);
    // }

    // /**
    //  * @return string Returns url for recovery password
    //  */
    // private function getSecretCipherForUser(User $user): string
    // {
    //     $url = base64_encode($user->getPassword());

    //     return $url;
    // }

    // /**
    //  * @return User Returns an User object
    //  */
    // public function getUserBySecretCipher(string $secretCipher)
    // {
    //     $userInfo = base64_decode($secretCipher);
    //     $user = $this->isUserExists($userInfo);

    //     if (empty($user)) {
    //         return false;
    //     }

    //     return $user;
    // }

    /**
     * @return User Returns an User object
     */
    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

    /**
     * @return int Returns a max id of table user
     */
    public function getLastUserId(): ?int
    {
        return $this->userRepository->getLastUserId();
    }

    /**
     * @return bool Returns true if Subscription created
     */
    public function subscribe(User $userSubscribed, User $user, bool $flush = true): bool
    {
        if ($subscription = $this->isSubscribe($userSubscribed->getId(), $user->getId())) {
            $this->subscriptionRepository->remove($subscription, $flush);

            return false;
        } else {
            $subscription = (new Subscription())
                ->setUserSubscribed($userSubscribed)
                ->setUser($user)
            ;
            $this->subscriptionRepository->add($subscription, $flush);

            return true;
        }
    }

    /**
     * @return Subscription Returns an object of Subscription if user subscribed
     */
    public function isSubscribe(int $userIdWantSubscribe, int $userId): ?Subscription
    {
        return $this->subscriptionRepository->findOneBy([
            'userSubscribed' => $userIdWantSubscribe,
            'user'           => $userId
        ]);
    }

    /**
     * @return [] Returns an array of emails, which subscribed on user
     */
    public function getSubscribedUsersEmails(User $user): array
    {
        return $this->userRepository->getSubscribedUsersEmails($user->getId());
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function getUsers(int $numberOfUsers, int $page): array
    {
        $lessThanMaxId = $page * $numberOfUsers - $numberOfUsers;

        return $this->userRepository->getUsers($numberOfUsers, $lessThanMaxId);
    }

    /**
     * @return User[] Returns an array of Users objects
     */
    public function searchUsers(string $searchWords): array
    {
        $users = [];
        if (strpos($searchWords, '@') !== false) {
            if ($result = $this->userRepository->findOneByEmail($searchWords)) {
                $users[] = $result;
            }
        }
        $users1 = $this->userRepository->searchByFio('%'.$searchWords.'%');
        $results = array_merge($users, $users1);

        return $results;
    }

    /**
     * @return void
     */
    public function update(User $user, string $password = ''): void
    {
        if ($password != '') {
            $newHashedPassword = $this->userPasswordHasher->hashPassword($user, $password);
            $this->userRepository->upgradePassword($user, $newHashedPassword);
        } else{
            $this->userRepository->update();
        }
    }

    public function delete($user, bool $flush = true): void
    {
        $this->userRepository->remove($user, $flush);
    }
}