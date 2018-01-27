<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Commands;

use Shopware\Bundle\PluginInstallerBundle\Service\InstallerService;
use Shopware\Components\Model\ModelRepository;
use Shopware\Models\Plugin\Plugin;
use Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PluginReinstallCommand extends ShopwareCommand implements CompletionAwareInterface
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sw:plugin:reinstall')
            ->setDescription('Reinstalls the provided plugin')
            ->addArgument(
                'plugin',
                InputArgument::REQUIRED,
                'Name of the plugin to be installed.'
            )->addOption(
                'removedata',
                'r',
                InputOption::VALUE_NONE,
                'if supplied plugin data will be removed'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var InstallerService $pluginManager */
        $pluginManager = $this->container->get('shopware_plugininstaller.plugin_manager');
        $pluginName = $input->getArgument('plugin');

        try {
            $plugin = $pluginManager->getPluginByName($pluginName);
        } catch (\Exception $e) {
            $output->writeln(sprintf('Plugin by name "%s" was not found.', $pluginName));

            return 1;
        }

        $removeData = $input->getOption('removedata');
        $pluginManager->uninstallPlugin($plugin, $removeData);
        $pluginManager->installPlugin($plugin);
        $pluginManager->activatePlugin($plugin);

        $output->writeln(sprintf('Plugin %s has been reinstalled successfully.', $pluginName));
    }

    /**
     * @inheritdoc
     */
    public function completeOptionValues($optionName, CompletionContext $context)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function completeArgumentValues($argumentName, CompletionContext $context)
    {
        if ($argumentName === 'plugin') {
            /** @var ModelRepository $repository */
            $repository = $this->getContainer()->get('models')->getRepository(Plugin::class);
            $queryBuilder = $repository->createQueryBuilder('plugin');
            $result = $queryBuilder->andWhere($queryBuilder->expr()->eq('plugin.capabilityEnable', 'true'))
                ->select(['plugin.name'])
                ->getQuery()
                ->getArrayResult();
            return array_column($result, 'name');
        }

        return false;
    }
}
