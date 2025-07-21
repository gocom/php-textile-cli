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
use Rah\TextileCli\Api\Input\GetStdInActionInterface;
use Rah\TextileCli\Api\Parser\ParserFactoryInterface;
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
final class TextileCommand extends Command
{
    private const OPTION_DOCUMENT_TYPE = 'document-type';
    private const OPTION_DOCUMENT_ROOT_DIRECTORY = 'document-root-directory';
    private const OPTION_LITE = 'lite';
    private const OPTION_NO_IMAGES = 'no-images';
    private const OPTION_LINK_RELATIONSHIP = 'link-relationship';
    private const OPTION_RESTRICTED = 'restricted';
    private const OPTION_RAW_BLOCKS = 'raw-blocks';
    private const OPTION_ALIGN_CLASSES = 'align-classes';
    private const OPTION_NO_ALIGN_CLASSES = 'no-align-classes';
    private const OPTION_NO_BLOCK_TAGS = 'no-block-tags';
    private const OPTION_NO_LINE_WRAP = 'no-line-wrap';
    private const OPTION_IMAGE_PREFIX = 'image-prefix';
    private const OPTION_LINK_PREFIX = 'link-prefix';
    private const OPTION_NO_DIMENSIONS = 'no-dimensions';
    private const OPTION_OUTPUT = 'output';
    private const ARGUMENT_FILE = 'file';

    public function __construct(
        private readonly ParserFactoryInterface $parserFactory,
        private readonly GetStdInActionInterface $getStdInAction,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('textile')
            ->setDefinition([
                new InputOption(
                    self::OPTION_DOCUMENT_TYPE,
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
                    self::OPTION_DOCUMENT_ROOT_DIRECTORY,
                    'd',
                    InputOption::VALUE_REQUIRED,
                    'Set the document root directory',
                    ''
                ),

                new InputOption(
                    self::OPTION_LITE,
                    'l',
                    InputOption::VALUE_NONE,
                    'Enable lite mode'
                ),

                new InputOption(
                    self::OPTION_NO_IMAGES,
                    'i',
                    InputOption::VALUE_NONE,
                    'Disable images'
                ),

                new InputOption(
                    self::OPTION_LINK_RELATIONSHIP,
                    'L',
                    InputOption::VALUE_REQUIRED,
                    'Set link relationship',
                    null,
                    ['nofollow', 'noreferrer']
                ),

                new InputOption(
                    self::OPTION_RESTRICTED,
                    'r',
                    InputOption::VALUE_NONE,
                    'Enable restricted mode'
                ),

                new InputOption(
                    self::OPTION_RAW_BLOCKS,
                    'U',
                    InputOption::VALUE_NONE,
                    'Enable raw blocks'
                ),

                new InputOption(
                    self::OPTION_ALIGN_CLASSES,
                    'A',
                    InputOption::VALUE_NONE,
                    'Enable alignment classes'
                ),

                new InputOption(
                    self::OPTION_NO_ALIGN_CLASSES,
                    'a',
                    InputOption::VALUE_NONE,
                    'Disable alignment classes'
                ),

                new InputOption(
                    self::OPTION_NO_BLOCK_TAGS,
                    'b',
                    InputOption::VALUE_NONE,
                    'Disable block tags'
                ),

                new InputOption(
                    self::OPTION_NO_LINE_WRAP,
                    'w',
                    InputOption::VALUE_NONE,
                    'Disable line wrapping'
                ),

                new InputOption(
                    self::OPTION_IMAGE_PREFIX,
                    'p',
                    InputOption::VALUE_REQUIRED,
                    'Set image URL prefix'
                ),

                new InputOption(
                    self::OPTION_LINK_PREFIX,
                    'P',
                    InputOption::VALUE_REQUIRED,
                    'Set link URL prefix'
                ),

                new InputOption(
                    self::OPTION_NO_DIMENSIONS,
                    'z',
                    InputOption::VALUE_NONE,
                    'Disable adding width and height to images'
                ),

                new InputOption(
                    self::OPTION_OUTPUT,
                    'o',
                    InputOption::VALUE_REQUIRED,
                    'Save output HTML as the specified file'
                ),

                new InputArgument(
                    self::ARGUMENT_FILE,
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

        $inputFile = $input->getArgument(self::ARGUMENT_FILE);

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

            $document = $this->getStdInAction->execute($inputSteam);
        }

        if (!$document) {
            $errorOutput->writeln(
                '<error>Textile document is required.</error>'
            );

            return Command::FAILURE;
        }

        $parser = $this->parserFactory->create();

        $parser
            ->setDocumentType($input->getOption(self::OPTION_DOCUMENT_TYPE) ?? Parser::DOCTYPE_HTML5)
            ->setLite((bool) $input->getOption(self::OPTION_LITE))
            ->setImages(!$input->getOption(self::OPTION_NO_IMAGES))
            ->setLinkRelationShip((string) $input->getOption(self::OPTION_LINK_RELATIONSHIP))
            ->setRestricted((bool) $input->getOption(self::OPTION_RESTRICTED))
            ->setRawBlocks((bool) $input->getOption(self::OPTION_RAW_BLOCKS))
            ->setBlockTags(!$input->getOption(self::OPTION_NO_BLOCK_TAGS))
            ->setLineWrap(!$input->getOption(self::OPTION_NO_LINE_WRAP))
            ->setImagePrefix((string) $input->getOption(self::OPTION_IMAGE_PREFIX))
            ->setLinkPrefix((string) $input->getOption(self::OPTION_LINK_PREFIX))
            ->setDimensionlessImages((bool) $input->getOption(self::OPTION_NO_DIMENSIONS));

        if ($input->getOption(self::OPTION_DOCUMENT_ROOT_DIRECTORY)) {
            $parser->setDocumentRootDirectory($input->getOption(self::OPTION_DOCUMENT_ROOT_DIRECTORY));
        }

        if ($input->getOption(self::OPTION_ALIGN_CLASSES)) {
            $parser->setAlignClasses(true);
        }

        if ($input->getOption(self::OPTION_NO_ALIGN_CLASSES)) {
            $parser->setAlignClasses(false);
        }

        $html = $parser->parse($document);
        $saveAs = $input->getOption(self::OPTION_OUTPUT);

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
