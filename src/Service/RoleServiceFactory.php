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

namespace Lmc\Rbac\Service;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Lmc\Rbac\Options\ModuleOptions;
use Lmc\Rbac\Role\RoleProviderInterface;
use Psr\Container\ContainerInterface;

use function key;
use function sprintf;

/**
 * Factory to create the role service
 */
class RoleServiceFactory
{
    public function __invoke(ContainerInterface $container): RoleService
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get(ModuleOptions::class);

        // Get the role provider from the options
        $roleProvider = $moduleOptions->getRoleProvider();
        if (empty($roleProvider)) {
            throw new ServiceNotCreatedException('No role provider defined in LmcRbac configuration.');
        }

        $roleProviderName = key($roleProvider);
        /** @var RoleProviderInterface $roleProvider */
        $roleProvider = $container->get($roleProviderName);
        if (! $roleProvider instanceof RoleProviderInterface) {
            throw new ServiceNotCreatedException(
                sprintf('Class %s does not implement LmcRbac\Role\RoleProviderInterface', $roleProviderName)
            );
        }

        return new RoleService($roleProvider, $moduleOptions->getGuestRole());
    }
}
