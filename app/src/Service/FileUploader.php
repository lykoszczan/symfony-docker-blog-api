<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;

/**
 * Class FileUploader
 * @package App\Service
 */
class FileUploader
{
    private string $uploadPath;
    private SluggerInterface $slugger;
    private UrlHelper $urlHelper;
    private string $relativeUploadsDir;

    /**
     * FileUploader constructor.
     * @param string $publicPath
     * @param string $uploadPath
     * @param SluggerInterface $slugger
     * @param UrlHelper $urlHelper
     */
    public function __construct(string $publicPath, string $uploadPath, SluggerInterface $slugger, UrlHelper $urlHelper)
    {
        $this->uploadPath = $uploadPath;
        $this->slugger = $slugger;
        $this->urlHelper = $urlHelper;

        $this->relativeUploadsDir = str_replace($publicPath, '', $this->uploadPath) . '/';
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();

        try {
            $file->move($this->getUploadPath(), $fileName);
        } catch (FileException $e) {
            //@TODO - logger
        }

        return $fileName;
    }

    /**
     * @return string
     */
    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    /**
     * @param string|null $fileName
     * @param bool $absolute
     * @return string|null
     */
    public function getUrl(?string $fileName, bool $absolute = true): ?string
    {
        $url = null;

        if (!empty($fileName)) {
            if ($absolute) {
                $url = $this->urlHelper->getAbsoluteUrl($this->relativeUploadsDir . $fileName);
            } else {
                $url = $this->urlHelper->getRelativePath($this->relativeUploadsDir . $fileName);
            }
        }

        return $url;
    }
}