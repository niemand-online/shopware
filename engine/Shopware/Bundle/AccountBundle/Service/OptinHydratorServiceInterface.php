<?php

namespace Shopware\Bundle\AccountBundle\Service;

use Shopware\Bundle\AccountBundle\Struct\Optin;

interface OptinHydratorServiceInterface
{
    /**
     * @param array $data
     * @return Optin
     */
    public function hydrate(array $data);
}
