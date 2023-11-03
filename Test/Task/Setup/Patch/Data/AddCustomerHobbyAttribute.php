<?php

declare(strict_types=1);

namespace Test\Task\Setup\Patch\Data;

use Exception;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute as AttributeResource;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;
use Test\Task\Model\Customer\Attribute\Source\Hobby;

/**
 * - Add a custom customer attribute "Hobby" with possible options: "Yoga", "Traveling", "Hiking".
 * The attribute is not required.
 * - Admin must be able to edit the attribute in admin panel.
 */
class AddCustomerHobbyAttribute implements DataPatchInterface

{
    /**
     * @var string
     */
    private string $attributeCode = 'hobby';

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeResource $attributeResource
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private CustomerSetupFactory     $customerSetupFactory,
        private AttributeResource        $attributeResource,
        private LoggerInterface          $logger
    )
    {
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        try {
            $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

            $customerSetup->addAttribute(
                Customer::ENTITY,
                $this->attributeCode,
                [
                    'type' => 'int',
                    'label' => 'Hobby',
                    'input' => 'select',
                    'source' => Hobby::class,
                    'required' => false,
                    'visible' => true,
                    'user_defined' => true,
                    'position' => 999,
                    'system' => 0,
                    'is_used_in_grid' => 1,
                    'is_visible_in_grid' => 1,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,

                ]
            );
            // Add new attribute to default attribute set and group
            $customerSetup->addAttributeToSet(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
                null,
                $this->attributeCode
            );

            $attribute = $customerSetup->getEavConfig()
                ->getAttribute(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, $this->attributeCode);

            // Make attribute visible in Admin customer form so admin can edit this attribute values
            $attribute->setData('used_in_forms', [
                'adminhtml_customer'
            ]);

            $this->attributeResource->save($attribute);

        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     *  Remove attribute during "module:uninstall"
     */
    public function revert(): void
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->removeAttribute(Customer::ENTITY, $this->attributeCode);
    }
}
