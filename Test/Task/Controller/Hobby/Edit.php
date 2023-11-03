<?php

declare(strict_types=1);

namespace Test\Task\Controller\Hobby;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory as ResultRedirectFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit implements HttpGetActionInterface
{

    /**
     * @param PageFactory $resultPageFactory
     * @param ResultRedirectFactory $resultRedirectFactory
     * @param Session $session
     */
    public function __construct(
        private PageFactory           $resultPageFactory,
        private ResultRedirectFactory $resultRedirectFactory,
        private Session               $session,
    )
    {
    }

    /**
     * Show page for logged in customers or redirect to login page
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

        return $this->resultPageFactory->create();
    }
}
