<?php


namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(ObjectNormalizer $normalizer){
        $this->normalizer = $normalizer;
    }

    public function normalize($user, $format = null, array $context = []) {
        $data = $this->normalizer->normalize($user, $format, $context);
        if(!empty($post)) {
            $data['email'] = $user->getEmail();
            $data['name'] = $user->getName();
            $data['id'] = $user->getId();
        }
        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User;
    }
}