<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\PostRating;
use App\Repository\PostRepository;
use App\Repository\PostRatingRepository;
use App\Service\UserService;

class PostService
{
    private PostRepository $postRepository;

    private PostRatingRepository $postRatingRepository;

    private UserService $userService;

    public function __construct(
        PostRepository       $postRepository,
        PostRatingRepository $postRatingRepository,
        UserService          $userService
    ) {
        $this->postRepository       = $postRepository;
        $this->postRatingRepository = $postRatingRepository;
        $this->userService          = $userService;
    }

    /**
     * Returns an object of Post
     */
    public function create(
        User   $user,
        string $title,
        string $content,
        bool   $approve  = false,
        ?int   $dateTime = null,
        bool   $flush    = true
    ): Post {
        if (empty($dateTime)) {
            $dateTime = time();
        }

        $post = (new Post())
            ->setTitle($title)
            ->setUser($user)
            ->setContent($content)
            ->setDateTime($dateTime)
            ->setRating('0.0')
            ->setApprove($approve);
        $this
            ->postRepository
            ->add($post, $flush);
        
        return $post;
    }

    /**
     * This function for moderators, to approve Post
     */
    public function approve(Post $post, bool $flush = true)
    {
        $this
            ->postRepository
            ->approve($post, $flush);

        if ($flush) {
            $this
                ->userService
                ->sendMailsToSubscribers($post);
        }
    }

    /**
     * Returns true if rating to post added
     */
    public function changeRating(User $user, Post $post, int $rating = 0, bool $flush = true): bool
    {
        $postRating = $this->getPostRating($user, $post);

        if (! empty($postRating)) {
            $this->removeRating($postRating, $post);

            return false;
        }

        $this->addRating($user, $post, doubleval($rating), $flush);

        return true;
    }

    /**
     * Returns PostRating if user added rating to this post
     */
    public function getPostRating(User $user, Post $post): ?PostRating
    {
        return $this->postRatingRepository->findOneBy([
            'user' => $user,
            'post' => $post
        ]);
    }

    /**
     * Returns true if rating to post added
     */
    public function addRating(User $user, Post $post, int $rating = 0, bool $flush = true): bool
    {
        $postRating = (new PostRating())
            ->setPost($post)
            ->setUser($user)
            ->setRating($rating);
        $post->setRating((string) $this->countRating($post, $rating));
        $this
            ->postRatingRepository
            ->add($postRating, $flush);

        return true;
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function getLastPosts(int $amountOfPosts): ?array
    {
        return $this->postRepository->getLastPosts($amountOfPosts);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function getMoreTalkedPosts(int $amountOfPosts): ?array
    {
        $timeWeekAgo = round(time() / 10000, 0) * 10000 - 7*24*60*60;
        return $this->postRepository->getMoreTalkedPosts($amountOfPosts, $timeWeekAgo);
    }

    /**
     * Returns a Post object
     */
    public function getPostById(int $postId): ?Post
    {
        return $this->postRepository->getPostById($postId);
    }

    /**
     * Returns a Post object
     */
    public function getNotApprovedPostById(int $postId): ?Post
    {
        return $this->postRepository->getNotApprovedPostById($postId);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function getPosts(int $numberOfPosts, int $page): ?array
    {
        $lessThanMaxId = $page * $numberOfPosts - $numberOfPosts;
        return $this->postRepository->getPosts($numberOfPosts, $lessThanMaxId);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function getNotApprovedPosts(int $numberOfPosts, int $page): ?array
    {
        $lessThanMaxId = $page * $numberOfPosts - $numberOfPosts;
        return $this->postRepository->getNotApprovedPosts($numberOfPosts, $lessThanMaxId);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function getPostsByUserId(int $userId, int $numberOfPosts): ?array
    {
        return $this->postRepository->getPostsByUserId($userId, $numberOfPosts);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function getLikedPostsByUserId(int $userId, int $numberOfPosts): ?array
    {
        return $this->postRepository->getLikedPostsByUserId($userId, $numberOfPosts);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function searchPosts(string $searchWords, int $numberOfResults): ?array
    {
        $numberOfResults = $numberOfResults / 4;
        $searchWords = '%' . $searchWords . '%';

        $posts = $this->postRepository->searchByTitle($searchWords, $numberOfResults);
        $posts1 = $this->postRepository->searchByAuthor($searchWords, $numberOfResults);
        $posts2 = $this->postRepository->searchByContent($searchWords, $numberOfResults);
        $results = array_merge($posts, $posts1, $posts2);
        return $results;
    }

    /**
     * Returns true if Post updated
     */
    public function update(Post $post, bool $flush = true): bool
    {
        if ($post->getId() && $flush) {
            $this->postRepository->update($flush);

            return true;
        }

        return false;
    }

    /**
     * This function removes the Post
     */
    public function delete(Post $post, bool $flush = true)
    {
        $this->postRepository->remove($post, $flush);
    }

    /**
     * Returns an float number - rating of post
     */
    private function countRating(Post $post, float $rating = 0.0): float
    {
        $allRatingsPost = $post->getPostRatings();
        $count          = $allRatingsPost->count();

        if ($count > 0) {
            foreach ($allRatingsPost as $postRating) {
                $rating += $postRating->getRating();
            }

            $rating = round($rating / ($count + 1), 1);
        }

        return $rating;
    }

    /**
     * Returns true if rating to post deleted
     */
    private function removeRating(PostRating $postRating, Post $post): bool
    {
        $this->postRatingRepository->remove($postRating);
        $post->setRating((string) $this->countRating($post));

        return true;
    }
}