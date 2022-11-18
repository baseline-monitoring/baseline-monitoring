<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\Dto\BaselineEntry;
use App\Dto\BaselineEntryCollection;
use App\Exception\Parser\BaselineFileContentException;
use App\Exception\Parser\FileNotFoundException;
use App\Exception\Parser\ParsingException;
use Nette\Neon\Exception as NeonException;
use Nette\Neon\Neon;
use Symfony\Component\Filesystem\Filesystem;

class PHPStanParser implements ParserInterface
{
    public function __construct(
        private readonly Filesystem $filesystem
    ) {
    }

    /**
     * @throws ParsingException
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getVersion(string $fileName): ?string
    {
        $parsedFileContent = $this->getNeonFileContent($fileName);

        $level = $parsedFileContent['parameters']['level'] ?? null;

        if ($level !== null) {
            $level = (string) $level;
        }

        return $level;
    }

    public function parse(string $fileName): BaselineEntryCollection
    {
        $baselineEntryCollection = new BaselineEntryCollection($fileName);
        $parsedFileContent = $this->getNeonFileContent($fileName);

        $ignoreErrors = $parsedFileContent['parameters']['ignoreErrors'] ?? null;
        if (!is_array($ignoreErrors)) {
            throw new BaselineFileContentException('Expected array elements "parameters", "ignoreErrors" not found in file "' . $fileName . '"');
        }

        foreach ($ignoreErrors as $ignoreError) {
            $message = $ignoreError['message'];
            $count = $ignoreError['count'];
            $path = $ignoreError['path'];

            $baselineEntryCollection->addBaselineEntry(
                new BaselineEntry(
                    (string) $message,
                    (int) $count,
                    (string) $path
                )
            );
        }

        return $baselineEntryCollection;
    }

    public function supports(string $fileName): bool
    {
        $fileName = trim($fileName);

        if ('' === $fileName) {
            return false;
        }

        $extension = (string) pathinfo($fileName, PATHINFO_EXTENSION);

        return mb_strtolower($extension) === 'neon';
    }

    /**
     * @return array{parameters: array{level?: int|string|null, ignoreErrors?: array{array{message: string, count: int, path: string}}}}
     *
     * @throws BaselineFileContentException
     * @throws FileNotFoundException
     * @throws ParsingException
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getNeonFileContent(string $fileName): array
    {
        if (!$this->filesystem->exists($fileName)) {
            throw new FileNotFoundException('Given file "' . $fileName . '" does not exist');
        }

        try {
            $parsedFileContent = Neon::decodeFile($fileName);
        } catch (NeonException $e) {
            throw new ParsingException('File "' . $fileName . '" could not get parsed by library', 1649789763, $e);
        }

        if (!is_array($parsedFileContent)) {
            throw new BaselineFileContentException('File "' . $fileName . '" is not valid');
        }

        /* @phpstan-ignore-next-line */
        return $parsedFileContent;
    }
}
