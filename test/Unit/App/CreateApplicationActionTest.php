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

namespace Rah\TextileCli\Test\Unit\App;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Rah\TextileCli\Api\App\ApplicationFactoryInterface;
use Rah\TextileCli\Api\App\CommandPoolInterface;
use Rah\TextileCli\Api\App\GetApplicationVersionActionInterface;
use Rah\TextileCli\Api\App\RegisterErrorHandlerActionInterface;
use Rah\TextileCli\App\CreateApplicationAction;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

final class CreateApplicationActionTest extends TestCase
{
    private CreateApplicationAction $action;

    /**
     * @var ApplicationFactoryInterface&MockObject
     */
    private $applicationFactory;

    /**
     * @var GetApplicationVersionActionInterface&MockObject
     */
    private $getApplicationVersionAction;

    /**
     * @var RegisterErrorHandlerActionInterface&MockObject
     */
    private $registerErrorHandlerAction;

    /**
     * @var CommandPoolInterface&MockObject
     */
    private $commandPool;

    /**
     * @var Application&MockObject
     */
    private $application;

    /**
     * @var Command&MockObject
     */
    private $command;

    protected function setUp(): void
    {
        $this->applicationFactory = $this
            ->getMockBuilder(ApplicationFactoryInterface::class)
            ->getMock();

        $this->getApplicationVersionAction = $this
            ->getMockBuilder(GetApplicationVersionActionInterface::class)
            ->getMock();

        $this->registerErrorHandlerAction = $this
            ->getMockBuilder(RegisterErrorHandlerActionInterface::class)
            ->getMock();

        $this->commandPool = $this
            ->getMockBuilder(CommandPoolInterface::class)
            ->getMock();

        $this->application = $this
            ->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->command = $this
            ->getMockBuilder(Command::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->action = new CreateApplicationAction(
            $this->applicationFactory,
            $this->getApplicationVersionAction,
            $this->registerErrorHandlerAction,
            $this->commandPool
        );
    }

    public function testExecute(): void
    {
        $this->registerErrorHandlerAction
            ->expects($this->once())
            ->method('execute');

        $this->applicationFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->application);

        $this->commandPool
            ->expects($this->once())
            ->method('getCommands')
            ->willReturn([
                $this->command,
            ]);

        $this->application
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo($this->command))
            ->willReturn($this->command);

        $this->application
            ->expects($this->once())
            ->method('setDefaultCommand')
            ->with(
                $this->equalTo('textile'),
                $this->equalTo(true)
            )
            ->willReturnSelf();

        $this->assertSame(
            $this->application,
            $this->action->execute()
        );
    }
}
