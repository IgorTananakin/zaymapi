<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $created_at
 * 
 * @property Loan[] $loans
 */
class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['name', 'password'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 255],
        ];
    }

    public function getLoans()
    {
        return $this->hasMany(Loan::class, ['user_id' => 'id']);
    }

    /**
     *проверка есть ли у пользователя одобренные займы
     */
    public function hasApprovedLoans()
    {
        return $this->getLoans()
            ->where(['status' => Loan::STATUS_APPROVED])
            ->exists();
    }

}