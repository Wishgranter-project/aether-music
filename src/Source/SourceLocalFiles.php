<?php 
namespace AdinanCenci\AetherMusic\Source;

use AdinanCenci\AetherMusic\Description;
use AdinanCenci\AetherMusic\Helper\Text;

class SourceLocalFiles extends SourceAbstract implements SourceInterface
{
    protected string $directory;

    protected string $baseHref;

    /**
     * @param string $directory
     */
    public function __construct(string $directory, string $baseHref)  
    {
        $this->directory = $directory;
        $this->baseHref  = $baseHref;
    }

    /**
     * {@inheritdoc}
     */
    public function getId() : string 
    {
        return 'localFiles';
    }

    /**
     * {@inheritdoc}
     */
    public function search(Description $description) : array
    {
        $resources = [];

        $files = $this->find($description);

        foreach ($files as $file) {
            $extension = self::getExtension($file);
            $basename = basename($file, $extension);

            $resources[] = new Resource(
                $this->getId(),
                '',
                $basename,
                '',
                $this->baseHref . str_replace($_SERVER['DOCUMENT_ROOT'], '', $file)
            );
        }

        return $resources;
    }

    protected function find(Description $description) : array
    {
        $files = [];

        foreach ($this->getFiles() as $file) {
            $basename = basename($file);

            if ($description->title && !Text::substrCount($basename, $description->title)) {
                continue;
            }

            if ($description->artist && !Text::substrCount($basename, $description->artist)) {
                continue;
            }

            $files[] = $file;
        }

        return $files;
    }

    protected function getFiles() : array
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

            if (!$extension = self::getExtension($entry)) {
                continue;
            }

            if (!in_array($extension, ['.mp3'])) {
                continue;
            }

            $files[] = $file;
        }

        return $files;
    }

    public static function getExtension(string $filename) : ?string
    {
        return preg_match('#(\.\w+)$#', $filename, $matches)
            ? $matches[0]
            : null;
    }
}
