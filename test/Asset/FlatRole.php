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
 * There is no specific implementation of a flot role.
 * A flot role is a simple utilization of the Laminas\Permission\Rbac\Role without children
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="flat_roles")
 */
#[ORM\Entity]
#[ORM\Table(name: 'flat_roles')]
class FlatRole implements RoleInterface
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
    protected ?int $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[ORM\Column(name: 'name', type: 'string', length: 255, unique: true)]
    protected ?string $name;

    /**
     * @var Permission[]|Collection
     * @ORM\ManyToMany(targetEntity="Permission", indexBy="name", fetch="EAGER", cascade={"persist"})
     */
    #[ORM\ManyToMany(targetEntity: 'Persmission', cascade: ['persist'], fetch: 'EAGER', indexBy: 'name')]
    protected array|Collection|ArrayCollection $permissions;

    /**
     * Init the Doctrine collection
     */
    public function __construct($name)
    {
        $this->name = (string) $name;
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
     * Add a permission
     *
     * @param  string $name
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
     * @inheritDoc
     */
    public function addPermission(PermissionInterface|string $permission): void
    {
        if (is_string($permission)) {
            $permission = new Permission($permission);
        }

        $this->permissions[(string) $permission] = $permission;
    }

    /**
     * @inheritDoc
     */
    public function hasPermission(PermissionInterface|string $permission): bool
    {
        // This can be a performance problem if your role has a lot of permissions. Please refer
        // to the cookbook to an elegant way to solve this issue

        return isset($this->permissions[(string) $permission]);
    }

    public function addChild(RoleInterface $role): void
    {
        // Do nothing
    }

    public function getChildren(): iterable
    {
        return [];
    }

    public function hasChildren(): bool
    {
        // Do nothing
    }
}
