<?php
/**
 * Copyright © 2021 Overdose_Testimonials. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Overdose\Testimonials\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'overdose_testimonials'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('overdose_testimonials')
        )->addColumn(
            'testimonial_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Testimonial Id'
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Testimonial Title'
        )->addColumn(
            'message',
            Table::TYPE_TEXT,
            '50000',
            [ 'nullable' => false],
            'Testimonial Message'
        )->addColumn(
            'image',
            Table::TYPE_TEXT,
            255,
            [],
            'Testimonial Author Image'
        )->addColumn(
            'status',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => 1],
            'Is Active'
        )->setComment(
            'Overdose Testimonials Table'
        );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
