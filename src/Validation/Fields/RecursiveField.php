<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Validation\Fields;

use ArekX\JsonQL\Validation\FieldInterface;
use ArekX\JsonQL\Validation\MissingIdentifierException;

/**
 * Class RecursiveField Field representing a any type.
 * @package ArekX\JsonQL\Validation\Fields
 */
class RecursiveField implements FieldInterface
{
    /**
     * @var string|null
     */
    public $identifier;

    /**
     * Field which is marked as recursive.
     *
     * @var FieldInterface
     */
    public $field;

    /**
     * RecursiveField constructor.
     *
     * @param FieldInterface $field Field to be wrapped.
     * @throws MissingIdentifierException Exception thrown when passed field does not have an identifier.
     */
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;

        if ($field->getIdentifier() === null) {
            throw new MissingIdentifierException($field);
        }
    }

    /**
     * @inheritdoc
     */
    public function validate($value, $parentValue = null): array
    {
        return $this->field->validate($value, $parentValue);
    }


    /**
     * @inheritdoc
     */
    public function definition(): array
    {
        return [
            'type' => 'recursive',
            'identifier' => $this->getIdentifier(),
            'of' => $this->field->getIdentifier()
        ];
    }


    /**
     * @inheritdoc
     */
    public function identifier(string $identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }
}