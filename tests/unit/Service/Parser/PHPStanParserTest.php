<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Parser;

use App\Dto\BaselineEntry;
use App\Dto\BaselineEntryCollection;
use App\Exception\Parser\FileNotFoundException;
use App\Service\Parser\PHPStanParser;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Filesystem;

class PHPStanParserTest extends TestCase
{
    use ProphecyTrait;

    private PHPStanParser $parser;

    private ObjectProphecy|Filesystem $filesystem;

    protected function setUp(): void
    {
        $this->filesystem = $this->prophesize(Filesystem::class);

        $this->parser = new PHPStanParser($this->filesystem->reveal());
    }

    public function testParse(): void
    {
        $shortBaseFileName = __DIR__.'/../../_data/phpstan-api-baseline-short.neon';

        $this->filesystem->exists($shortBaseFileName)
            ->shouldBeCalled()
            ->willReturn(true);

        $actualCollection = $this->parser->parse($shortBaseFileName);

        $expectedBaselineEntryCollection = new BaselineEntryCollection($shortBaseFileName, [
            new BaselineEntry(
                '#^Cannot call method addHeader\(\) on ArrayIterator\|bool\|Laminas\\\\Http\\\\Header\\\\HeaderInterface\|Laminas\\\\Http\\\\Headers\.$#',
                2,
                '../module/C9M/src/Service/C9MRequestBuilder.php'
            ),
            new BaselineEntry(
                '#^Cannot call method getProvider\(\) on Storage\\\\MySQL\\\\SomeSpace\\\\Entity\\\\Supplier\|null\.$#',
                1,
                '../module/Api/src/Cache/OfferBlacklistCache.php'
            ),
            new BaselineEntry(
                '#^Variable \$cacheKey on left side of \?\? always exists and is not nullable\.$#',
                2,
                '../module/Api/src/Cache/OfferBlacklistCache.php'
            ),
        ]);

        $this->assertEquals($expectedBaselineEntryCollection, $actualCollection);
    }

    public function testParseWithNonExistingFile(): void
    {
        $fileName = '/foo/bar/baz.neon';

        $this->filesystem->exists($fileName)
            ->shouldBeCalledOnce()
            ->willReturn(false);

        $this->expectException(FileNotFoundException::class);

        $this->parser->parse($fileName);
    }

    /**
     * @dataProvider supportsProvider
     */
    public function testSupports(bool $expectedResult, string $fileName): void
    {
        $this->assertSame($expectedResult, $this->parser->supports($fileName));
    }

    public function supportsProvider(): iterable
    {
        yield 'empty string' => [false, ''];
        yield 'neon file' => [true, 'baseline.neon'];
        yield 'neon file with directory' => [true, '/home/foo/bar/baz/foobar.neon'];
        yield 'neon file but with wrong extension' => [false, '/home/foo/bar/baz/foobar.neon.bck'];
        yield 'neon file with multiple dots in filename' => [true, '/home/foo/foo.bar.baz.neon'];
        yield 'file without extension' => [false, '/home/foo/bar/baz'];
        yield 'capslock extension' => [true, '/home/foo/bar/baz/baseline.NEON'];
        yield 'non neon file #1' => [false, 'neonfile.yaml'];
        yield 'non neon file #2' => [false, 'baseline.xml'];
        yield 'non neon file #3' => [false, '/home/neon/foo.csv'];
    }
}
