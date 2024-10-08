<?php

declare(strict_types=1);

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

namespace Lmc\Rbac\Assertion;

use Lmc\Rbac\Exception;
use Lmc\Rbac\Identity\IdentityInterface;

use function count;
use function gettype;
use function is_array;
use function is_callable;
use function is_object;
use function is_string;
use function sprintf;

class AssertionSet implements AssertionInterface
{
    /**
     * Condition constants
     */
    public const CONDITION_OR  = 'condition_or';
    public const CONDITION_AND = 'condition_and';

    /** @var array */
    private array $assertions;

    private string $condition = self::CONDITION_AND;

    /**
     * @param AssertionPluginManagerInterface $assertionPluginManager
     * @param array $assertions
     */
    public function __construct(
        private readonly AssertionPluginManagerInterface $assertionPluginManager,
        array $assertions
    ) {
        if (isset($assertions['condition'])) {
            if (
                $assertions['condition'] !== self::CONDITION_AND
                && $assertions['condition'] !== self::CONDITION_OR
            ) {
                throw new Exception\InvalidArgumentException('Invalid assertion condition given.');
            }

            $this->condition = $assertions['condition'];

            unset($assertions['condition']);
        }

        $this->assertions = $assertions;
    }

    public function assert(
        $permission,
        ?IdentityInterface $identity = null,
        mixed $context = null
    ): bool {
        if (empty($this->assertions)) {
            return false;
        }

        $assertedCount = 0;

        foreach ($this->assertions as $index => $assertion) {
            switch (true) {
                case is_callable($assertion):
                    $asserted = $assertion($permission, $identity, $context);
                    break;
                case $assertion instanceof AssertionInterface:
                    $asserted = $assertion->assert($permission, $identity, $context);
                    break;
                case is_string($assertion):
                    $this->assertions[$index] = $assertion = $this->assertionPluginManager->get($assertion);

                    $asserted = $assertion->assert($permission, $identity, $context);
                    break;
                case is_array($assertion):
                    $this->assertions[$index] = $assertion = new AssertionSet($this->assertionPluginManager, $assertion);
                    $asserted                 = $assertion->assert($permission, $identity, $context);
                    break;
                default:
                    throw new Exception\InvalidArgumentException(sprintf(
                        'Assertion must be callable, string, array or implement Lmc\Rbac\Assertion\AssertionInterface, "%s" given',
                        is_object($assertion) ? $assertion::class : gettype($assertion)
                    ));
            }

            switch ($this->condition) {
                case self::CONDITION_AND:
                    if (false === $asserted) {
                        return false;
                    }

                    break;
                case self::CONDITION_OR:
                    if (true === $asserted) {
                        return true;
                    }
                    break;
            }

            $assertedCount++;
        }

        if (self::CONDITION_AND === $this->condition && count($this->assertions) === $assertedCount) {
            return true;
        }

        return false;
    }
}
