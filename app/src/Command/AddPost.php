<?php


namespace App\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

/**
 * Class AddPost
 * @package App\Command
 */
class AddPost
{
    private Uuid $id;
    private string $title;
    private string $content;
    private UploadedFile $image;

    public function __construct(Uuid $id, string $title, string $content, UploadedFile $image)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImage(): UploadedFile
    {
        return $this->image;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}