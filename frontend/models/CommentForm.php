<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\article\frontend\models;

use Yii;
use yii\base\Model;
use yuncms\article\models\Comment;

/**
 * Class CommentForm
 * @package yuncms\article\frontend\models
 */
class CommentForm extends Model
{
    /**
     * @var int
     */
    public $source_id;

    /**
     * @var string
     */
    public $content;

    private $_user;

    /**
     * @return \yii\web\IdentityInterface
     */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }

    /**
     * 保存评论
     */
    public function save()
    {
        if ($this->validate()) {
            $model = new Comment([
                'source_id' => $this->source_id,
                'user_id' => Yii::$app->user->id,
                'content' => $this->content,
            ]);
            return $model->save();
        }
        return false;
    }
}