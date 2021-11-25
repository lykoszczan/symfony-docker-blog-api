<?php


namespace App\Tests\Entity;


use App\Entity\Post;
use PHPUnit\Framework\TestCase;

/**
 * Class PostTest
 * @package App\Tests\Entity
 */
class PostTest extends TestCase
{
    public function testSetTitle(): void
    {
        $post = new Post();
        $title = 'titleeeee';
        $post->setTitle($title);

        self::assertEquals($title, $post->getTitle());

        $titleWithHtml = '<p>title</p> blog';
        $titleWithoutHtml = 'title blog';

        $post->setTitle($titleWithHtml);
        self::assertEquals($titleWithoutHtml, $post->getTitle());
    }

    public function testSetContent(): void
    {
        $post = new Post();
        $content = 'blog content';
        $post->setContent($content);

        self::assertEquals($content, $post->getContent());

        $contentBefore = '<p>content blog <div>aaaaaa</div><ul><li>List ' .
            '<strong>item</strong></li></ul> <style>width: 12px</style>';
        $contentAfter = '<p>content blog aaaaaa<ul><li>List <strong>item</strong></li></ul> width: 12px';

        $post->setContent($contentBefore);
        self::assertEquals($contentAfter, $post->getContent());
    }
}