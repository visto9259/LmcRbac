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

namespace Lmc\Rbac\Role;

use Lmc\Rbac\Exception;
use Lmc\Rbac\Options\ModuleOptions;
use Lmc\Rbac\Role\ObjectRepositoryRoleProvider;
use Psr\Container\ContainerInterface;

/**
 * Factory used to create an object repository role provider
 */
class ObjectRepositoryRoleProviderFactory
{
    public function __invoke(ContainerInterface $container): ObjectRepositoryRoleProvider
    {
        $moduleOptions = $container->get(ModuleOptions::class);
        $options       = $moduleOptions->getRoleProvider()[ObjectRepositoryRoleProvider::class] ?? [];

        if (! isset($options['role_name_property'])) {
            throw new Exception\RuntimeException('The "role_name_property" option is missing');
        }

        if (isset($options['object_repository'])) {
            $objectRepository = $container->get($options['object_repository']);

            return new ObjectRepositoryRoleProvider($objectRepository, $options['role_name_property']);
        }

        if (isset($options['object_manager'], $options['class_name'])) {
            $objectManager    = $container->get($options['object_manager']);
            $objectRepository = $objectManager->getRepository($options['class_name']);

            return new ObjectRepositoryRoleProvider($objectRepository, $options['role_name_property']);
        }

        throw new Exception\RuntimeException(
            'No object repository was found while creating the LmcRbac object repository role provider. Are
             you sure you specified either the "object_repository" option or "object_manager"/"class_name" options?'
        );
    }
}
