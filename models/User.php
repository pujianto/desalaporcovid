<?php

namespace app\models;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\table\UsersTable;

class User extends UsersTable implements IdentityInterface 
{

    const STATUS_DELETED = 30;
    const STATUS_ACTIVE = 10;
    const STATUS_SUSPENDED = 0;


    const LEVEL_POSKO = 'posko';
    const LEVEL_ADMIN = 'admin';
    const LEVEL_PENGGUNA = 'pengguna';

    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public function getIdPoskoToPoskoModel()
    {
        return $this->hasOne(PoskoModel::className(),['id'=>'user_id']);
    }

    public static function getPenggunaCount()
    {
        return self::find()->count();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // return $this->password === $password;
        return $this->password === md5($password);
    }
}
