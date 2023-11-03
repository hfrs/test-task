<?php

declare(strict_types=1);

namespace Test\Task\Api;
/**
 *  Interface for customer attribute option value validation
 */
interface CustomerAttributeValidatorInterface
{
    /**
     *  Check if customer attribute value is valid
     *
     * @param string $attributeCode
     * @param mixed $attributeValue
     * @return bool
     */
    public function isOptionValueValid(string $attributeCode, mixed $attributeValue): bool;
}
