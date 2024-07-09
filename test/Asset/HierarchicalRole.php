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

namespace LmcRbacTest\Asset;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LmcRbac\Permission\PermissionInterface;
use LmcRbac\Role\RoleInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="hierarchical_roles")
 */
#[ORM\Entity]
#[ORM\Table(name: 'hierarchical_roles')]
class HierarchicalRole implements RoleInterface
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=48, unique=true)
     */
    #[ORM\Column(name: 'name', type: 'string', length: 255, unique: true)]
    protected ?string $name;

    /**
     * @var RoleInterface[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="HierarchicalRole")
     */
    #[ORM\ManyToMany(targetEntity: RoleInterface::class)]
    protected array|Collection|ArrayCollection $children = [];

    /**
     * @var Permission[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Permission", indexBy="name", fetch="EAGER")
     */
    #[ORM\ManyToMany(targetEntity: Permission::class, fetch: 'EAGER', indexBy: 'name')]
    protected array|Collection|ArrayCollection $permissions;

    /**
     * Init the Doctrine collection
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    /**
     * Get the role identifier
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the role name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the role name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param RoleInterface $role
     * @return void
     */
    public function addChild(RoleInterface $role): void
    {
        $this->children[] = $role;
    }

    /**
     * @param string|PermissionInterface $permission
     * @return void
     */
    public function addPermission(string|PermissionInterface $permission): void
    {
        if (is_string($permission)) {
            $permission = new Permission($permission);
        }

        $this->permissions[(string) $permission] = $permission;
    }

    /**
     * @param string|PermissionInterface $permission
     * @return bool
     */
    public function hasPermission(string|PermissionInterface $permission): bool
    {
        // This can be a performance problem if your role has a lot of permissions. Please refer
        // to the cookbook to an elegant way to solve this issue

        return isset($this->permissions[(string) $permission]);
    }

    /**
     * @inheritDoc
     */
    public function getChildren(): iterable
    {
        return $this->children;
    }

    /**
     * @inheritDoc
     */
    public function hasChildren(): bool
    {
        return ! $this->children->isEmpty();
    }
}
