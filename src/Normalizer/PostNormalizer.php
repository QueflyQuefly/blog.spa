<?php

namespace App\Normalizer;

use App\Entity\Post;
use Exception;

class PostNormalizer
{
    public function normalize($post): array
    {
        if (! $this->supportsNormalization($post)) {
            throw new Exception('It is not a Post object. Normalization is does not supported');
        }

        $data = [
            'id'             => $post->getId(),
            'author'         => $post->getUser()->getFio(),
            'date_time'      => $post->getDateTime(),
            'title'          => $post->getTitle(),
            'content'        => $post->getContent(),
            'rating'         => $post->getRating(),
            'count_comments' => $post->getCountComments(),
            'count_ratings'  => $post->getCountPostRatings(),
        ];

        return $data;
    }

    public function normalizeArrayOfPosts(array $posts): array
    {
        $data = [];

        foreach ($posts as $post) {
            if ($post instanceof Post) {
                $data[] = $this->normalize($post);
            }
        }

        return $data;
    }

    public function supportsNormalization($data): bool
    {
        return $data instanceof Post;
    }
}