<?php

namespace Shopware\Bundle\AccountBundle\Service;

use Shopware\Bundle\AccountBundle\Exception\OptinCreateException;
use Shopware\Bundle\AccountBundle\Struct\Optin;

interface OptinServiceInterface
{
    const OPTIN_TYPE_REGISTER = 'swRegister';

    const OPTIN_TYPE_NOTIFICATION = 'swNotification';

    const OPTIN_TYPE_NEWSLETTER = 'swNewsletter';

    const OPTIN_TYPE_PRODUCT_VOTE = 'swProductVote';

    const OPTIN_TYPE_PASSWORD = 'swPassword';

    /**
     * @param Optin $optin
     * @return Optin
     * @throws OptinCreateException
     */
    public function create(Optin $optin);
}
