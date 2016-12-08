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


        //usuarios demo
        $this->batchInsert('{{%user}}',
            [
                'username',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'email',
                'status',
                'created_at',
                'updated_at'
            ],
            [
                //password 123456
                ['admin', '7ATWh6v7C3aoJAe-1J6EIlbHFOvOQkEl', '$2y$13$mAOVcVcoIydz5TvrbHwwWeOxjEwMGicl3MewYVANWDIMDQ.BAEPxW', NULL, 'admin@correo.com', 10, '1481143012','1481143012'],
                ['particular', 'QXdym_EVgnxq6S1cMa5MefXXRo4FCE1b', '$2y$13$BVFgCffxBjycc6ZoQIr8L.paJslyI1txSUNK02k8SrziRDB0/vCkS', NULL, 'particular@correo.com', 10, '1481143012','1481143012'],
                ['vendedor', 'pbHFQUoXoB6lURqZTF_NwNWH_zy3Wrc_', '$2y$13$XguulkXA.eIw1nqAnWCnBeULMLjobQUXhb7DnOSDBbhD5migt2Xyy', NULL, 'vendedor@correo.com', 10, '1481143012','1481143012'],
                ['empleado', 'XFaTWhB0Ao09_cy1h-LZdQMiEHrCaMYi', '$2y$13$qxMLnsJCZg/NApoGKiHFy.v1TT9sIlwKE6EfBVlCjk2ZCSF8gfRlO', NULL, 'empleado@correo.com', 10, '1481143012','1481143012'],
            ]);

        $this->batchInsert('{{%authToken}}',
            [
                'user_id',
                'token',
                'created_at',
            ],
            [
                //password 123456
                [1, 'l1STFriemVGnSXEGkpNpWcY5XnKGBmSs', '1481143012'],
                [2, 'I2LowN6s9m23JtVIY5KAojTu5-t2Nk91', '1481143012'],
                [3, 'tFgX3AKgWoTSJNApcRGf9FZDdiuLrSpA', '1481143012'],
                [4, '_RymZHzH9az5f-vrJmmoxOepuyhfT4yb', '1481143012'],
            ]);
    }

    public function down()
    {
        $this->dropTable($this->tbl_autorizacion);
        $this->dropTable($this->tbl_usuarios);
    }
}
