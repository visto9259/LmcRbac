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

namespace LmcTest\Rbac\Service;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceManager;
use Lmc\Rbac\Options\ModuleOptions;
use Lmc\Rbac\Role\InMemoryRoleProvider;
use Lmc\Rbac\Service\RoleService;
use Lmc\Rbac\Service\RoleServiceFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(RoleServiceFactory::class)]
class RoleServiceFactoryTest extends TestCase
{
    public function testCanCreateRoleService(): void
    {
        $options = new ModuleOptions([
            'guest_role'    => 'guest',
            'role_provider' => [
                InMemoryRoleProvider::class => [
                    'foo',
                ],
            ],
        ]);

        $container = new ServiceManager([
            'services' => [
                ModuleOptions::class        => $options,
                InMemoryRoleProvider::class => new InMemoryRoleProvider([]),
            ],
        ]);

        $factory     = new RoleServiceFactory();
        $roleService = $factory($container, RoleService::class);

        $this->assertInstanceOf(RoleService::class, $roleService);
    }

    public function testThrowExceptionIfNoRoleProvider(): void
    {
        $this->expectException(ServiceNotCreatedException::class);

        $options = new ModuleOptions([
            'guest_role'    => 'guest',
            'role_provider' => [],
        ]);

        $container = new ServiceManager([
            'services' => [
                ModuleOptions::class => $options,
            ],
        ]);

        $factory = new RoleServiceFactory();
        $factory($container);
    }

    public function testThrowExceptionIfInvalidRoleProvider(): void
    {
        $this->expectException(ServiceNotCreatedException::class);

        $options = new ModuleOptions([
            'guest_role'    => 'guest',
            'role_provider' => [
                'InvalidRoleProvider' => [],
            ],
        ]);

        $container = new ServiceManager([
            'services' => [
                ModuleOptions::class  => $options,
                'InvalidRoleProvider' => function () {
                    return new stdClass();
                },
            ],
        ]);

        $factory = new RoleServiceFactory();
        $factory($container);
    }
}
