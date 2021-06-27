<?php


namespace App\Serializer;
use App\Entity\Blog;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BlogNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper){
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($blog, $format = null, array $context = []) {
        $data = $this->normalizer->normalize($blog, $format, $context);
        $data['user']['id'] = $blog->getUser()->getId();
        $data['user']['email'] = $blog->getUser()->getEmail();
        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Blog;
    }
}