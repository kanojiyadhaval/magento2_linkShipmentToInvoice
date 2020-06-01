<?php

namespace Magechamp\CustomizeInvoice\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Indur\SprintObjectives\Setup
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();
        if ($connection->tableColumnExists('sales_invoice', 'linked_shipping_id') === false) {
            $connection
            ->addColumn(
                $setup->getTable('sales_invoice'),
                'linked_shipping_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'default' => 0,
                    'unsigned' => true,
                    'nullable' => true,
                    'comment' => 'linked_shipping_id'
                ]
            );
        }
        $installer->endSetup();
    }
}
