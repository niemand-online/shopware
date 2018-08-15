<?php

namespace Shopware\Bundle\AccountBundle\Service;

use DateTime;

interface OptinServiceInterface
{
    const OPTIN_TYPE_REGISTER = 'swRegister';

    /**
     * @param string        $optinType
     * @param array         $data
     * @param DateTime|null $date
     *
     * @return string
     */
    public function generateOptin($optinType, array $data = [], $date = null);
}
