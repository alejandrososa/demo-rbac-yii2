<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{

    public $tbl_usuarios;
    public $tbl_autorizacion;

    const TIPO_RESTRICT     = 'RESTRICT';
    const TIPO_CASCADE      = 'CASCADE';
    const TIPO_NO_ACTION    = 'NO ACTION';
    const TIPO_SET_DEFAULT  = 'SET DEFAULT';
    const TIPO_SET_NULL     = 'SET NULL';

    public function init()
    {
        parent::init();

        $this->tbl_usuarios = 'user';
        $this->tbl_autorizacion = 'authToken';
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tbl_usuarios, [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable($this->tbl_autorizacion, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull()->unique(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // index columna `categorias`
        $this->createIndex(
            'idx-' . $this->tbl_autorizacion . '-user',
            $this->tbl_autorizacion,
            'user_id'
        );
        //clave foranea a `usuarios`
        $this->addForeignKey(
            'fk-' . $this->tbl_autorizacion . '-user',
            $this->tbl_autorizacion,
            'user_id',
            $this->tbl_usuarios,
            'id',
            self::TIPO_CASCADE,
            self::TIPO_NO_ACTION
        );
    }

    public function down()
    {
        $this->dropTable($this->tbl_autorizacion);
        $this->dropTable($this->tbl_usuarios);
    }
}
