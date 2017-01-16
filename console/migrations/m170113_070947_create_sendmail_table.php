<?php
use yii\db\Migration;

/**
 * Handles the creation of table `sendmail`.
 */
class m170113_070947_create_sendmail_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('sendmail', [
            'id' => $this->primaryKey(),
            'to_email' => $this->string()->notNull(),
            'from_email' => $this->string()->notNull(),
            'created_at' => $this->datetime()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('sendmail');
    }
}
