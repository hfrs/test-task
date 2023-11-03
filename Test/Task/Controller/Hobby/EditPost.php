<?php

declare(strict_types=1);

namespace Test\Task\Controller\Hobby;

use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory as ResultRedirectFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\Page;
use Test\Task\Api\CustomerAttributeValidatorInterface;

class EditPost implements HttpPostActionInterface
{

    /**
     * @param ResultRedirectFactory $resultRedirectFactory
     * @param Session $session
     * @param RequestInterface $request
     * @param CustomerRepositoryInterface $customerRepository
     * @param ManagerInterface $messageManager
     * @param CustomerAttributeValidatorInterface $customerAttributeValidator
     */
    public function __construct(
        private ResultRedirectFactory               $resultRedirectFactory,
        private Session                             $session,
        private RequestInterface                    $request,
        private CustomerRepositoryInterface         $customerRepository,
        private ManagerInterface                    $messageManager,
        private CustomerAttributeValidatorInterface $customerAttributeValidator,
    ) {
    }

    /**
     * Update attribute value for logged-in user and redirect to edit form
     *
     * @return Redirect|Page
     */
    public function execute(): Page|Redirect
    {
        if (!$this->session->isLoggedIn()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');

            return $resultRedirect;
        }
        try {
            $attributeValue = $this->request->getParam('customer-hobby');

            $this->checkCustomerHobbyValue($attributeValue);

            $this->updateCustomerHobbyAttribute($attributeValue);

            $this->messageManager->addSuccessMessage(__('You saved the account information.'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/edit');

        return $resultRedirect;
    }

    /**
     *  Check if submitted hobby value is valid
     *
     * @param $attributeValue
     * @return void
     * @throws Exception
     */
    private function checkCustomerHobbyValue($attributeValue): void
    {
        $isValid = $this->customerAttributeValidator->isOptionValueValid('hobby', $attributeValue);

        if (!$isValid) {
            throw new Exception('Invalid attribute value');
        }
    }

    /**
     *  Update customer hobby attribute for current customer
     *
     * @param $attributeValue
     * @return void
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws InputMismatchException
     */
    private function updateCustomerHobbyAttribute($attributeValue): void
    {
        $customerId = $this->session->getCustomerId();

        $updatedCustomer = $this->customerRepository->getById($customerId);
        $updatedCustomer->setCustomAttribute('hobby', $attributeValue);
        $this->customerRepository->save($updatedCustomer);
    }
}
