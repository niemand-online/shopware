<?php

namespace Shopware\Bundle\AccountBundle\Service;

use Shopware\Bundle\AccountBundle\Exception\OptinCreateException;
use Shopware\Bundle\AccountBundle\Struct\Optin;

interface OptinServiceInterface
{
    const OPTIN_TYPE_REGISTER = 'swRegister';

    /**
     * @param Optin $optin
     * @return Optin
     * @throws OptinCreateException
     */
    public function create(Optin $optin);
}
