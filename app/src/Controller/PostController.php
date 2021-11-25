<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\Command\AddPost;
use App\Entity\Post;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function count;

/**
 * Class PostController
 * @package App\Controller
 */
#[AsController]
final class PostController extends AbstractController
{
    #[Route('/get', name: 'get')]
    public function getPosts(PostRepository $postRepository): Response
    {
        return $this->render(
            'post/index.html.twig',
            [
                'posts' => $postRepository->findAll(),
            ]
        );
    }

    public function __invoke(
        Request $request,
        ValidatorInterface $validator,
        PostRepository $postRepository
    ): Post
    {
        $uploadedFile = $this->getUploadedFile($request, $validator);

        $post = new Post();
        $post->setTitle($request->get('title'));
        $post->setContent($request->get('content'));
        $this->dispatchMessage(new AddPost($post->getId(), $post->getTitle(), $post->getContent(), $uploadedFile));

        return $postRepository->find($post->getId());
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return UploadedFile
     */
    private function getUploadedFile(Request $request, ValidatorInterface $validator): UploadedFile
    {
        $uploadedFile = $request->files->get('file');
        $this->validateFile($uploadedFile, $validator);

        return $uploadedFile;
    }

    /**
     * @param UploadedFile|null $file
     * @param ValidatorInterface $validator
     */
    private function validateFile(?UploadedFile $file, ValidatorInterface $validator): void
    {
        if ($file === null) {
            throw new BadRequestHttpException('"file" is required');
        }

        $errors = $validator->validate($file, PostType::getFileValidator());

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    #[Route('/get/{id}', name: 'getPost')]
    public function getPost(Request $request, PostRepository $postRepository): Response
    {
        $id = $request->get('id');

        return $this->render(
            'post/index.html.twig',
            [
                'posts' => [$postRepository->find($id)],
            ]
        );
    }

    #[Route('/add', name: 'addPost')]
    public function addPost(Request $request, ValidatorInterface $validator): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $post Post
             */
            $post = $form->getData();

            $uploadedFile = $form->get('file')->getData();
            $this->validateFile($uploadedFile, $validator);

            $this->dispatchMessage(new AddPost($post->getId(), $post->getTitle(), $post->getContent(), $uploadedFile));

            return $this->redirectToRoute('get');
        }

        return $this->renderForm('post/new.html.twig', [
            'form' => $form,
        ]);
    }
}
