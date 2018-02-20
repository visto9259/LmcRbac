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

namespace ZfcRbacTest\Role;

use PHPUnit\Framework\TestCase;
use ZfcRbac\Role\HierarchicalRole;
use ZfcRbac\Role\HierarchicalRoleInterface;

/**
 * @covers \ZfcRbac\Role\HierarchicalRole
 * @group  Coverage
 */
class HierarchicalRoleTest extends TestCase
{
    /**
     * @covers \ZfcRbac\Role\HierarchicalRole::addChild
     */
    public function testCanAddChild(): void
    {
        $role = new HierarchicalRole('role');
        $child = new HierarchicalRole('child');

        $role->addChild($child);

        $this->assertCount(1, $role->getChildren());
    }

    /**
     * @covers \ZfcRbac\Role\HierarchicalRole::hasChildren
     */
    public function testHasChildren(): void
    {
        $role = new HierarchicalRole('role');

        $this->assertFalse($role->hasChildren());

        $role->addChild(new HierarchicalRole('child'));

        $this->assertTrue($role->hasChildren());
    }

    /**
     * @covers \ZfcRbac\Role\HierarchicalRole::getChildren
     */
    public function testCanGetChildren(): void
    {
        $role = new HierarchicalRole('role');
        $child1 = new HierarchicalRole('child 1');
        $child2 = new HierarchicalRole('child 2');

        $role->addChild($child1);
        $role->addChild($child2);

        $children = $role->getChildren();

        $this->assertCount(2, $children);
        $this->assertContainsOnlyInstancesOf(HierarchicalRoleInterface::class, $children);
    }
}
