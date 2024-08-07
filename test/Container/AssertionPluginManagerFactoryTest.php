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

namespace LmcRbacTest\Container;

use Laminas\ServiceManager\ServiceManager;
use Lmc\Rbac\Assertion\AssertionContainer;
use Lmc\Rbac\Assertion\AssertionContainerFactory;
use Lmc\Rbac\Assertion\AssertionPluginManager;
use Lmc\Rbac\Assertion\AssertionPluginManagerFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lmc\Rbac\Assertion\AssertionPluginManagerFactory
 */
class AssertionPluginManagerFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', [
            'lmc_rbac' => [
                'assertion_manager' => [],
            ],
        ]);

        $factory = new AssertionPluginManagerFactory();
        $pluginManager = $factory($serviceManager, AssertionPluginManager::class);

        $this->assertInstanceOf(AssertionPluginManager::class, $pluginManager);
    }
}
