<?php

declare(strict_types=1);

namespace Test\Task\Model\Customer\ValidateCustomerData;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\CustomerGraphQl\Api\ValidateCustomerDataInterface;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Test\Task\Api\CustomerAttributeValidatorInterface;

/**
 * Validates hobby value passed to GraphQl request
 */
class ValidateHobby implements ValidateCustomerDataInterface
{
    /**
     * @param CustomerAttributeValidatorInterface $customerAttributeValidator
     */
    public function __construct(
        private CustomerAttributeValidatorInterface $customerAttributeValidator,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(array $customerData): void
    {
        if (!isset($customerData['hobby'])) {
            return;
        }

        $hobbyValue = $customerData['hobby'];
        $isValid = $this->customerAttributeValidator->isOptionValueValid('hobby', $hobbyValue);

        if (!$isValid) {
            throw new GraphQlInputException(
                __('"%1" is not a valid hobby value.', $hobbyValue)
            );
        }
    }
}
