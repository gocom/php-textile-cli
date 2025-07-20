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

namespace Rah\TextileCli\Input;

use Rah\TextileCli\Api\Input\GetStdInActionInterface;

/**
 * Get STDIN action.
 */
final class GetStdInAction implements GetStdInActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute($stream = null): ?string
    {
        $stream = $stream ?? fopen('php://stdin', 'r');

        if ($stream) {
            $stdin = '';
            $read = [$stream];
            $write = null;
            $except = null;

            if (stream_select($read, $write, $except, 0) === 1) {
                while ($line = fgets($stream)) {
                    $stdin .= $line;
                }
            }

            if ($stdin !== '') {
                return $stdin;
            }
        }

        return null;
    }
}
