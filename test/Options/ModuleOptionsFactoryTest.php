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

namespace LmcTest\Rbac\Options;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceManager;
use Lmc\Rbac\Options\ModuleOptions;
use Lmc\Rbac\Options\ModuleOptionsFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ModuleOptionsFactory::class)]
class ModuleOptionsFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $config = ['lmc_rbac' => []];

        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', $config);

        $factory = new ModuleOptionsFactory();
        $options = $factory($serviceManager);

        $this->assertInstanceOf(ModuleOptions::class, $options);
    }
    public function testNoConfig(): void
    {
        $factory = new ModuleOptionsFactory();
        $serviceManager = new ServiceManager();

        $config = [];
        $serviceManager->setService('config', $config);

        $this->expectException(ServiceNotCreatedException::class);
        $factory($serviceManager);

        $config['lmc_rbac'] = 'foo';
        $serviceManager->setService('config', $config);

        $this->expectException(ServiceNotCreatedException::class);
        $factory($serviceManager);
    }
}
