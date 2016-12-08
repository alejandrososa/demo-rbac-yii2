<?php

use yii\db\Migration;

/**
 * Handles the creation of table `facturas`.
 */
class m161207_141852_create_facturas_table extends Migration
{
    public $tbl_usuarios;
    public $tbl_facturas;

    const TIPO_RESTRICT     = 'RESTRICT';
    const TIPO_CASCADE      = 'CASCADE';
    const TIPO_NO_ACTION    = 'NO ACTION';
    const TIPO_SET_DEFAULT  = 'SET DEFAULT';
    const TIPO_SET_NULL     = 'SET NULL';

    public function init()
    {
        parent::init();

        $this->tbl_usuarios = 'user';
        $this->tbl_facturas = 'facturas';
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tbl_facturas, [
            'id' => $this->primaryKey(),
            'codigo' => $this->string(20)->unique(),
            'cantidad' => $this->integer()->defaultValue(1)->notNull(),
            'concepto' => $this->string(70)->notNull(),
            'descripcion' => $this->text(),
            'empleado_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // index columna `categorias`
        $this->createIndex(
            'idx-' . $this->tbl_facturas . '-empleado',
            $this->tbl_facturas,
            'empleado_id'
        );
        //clave foranea a `usuarios`
        $this->addForeignKey(
            'fk-' . $this->tbl_facturas . '-empleado',
            $this->tbl_facturas,
            'empleado_id',
            $this->tbl_usuarios,
            'id',
            self::TIPO_CASCADE,
            self::TIPO_NO_ACTION
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tbl_facturas);
    }
}
