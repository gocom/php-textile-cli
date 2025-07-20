<?php

declare(strict_types=1);

/*
 * PHP-Textile CLI
 * https://github.com/gocom/php-textile-cli
 *
 * Copyright (C) 2025 Jukka Svahn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Rah\TextileCli\Test\Unit\Command;

use ErrorException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Rah\TextileCli\Command\Textile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TextileTest extends TestCase
{
    private Textile $command;

    /**
     * @var MockObject&InputInterface
     */
    private $input;

    /**
     * @var MockObject&OutputInterface
     */
    private $output;

    protected function setUp(): void
    {
        $this->input = $this
            ->getMockBuilder(InputInterface::class)
            ->getMock();

        $this->output = $this
            ->getMockBuilder(OutputInterface::class)
            ->getMock();

        $this->command = new Textile();
    }

    public function testExecute(): void
    {
        $this->assertSame(
            Command::FAILURE,
            $this->command->run($this->input, $this->output)
        );
    }

    public function testExecuteHasErrorOutput(): void
    {
        $output = $this
            ->getMockBuilder(ConsoleOutputInterface::class)
            ->getMock();

        $this->assertSame(
            Command::FAILURE,
            $this->command->run($this->input, $output)
        );
    }

    public function testExecuteHasStreamInput(): void
    {
        $input = $this
            ->getMockBuilder(StreamableInputInterface::class)
            ->getMock();

        $this->assertSame(
            Command::FAILURE,
            $this->command->run($input, $this->output)
        );
    }

    public function testExecuteInvalidInputFile(): void
    {
        $this->input
            ->expects($this->any())
            ->method('getArgument')
            ->with($this->equalTo('file'))
            ->willReturn('invalid');

        $this->assertSame(
            Command::FAILURE,
            $this->command->run($this->input, $this->output)
        );
    }

    public function testExecuteValidInputFile(): void
    {
        $this->input
            ->expects($this->any())
            ->method('getArgument')
            ->with($this->equalTo('file'))
            ->willReturn(\dirname(__DIR__, 2) . '/fixture/document.textile');

        $this->assertSame(
            Command::SUCCESS,
            $this->command->run($this->input, $this->output)
        );
    }

    public function testExecuteSaveAsFile(): void
    {
        $this->input
            ->expects($this->any())
            ->method('getArgument')
            ->with($this->equalTo('file'))
            ->willReturn(\dirname(__DIR__, 2) . '/fixture/document.textile');

        $this->input
            ->expects($this->any())
            ->method('getOption')
            ->willReturnCallback(static function (string $name) {
                if ($name === 'output') {
                    return 'php://memory';
                }

                return null;
            });

        $this->assertSame(
            Command::SUCCESS,
            $this->command->run($this->input, $this->output)
        );
    }

    public function testExecuteFailToSaveToAFile(): void
    {
        $this->expectException(ErrorException::class);

        $this->input
            ->expects($this->any())
            ->method('getArgument')
            ->with($this->equalTo('file'))
            ->willReturn(\dirname(__DIR__, 2) . '/fixture/document.textile');

        $this->input
            ->expects($this->any())
            ->method('getOption')
            ->willReturnCallback(static function (string $name) {
                if ($name === 'output') {
                    return __DIR__ . '/path/to/invalid.html';
                }

                return null;
            });

        $this->command->run($this->input, $this->output);
    }
}
