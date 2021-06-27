<?php


namespace App\Serializer;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PostNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper){
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($post, $format = null, array $context = []) {
        $data = $this->normalizer->normalize($post, $format, $context);
        if(!empty($post->getImage())) {
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/storage/default/'.$post->getImage());

            $data['user']['id'] = $post->getUser()->getId();
            $data['user']['email'] = $post->getUser()->getEmail();
        }
        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Post;
    }
}