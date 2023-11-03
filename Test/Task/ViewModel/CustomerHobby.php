<?php
declare(strict_types=1);

namespace Test\Task\ViewModel;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\Data\AttributeMetadataInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CustomerHobby implements ArgumentInterface
{
    /**
     * @param Session $customerSession
     * @param CustomerMetadataInterface $customerMetadata
     */
    public function __construct(
        private Session                   $customerSession,
        private CustomerMetadataInterface $customerMetadata,
    )
    {
    }

    /**
     * @return mixed
     */
    public function getHobbyValue(): mixed
    {
        return $this->customerSession->getCustomer()->getData('hobby');
    }

    /**
     * Get options array for the customer attribute 'hobby'
     * @return array
     */
    public function getHobbyOptions(): array
    {
        $attribute = $this->_getAttribute();
        $options = $attribute ? $attribute->getOptions() : [];

        $result = [];
        foreach ($options as $optionData) {
            $result[] = [
                'value' => $optionData->getValue(),
                'label' => $optionData->getLabel(),
            ];
        }
        return $result;
    }

    /**
     * Retrieve customer attribute instance
     * @return AttributeMetadataInterface|null
     */
    protected function _getAttribute(): ?AttributeMetadataInterface
    {
        try {
            return $this->customerMetadata->getAttributeMetadata('hobby');
        } catch (NoSuchEntityException|LocalizedException $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getStoreLabel(): string
    {
        $attribute = $this->_getAttribute();

        return $attribute ? $attribute->getStoreLabel() : '';
    }
}
