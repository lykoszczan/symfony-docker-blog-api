<?php

namespace App\Command\Handler;

use App\Command\AddPost;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class AddPostHandler
 * @package App\Command\Handler
 */
class AddPostHandler implements MessageHandlerInterface
{
    private PostRepository $posts;
    private EntityManagerInterface $entityManager;
    private FileUploader $fileUploader;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $em, FileUploader $fileUploader)
    {
        $this->posts = $postRepository;
        $this->entityManager = $em;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param AddPost $command
     */
    public function __invoke(AddPost $command)
    {
        if ($this->posts->find($command->getId())) {
            throw new LogicException('Post id has to be unique');
        }

        $post = new Post($command->getId());
        $post->setTitle($command->getTitle());
        $post->setContent($command->getContent());
        $post->setImagePath($this->fileUploader->upload($command->getImage()));

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}