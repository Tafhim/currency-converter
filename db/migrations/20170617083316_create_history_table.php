<?php

use Phinx\Migration\AbstractMigration;

class CreateHistoryTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

    }

    public function up() {
        $history = $this->table('history');
        $history->addIndex('id', 'integer', array('limit' => 255))
                ->addColumn('from', 'char', array('limit' => 10))
                ->addColumn('to', 'char', array('limit' => 10))
                ->addColumn('amount', 'text')
                ->addColumn('to', 'text')
                ->addColumn('created', 'datetime')
                ->save();
    }

    public function down() {
        $this->dropTable('history');
    }
}
