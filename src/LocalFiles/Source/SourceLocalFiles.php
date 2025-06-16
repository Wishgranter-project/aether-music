<?php

namespace WishgranterProject\AetherMusic\LocalFiles\Source;

use WishgranterProject\AetherMusic\Description;
use WishgranterProject\AetherMusic\Helper\Text;
use WishgranterProject\AetherMusic\Resource\Resource;
use WishgranterProject\AetherMusic\Source\SourceAbstract;
use WishgranterProject\AetherMusic\Source\SourceInterface;

/**
 * @todo Add support for id3 tags.
 */
class SourceLocalFiles extends SourceAbstract implements SourceInterface
{
    /**
     * Absolute path to the directory where to find the files.
     *
     * @var string
     */
    protected string $directory;

    /**
     * Absolute URL for the $directory.
     *
     * @var string
     */
    protected string $baseHref;

    /**
     * @param string $directory
     *   Absolute path to the directory where to find the files.
     * @param string $baseHref
     *   Absolute URL for the $directory.
     */
    public function __construct(string $directory, string $baseHref)
    {
        $this->directory = $directory;
        $this->baseHref  = $baseHref;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'localFiles';
    }

    /**
     * {@inheritdoc}
     */
    public function getProvider(): string
    {
        return 'localFiles';
    }

    /**
     * {@inheritdoc}
     */
    public function search(Description $description): array
    {
        if (!file_exists($this->directory)) {
            return [];
        }

        $resources = [];
        $files = $this->findMatchingFiles($description);

        foreach ($files as $file) {
            $extension = self::getExtension($file);
            $filename = basename($file);
            $basename = basename($file, $extension);

            $resources[] = Resource::createFromArray([
                'source'   => $this->getId(),
                'provider' => $this->getProvider(),
                'title'    => $basename,
                'src'      => $this->baseHref . $filename
            ]);
        }

        return $resources;
    }

    /**
     * Find files matching the description.
     *
     * @param WishgranterProject\AetherMusic\Description $description
     *   Music description.
     *
     * @return string[]
     *   Array of absolute path paths.
     */
    protected function findMatchingFiles(Description $description): array
    {
        $files = [];

        foreach ($this->getFiles() as $file) {
            $basename = basename($file);

            if ($description->title && !Text::substrCountArray($basename, $description->title)) {
                continue;
            }

            if ($description->artist && !Text::substrCountArray($basename, $description->artist)) {
                continue;
            }

            $files[] = $file;
        }

        return $files;
    }

    /**
     * List all sound files within our directory.
     *
     * @return string[]
     *   Array of absolute paths.
     */
    protected function getFiles(): array
    {
        $files = $this->scanDir();
        return array_filter($files, function ($entry) {
            $extension = SourceLocalFiles::getExtension($entry);
            return in_array($extension, ['.mp3']);
        });
    }

    /**
     * List all files within our directory.
     *
     * @return string[]
     *   A list of files.
     */
    public function scanDir()
    {
        $files = [];
        foreach (scandir($this->directory) as $entry) {
            if (in_array($entry, ['.', '..'])) {
                continue;
            }

            $file = $this->directory . $entry;

            if (!is_file($file)) {
                continue;
            }

            $files[] = $file;
        }

        return $files;
    }

    /**
     * Gets the extension of a $filename.
     *
     * @param string $filename
     *   The filename.
     *
     * @return string|null
     *   The extension.
     */
    public static function getExtension(string $filename): ?string
    {
        return preg_match('#(\.\w+)$#', $filename, $matches)
            ? $matches[0]
            : null;
    }
}
