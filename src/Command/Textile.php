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

namespace Rah\TextileCli\Command;

use Netcarver\Textile\Parser;
use Rah\TextileCli\Input\GetStdInAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Parse command.
 *
 * Compiles Textile documents into HTML.
 */
final class Textile extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('textile')
            ->setDefinition([
                new InputOption(
                    'document-type',
                    't',
                    InputOption::VALUE_REQUIRED,
                    'Set the document type',
                    Parser::DOCTYPE_HTML5,
                    [
                        Parser::DOCTYPE_HTML5,
                        Parser::DOCTYPE_XHTML,
                    ]
                ),

                new InputOption(
                    'document-root-directory',
                    'd',
                    InputOption::VALUE_REQUIRED,
                    'Set the document root directory',
                    ''
                ),

                new InputOption(
                    'lite',
                    'l',
                    InputOption::VALUE_NONE,
                    'Enable lite mode'
                ),

                new InputOption(
                    'no-images',
                    'i',
                    InputOption::VALUE_NONE,
                    'Disable images'
                ),

                new InputOption(
                    'link-relationship',
                    'L',
                    InputOption::VALUE_REQUIRED,
                    'Set link relationship',
                    null,
                    ['nofollow', 'noreferrer']
                ),

                new InputOption(
                    'restricted',
                    'r',
                    InputOption::VALUE_NONE,
                    'Enable restricted mode'
                ),

                new InputOption(
                    'raw-blocks',
                    'U',
                    InputOption::VALUE_NONE,
                    'Enable raw HTML blocks'
                ),

                new InputOption(
                    'align-classes',
                    'A',
                    InputOption::VALUE_NONE,
                    'Enable alignment classes'
                ),

                new InputOption(
                    'no-align-classes',
                    'a',
                    InputOption::VALUE_NONE,
                    'Disable alignment classes'
                ),

                new InputOption(
                    'no-block-tags',
                    'b',
                    InputOption::VALUE_NONE,
                    'Disable block tags'
                ),

                new InputOption(
                    'no-line-wrap',
                    'w',
                    InputOption::VALUE_NONE,
                    'Disable line wrapping'
                ),

                new InputOption(
                    'image-prefix',
                    'p',
                    InputOption::VALUE_REQUIRED,
                    'Set image URL prefix'
                ),

                new InputOption(
                    'link-prefix',
                    'P',
                    InputOption::VALUE_REQUIRED,
                    'Set link URL prefix'
                ),

                new InputOption(
                    'no-dimensions',
                    'z',
                    InputOption::VALUE_NONE,
                    'Disable adding width and height to images'
                ),

                new InputOption(
                    'output',
                    'o',
                    InputOption::VALUE_REQUIRED,
                    'Save output HTML as the specified file'
                ),

                new InputArgument(
                    'file',
                    InputArgument::OPTIONAL,
                    'Textile document to parse and compile'
                ),
            ])
            ->setDescription('Compile Textile markup to HTML')
            ->setHelp(<<<EOF
CLI implementation of PHP-Textile, a modern Textile markup language parser for PHP. Textile is a humane web
text generator that takes lightweight, readable, plaintext-like markup language and converts it into well-formed
HTML.
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errorOutput = $output instanceof ConsoleOutputInterface
            ? $output->getErrorOutput()
            : $output;

        $inputFile = $input->getArgument('file');

        if ($inputFile) {
            if (!\file_exists($inputFile) || !\is_readable($inputFile)) {
                $errorOutput->writeln(
                    '<error>Textile document file could not be opened.</error>'
                );

                return Command::FAILURE;
            }

            $document = \file_get_contents($inputFile);
        } else {
            $inputSteam = $input instanceof StreamableInputInterface
                ? $input->getStream()
                : null;

            $document = (new GetStdInAction())->execute($inputSteam);
        }

        if (!$document) {
            $errorOutput->writeln(
                '<error>Textile document is required.</error>'
            );

            return Command::FAILURE;
        }

        $textile = new Parser();

        $textile
            ->setDocumentType($input->getOption('document-type') ?? Parser::DOCTYPE_HTML5)
            ->setLite((bool) $input->getOption('lite'))
            ->setImages(!$input->getOption('no-images'))
            ->setLinkRelationShip((string) $input->getOption('link-relationship'))
            ->setRestricted((bool) $input->getOption('restricted'))
            ->setRawBlocks((bool) $input->getOption('raw-blocks'))
            ->setBlockTags(!$input->getOption('no-block-tags'))
            ->setLineWrap(!$input->getOption('no-line-wrap'))
            ->setImagePrefix((string) $input->getOption('image-prefix'))
            ->setLinkPrefix((string) $input->getOption('link-prefix'))
            ->setDimensionlessImages((bool) $input->getOption('no-dimensions'));

        if ($input->getOption('document-root-directory')) {
            $textile->setDocumentRootDirectory($input->getOption('document-root-directory'));
        }

        if ($input->getOption('align-classes')) {
            $textile->setAlignClasses(true);
        }

        if ($input->getOption('no-align-classes')) {
            $textile->setAlignClasses(false);
        }

        $html = $textile->parse($document);
        $saveAs = $input->getOption('output');

        if ($saveAs) {
            $result = \file_put_contents(
                $saveAs,
                $html . "\n"
            );

            if ($result === false) {
                $errorOutput->writeln(
                    \sprintf(
                        '<error>Failed to save HTML document as %s</error>',
                        $saveAs
                    )
                );

                return Command::FAILURE;
            }

            return Command::SUCCESS;
        }

        $output->writeln($html);

        return Command::SUCCESS;
    }
}
