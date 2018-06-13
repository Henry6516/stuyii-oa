<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "auth_admin_role".
 *
 * @property int $id
 * @property int $roleId
 * @property string $store
 * @property string $plat
 */
class AuthAdminRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_admin_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['store', 'plat','role'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role' => 'Role',
            'store' => 'Store',
            'plat' => 'Plat',
        ];
    }
}
