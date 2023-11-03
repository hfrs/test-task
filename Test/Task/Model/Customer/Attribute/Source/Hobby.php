<?php

declare(strict_types=1);

namespace Test\Task\Model\Customer\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 *  Options for customer attribute 'hobby'
 */
class Hobby extends AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions(): array
    {
        return [
            ['value' => '', 'label' => ' ',],
            ['value' => 100, 'label' => 'Yoga',],
            ['value' => 200, 'label' => 'Traveling',],
            ['value' => 300, 'label' => 'Hiking',],
        ];
    }
}
