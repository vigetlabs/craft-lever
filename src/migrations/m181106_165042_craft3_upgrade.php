<?php

namespace viget\lever\migrations;

use Craft;
use craft\db\Migration;
use viget\lever\fields\LeverField;

/**
 * m181106_165042_craft3_upgrade migration.
 */
class m181106_165042_craft3_upgrade extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('{{%fields}}', [
            'type' => LeverField::class
        ], ['type' => 'Lever']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m181106_165042_craft3_upgrade cannot be reverted.\n";
        return false;
    }
}
