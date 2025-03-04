<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Tests\Fixtures\NestedAttribute;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Tests\Fixtures\Attribute\EntityParent;
use Symfony\Component\Validator\Tests\Fixtures\EntityInterfaceB;
use Symfony\Component\Validator\Tests\Fixtures\CallbackClass;
use Symfony\Component\Validator\Tests\Fixtures\ConstraintA;

#[
    ConstraintA,
    Assert\GroupSequence(['Foo', 'Entity']),
    Assert\Callback([CallbackClass::class, 'callback']),
    Assert\Sequentially([
        new Assert\Expression('this.getFirstName() != null')
    ])
]
class Entity extends EntityParent implements EntityInterfaceB
{
    #[
        Assert\NotNull,
        Assert\Range(min: 3),
        Assert\All([
            new Assert\NotNull(),
            new Assert\Range(min: 3),
        ]),
        Assert\All(
            constraints: [
                new Assert\NotNull(),
                new Assert\Range(min: 3),
            ],
        ),
        Assert\Collection(
            fields: [
                'foo' => [
                    new Assert\NotNull(),
                    new Assert\Range(min: 3),
                ],
                'bar' => new Assert\Range(min: 5),
                'baz' => new Assert\Required([new Assert\Email()]),
                'qux' => new Assert\Optional([new Assert\NotBlank()]),
            ],
            allowExtraFields: true
        ),
        Assert\Choice(choices: ['A', 'B'], message: 'Must be one of %choices%'),
        Assert\AtLeastOneOf(
            constraints: [
                new Assert\NotNull(),
                new Assert\Range(min: 3),
            ],
            message: 'foo',
            includeInternalMessages: false,
        ),
        Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Range(min: 5),
        ]),
    ]
    public $firstName;
    #[Assert\Valid]
    public $childA;
    #[Assert\Valid]
    public $childB;
    protected $lastName;
    public $reference;
    public $reference2;
    private $internal;
    public $data = 'Overridden data';
    public $initialized = false;
    #[Assert\Type('integer')]
    protected $other;

    public function __construct($internal = null)
    {
        $this->internal = $internal;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getInternal()
    {
        return $this->internal.' from getter';
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    #[Assert\NotNull]
    public function getLastName()
    {
        return $this->lastName;
    }

    public function getValid()
    {
    }

    #[Assert\IsTrue]
    public function isValid()
    {
        return 'valid';
    }

    #[Assert\IsTrue]
    public function hasPermissions()
    {
        return 'permissions';
    }

    public function getData()
    {
        return 'Overridden data';
    }

    #[Assert\Callback(payload: 'foo')]
    public function validateMe(ExecutionContextInterface $context)
    {
    }

    #[Assert\Callback]
    public static function validateMeStatic($object, ExecutionContextInterface $context)
    {
    }

    /**
     * @return mixed
     */
    public function getChildA()
    {
        return $this->childA;
    }

    /**
     * @param mixed $childA
     */
    public function setChildA($childA)
    {
        $this->childA = $childA;
    }

    /**
     * @return mixed
     */
    public function getChildB()
    {
        return $this->childB;
    }

    /**
     * @param mixed $childB
     */
    public function setChildB($childB)
    {
        $this->childB = $childB;
    }

    public function getReference()
    {
        return $this->reference;
    }
}
