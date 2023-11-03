<?php

declare(strict_types=1);

namespace Test\Task\Model;

use Exception;
use Magento\Customer\Api\CustomerMetadataInterface;
use Test\Task\Api\CustomerAttributeValidatorInterface;

/**
 *  Validate customer attribute value
 */
class CustomerAttributeValidator implements CustomerAttributeValidatorInterface
{
    /**
     * @param CustomerMetadataInterface $customerMetadata
     */
    public function __construct(
        private CustomerMetadataInterface $customerMetadata,
    )
    {
    }

    /**
     * Check if option value is valid for specified customer attribute code
     *
     * @param $attributeCode
     * @param $attributeValue
     * @return bool
     */
    public function isOptionValueValid($attributeCode, $attributeValue): bool
    {
        try {
            $options = $this->customerMetadata->getAttributeMetadata($attributeCode)->getOptions();
            foreach ($options as $optionData) {
                if ($optionData->getValue() == $attributeValue) {
                    return true;
                }
            }
        } catch (Exception $e) {

        }
        return false;
    }
}
