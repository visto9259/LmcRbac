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

use Doctrine\Persistence\ObjectRepository;
use Lmc\Rbac\Exception\RoleNotFoundException;

use function array_diff;
use function count;
use function implode;
use function sprintf;

/**
 * Role provider that uses Doctrine object repository to fetch roles
 */
final class ObjectRepositoryRoleProvider implements RoleProviderInterface
{
    /** @var ObjectRepository */
    private $objectRepository;

    /** @var string */
    private $roleNameProperty;

    /** @var array */
    private $roleCache = [];

    public function __construct(ObjectRepository $objectRepository, string $roleNameProperty)
    {
        $this->objectRepository = $objectRepository;
        $this->roleNameProperty = $roleNameProperty;
    }

    public function clearRoleCache(): void
    {
        $this->roleCache = [];
    }

    public function getRoles(iterable $roleNames): iterable
    {
        $key = implode($roleNames);

        if (isset($this->roleCache[$key])) {
            return $this->roleCache[$key];
        }

        /** @var RoleInterface[] $roles */
        $roles = $this->objectRepository->findBy([$this->roleNameProperty => $roleNames]);

        // We allow more roles to be loaded than asked (although this should not happen because
        // role name should have a UNIQUE constraint in database... but just in case ;))
        if (count($roles) >= count($roleNames)) {
            $this->roleCache[$key] = $roles;

            return $roles;
        }

        // We have roles that were asked but couldn't be found in database... problem!
        foreach ($roles as &$role) {
            $role = $role->getName();
        }

        throw new RoleNotFoundException(sprintf(
            'Some roles were asked but could not be loaded from database: %s',
            implode(', ', array_diff($roleNames, $roles))
        ));
    }
}
