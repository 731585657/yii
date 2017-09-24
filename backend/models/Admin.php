<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{

    //定义一个常量路径方便指定场景
    const SCENARIO_ADD ='add';
    const SCENARIO_LOGIN ='login';
    const SCENARIO_EDIT ='edit';
    public $password;//明文密码
    public $rememberMe;
    public $permissions;
//    public $passwordy;
    public $passwords;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email','status','status'], 'required',],
            ['password','required','on'=>[self::SCENARIO_ADD,self::SCENARIO_LOGIN]],//on指定场景  该规则只在指定的场景下生效
            ['password','required','on'=>[self::SCENARIO_EDIT]],//on指定场景  该规则只在指定的场景下生效
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password'], 'string'],
            ['rememberMe','string'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            //['passwordy','string'],
            [['password_reset_token'], 'unique'],
            ['permissions','safe'],
        ];
    }

    //添加和修改
    public function beforeSave($insert){
        if($insert){
            //添加
            //密码加密
            $this->password_hash=Yii::$app->security->generatePasswordHash($this->password);
            //auth_key
            $this->auth_key=Yii::$app->security->generateRandomString();//随机字符串
            //保存时间
            $this->created_at=time();
        }else{
            //修改
            if($this->password){
                $this->password_hash=Yii::$app->security->generatePasswordHash($this->password);
                $this->auth_key=Yii::$app->security->generateRandomString();//随机字符串
                $this->updated_at=time();
            }
        }

        return parent::beforeSave($insert);
    }
        //关联角色
    public static function getPermissionItems(){
        $permissions=\Yii::$app->authManager->getRoles();
        //转换成表单相对应的格式  用数组来装
        $items=[];
        //遍历出所有的权限数据
        foreach ($permissions as $permission){
            $items[$permission->name]=$permission->description;
        }
        return $items;
    }

    //获取用户菜单
    public static function getMenus()
    {
//        return [
//            ['label'=>'用户管理','items'=>[
//                ['label'=>'添加用户','url'=>['admin/add']],
//                ['label'=>'添加用户','url'=>['admin/add']],
//            ]],
//        ];
        //定义一个数组来存放遍历出来的二级菜单
        $menuItems = [];
        $menus = Menu::find()->where(['parent_id' => 0])->all();
        //var_dump($menus);exit;
        //遍历出所有的以及菜单
        foreach ($menus as $menu) {
            //查找二级分类
            $children = Menu::find()->where(['parent_id' => $menu->id])->all();
            $items = [];
            //遍历二级分类
            foreach ($children as $child) {
                if (Yii::$app->user->can($child->route)) {
                    //var_dump(Yii::$app->user->can($child->route));exit;
                    $items[] = ['label' => $child->name, 'url' => ['admin\index']];

                }
                $menuItems[] = ['label' => $menu->name, 'items' => $items];
            }

        }
//        var_dump($menuItems);exit;
        return $menuItems;
    }





    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
            'password'=>'密码',
            'passwordy'=>'确认密码',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $authKey==$this->auth_key;
    }
}
