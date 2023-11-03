<?php
declare(strict_types=1);

namespace Test\Task\Plugin\Customer\CustomerData;

use Exception;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;

/**
 *  Add custom values to the 'customer' section data
 */
class SectionSource
{
    public function __construct(
        private CurrentCustomer           $currentCustomer,
        private CustomerMetadataInterface $customerMetadata,
    ) {
    }

    /**
     *  Add 'hobby' and 'hobbylabel' values to the 'customer' section data
     *
     * @param $subject
     * @param $result
     * @return mixed
     */
    public function afterGetSectionData($subject, $result)
    {
        if (!$this->currentCustomer->getCustomerId()) {
            return $result;
        }

        $hobby = $this->currentCustomer->getCustomer()->getCustomAttribute('hobby');

        if ($hobby) {
            $result['hobby'] = $hobby->getValue();

            $result['hobbylabel'] = $this->getAttributeValueLabel($hobby->getValue());
        }

        return $result;
    }

    /**
     *  Get label for the "hobby" attribute value
     *
     * @param $attributeValue
     * @return string|null
     */
    private function getAttributeValueLabel($attributeValue): string|null
    {
        try {
            $options = $this->customerMetadata->getAttributeMetadata('hobby')->getOptions();
            foreach ($options as $optionData) {
                if ($optionData->getValue() && $optionData->getValue() == $attributeValue) {
                    return $optionData->getLabel();
                }
            }
        } catch (Exception $e) {

        }
        return null;
    }
}
