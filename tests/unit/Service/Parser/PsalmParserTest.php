<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Parser;

use App\Dto\BaselineEntry;
use App\Dto\BaselineEntryCollection;
use App\Exception\Parser\FileNotFoundException;
use App\Service\Parser\PsalmParser;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Filesystem\Filesystem;

class PsalmParserTest extends TestCase
{
    use ProphecyTrait;

    private PsalmParser $parser;

    private ObjectProphecy|Filesystem $filesystem;

    protected function setUp(): void
    {
        $this->filesystem = $this->prophesize(Filesystem::class);

        $this->parser = new PsalmParser($this->filesystem->reveal());
    }

    public function testParse(): void
    {
        $shortBaseFileName = __DIR__ . '/../../_data/psalm-baseline-short.xml';

        $this->filesystem->exists($shortBaseFileName)
            ->shouldBeCalledOnce()
            ->willReturn(true);

        $actualCollection = $this->parser->parse($shortBaseFileName);

        $expectedBaselineCollection = new BaselineEntryCollection($shortBaseFileName, [
            new BaselineEntry(
                'RedundantCastGivenDocblockType',
                1,
                'config/container.php'
            ),
            new BaselineEntry(
                'LessSpecificReturnStatement',
                1,
                'module/AccountManager/src/SomeSpace/Response/State/ActiveResponse.php'
            ),
            new BaselineEntry(
                'MoreSpecificReturnType',
                1,
                'module/AccountManager/src/SomeSpace/Response/State/ActiveResponse.php'
            ),

            new BaselineEntry(
                'InvalidScalarArgument',
                1,
                'module/Api/src/Api/Business/Converter/Paramunittype.php'
            ),
            new BaselineEntry(
                'MissingParamType',
                1,
                'module/Api/src/Api/Business/Converter/Paramunittype.php'
            ),
            new BaselineEntry(
                'MixedArgument',
                2,
                'module/Api/src/Api/Business/Converter/Paramunittype.php'
            ),
            new BaselineEntry(
                'MixedAssignment',
                1,
                'module/Api/src/Api/Business/Converter/Paramunittype.php'
            ),
            new BaselineEntry(
                'PossiblyInvalidOperand',
                1,
                'module/Api/src/Api/Business/Converter/Paramunittype.php'
            ),
            new BaselineEntry(
                'PossiblyNullArrayAccess',
                2,
                'module/Api/src/Api/Business/Converter/Paramunittype.php'
            ),
            new BaselineEntry(
                'PossiblyNullOperand',
                1,
                'module/Api/src/Api/Business/Converter/Paramunittype.php'
            ),
        ]);

        $this->assertEquals($expectedBaselineCollection, $actualCollection);
    }

    public function testParseWithNonExistingFile(): void
    {
        $fileName = '/foo/bar/baz.xml';

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
        yield 'xml file' => [true, 'baseline.xml'];
        yield 'xml file with directory' => [true, '/home/foo/bar/baz/foobar.xml'];
        yield 'xml file but with wrong extension' => [false, '/home/foo/bar/baz/foobar.xml.bck'];
        yield 'xml file with multiple dots in filename' => [true, '/home/foo/foo.bar.baz.xml'];
        yield 'file without extension' => [false, '/home/foo/bar/baz'];
        yield 'capslock extension' => [true, '/home/foo/bar/baz/baseline.XML'];
        yield 'non xml file #1' => [false, 'psalm.yaml'];
        yield 'non xml file #2' => [false, 'baseline.neon'];
        yield 'non xml file #3' => [false, '/home/xml/foo.csv'];
    }
}
