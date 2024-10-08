<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

declare(strict_types=1);

namespace LmcTest\Rbac\Assertion;

use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Lmc\Rbac\Assertion\AssertionInterface;
use Lmc\Rbac\Assertion\AssertionPluginManager;
use LmcTest\Rbac\Asset\SimpleAssertion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use stdClass;

#[CoversClass(AssertionPluginManager::class)]
class AssertionPluginManagerTest extends TestCase
{
    public function testValidationOfPluginSucceedsIfAssertionInterfaceIsImplemented()
    {
        $containerMock = $this->createMock(ContainerInterface::class);
        $container     = new AssertionPluginManager($containerMock, [
            'factories' => [
                SimpleAssertion::class => InvokableFactory::class,
            ],
        ]);

        $this->assertInstanceOf(AssertionInterface::class, $container->get(SimpleAssertion::class));
    }

    public function testValidationOfPluginFailsIfAssertionInterfaceIsNotImplemented()
    {
        $containerMock = $this->createMock(ContainerInterface::class);
        $container     = new AssertionPluginManager($containerMock, [
            'factories' => [
                stdClass::class => InvokableFactory::class,
            ],
        ]);

        $this->expectException(InvalidServiceException::class);
        $instance = $container->get(stdClass::class);
    }
}
