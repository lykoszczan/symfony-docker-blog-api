<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\PostController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ApiResource(
 *   normalizationContext={"groups" = {"read"}},
 *   denormalizationContext={"groups" = {"write"}},
 *   itemOperations={"get"},
 *   collectionOperations={
 *     "get",
 *     "post" = {
 *       "controller" = PostController::class,
 *       "deserialize" = false,
 *       "openapi_context" = {
 *         "requestBody" = {
 *           "description" = "Adding new post",
 *           "required" = true,
 *           "content" = {
 *             "multipart/form-data" = {
 *               "schema" = {
 *                 "type" = "object",
 *                 "properties" = {
 *                   "title" = {
 *                     "description" = "Title of the post",
 *                     "type" = "string",
 *                     "example" = "My first awesome blog",
 *                   },
 *                   "content" = {
 *                     "description" = "Content of the post",
 *                     "type" = "string",
 *                     "example" = "Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum",
 *                   },
 *                   "file" = {
 *                     "type" = "string",
 *                     "format" = "binary",
 *                     "description" = "Upload a image of the post",
 *                   },
 *                 },
 *               },
 *             },
 *           },
 *         },
 *       },
 *     },
 *   },
 * )
 */
class Post
{
    private const ALLOWED_TAGS = '<ul><li><ol><p><strong>';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @Groups({"read"})
     */
    private Uuid $id;

    /**
     * @Assert\NotNull
     * @Assert\Length(
     *     min = 10,
     *     max = 80
     * )
     * @ORM\Column(type="string", length=80)
     * @Groups({"read", "write"})
     */
    private string $title;

    /**
     * @Assert\NotNull
     * @Assert\Length(
     *     min = 20
     * )
     * @ORM\Column(type="text")
     * @Groups({"read", "write"})
     */
    private string $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private string $imagePath;

    public function __construct(Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = strip_tags($title);

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = strip_tags($content, self::ALLOWED_TAGS);

        return $this;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
