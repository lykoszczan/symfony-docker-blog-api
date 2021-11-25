<?php

namespace App\Serializer;

use ArrayObject;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use App\Service\FileUploader;
use App\Entity\Post;

/**
 * Class PostSerializer
 * @package App\Serializer
 */
final class PostSerializer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private FileUploader $fileUploader;
    private const ALREADY_CALLED = 'POST_OBJECT_NORMALIZER_ALREADY_CALLED';

    /**
     * PostSerializer constructor.
     * @param FileUploader $fileUploader
     */
    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param Post $data
     * @param string|null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof Post;
    }

    /**
     * @param Post $object
     * @param string|null $format
     * @param array $context
     * @return array|ArrayObject|bool|float|int|string|null
     * @throws ExceptionInterface
     */
    public function normalize($object, ?string $format = null, array $context = [])
    {
        $context[self::ALREADY_CALLED] = true;

        $object->setImagePath($this->fileUploader->getUrl($object->getImagePath()));

        return $this->normalizer->normalize($object, $format, $context);
    }
}