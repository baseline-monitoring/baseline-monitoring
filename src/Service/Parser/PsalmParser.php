<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\Dto\BaselineEntry;
use App\Dto\BaselineEntryCollection;
use App\Exception\Parser\BaselineFileContentException;
use App\Exception\Parser\FileNotFoundException;
use App\Exception\Parser\ParsingException;
use SimpleXMLElement;
use Symfony\Component\Filesystem\Filesystem;

class PsalmParser implements ParserInterface
{
    public function __construct(
        private readonly Filesystem $filesystem
    ) {
    }

    public function parse(string $fileName): BaselineEntryCollection
    {
        $errors = $this->getFileContent($fileName);

        $collection = new BaselineEntryCollection($fileName);

        foreach ($errors as $error) {
            $filePath = (string) ($error->attributes()['src'] ?? '');

            foreach ($error->children() as $errorMessage => $child) {
                $collection->addBaselineEntry(new BaselineEntry(
                    $errorMessage,
                    (int) ($child->attributes()['occurrences'] ?? 0),
                    $filePath
                ));
            }
        }

        return $collection;
    }

    public function supports(string $fileName): bool
    {
        $fileName = trim($fileName);

        if ('' === $fileName) {
            return false;
        }

        $extension = (string) pathinfo($fileName, PATHINFO_EXTENSION);

        return mb_strtolower($extension) === 'xml';
    }

    /**
     * @return array<SimpleXMLElement>
     *
     * @throws FileNotFoundException
     * @throws ParsingException
     * @throws BaselineFileContentException
     */
    private function getFileContent(string $fileName): array
    {
        if (!$this->filesystem->exists($fileName)) {
            throw new FileNotFoundException('Given file "' . $fileName . '" does not exist');
        }

        $fileContent = file_get_contents($fileName);
        if (!$fileContent) {
            throw new ParsingException('File "' . $fileName . '" could not get read');
        }

        $content = simplexml_load_string($fileContent);

        if (!$content) {
            throw new ParsingException('File "' . $fileName . '" could not get parsed');
        }

        $output = (array) $content;

        $errors = $output['file'] ?? null;
        if (null === $errors) {
            throw new BaselineFileContentException('File "' . $fileName . '" is not valid');
        }

        return $errors;
    }

    public function getVersion(string $fileName): ?string
    {
        return null;
    }
}
