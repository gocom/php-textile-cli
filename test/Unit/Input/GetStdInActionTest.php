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

namespace Rah\TextileCli\Test\Unit\Input;

use PHPUnit\Framework\TestCase;
use Rah\TextileCli\Input\GetStdInAction;

final class GetStdInActionTest extends TestCase
{
    private GetStdInAction $action;

    protected function setUp(): void
    {
        $this->action = new GetStdInAction();
    }

    public function testExecuteDefault(): void
    {
        $this->assertNull($this->action->execute());
    }

    public function testExecute(): void
    {
        $expected = "Line1\nLine2\n";

        /** @var resource $stream */
        $stream = fopen('php://temp', 'w+');

        fputs($stream, "Line1\n");
        fputs($stream, "Line2\n");

        rewind($stream);

        $this->assertSame($expected, $this->action->execute($stream));
    }
}
