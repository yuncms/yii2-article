<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\article\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Overtrue\Pinyin\Pinyin;

/**
 * Class Category
 * @property int $id ID
 * @property integer $parent 父ID
 * @property string $name 标题
 * @property string $keywords 关键词
 * @property string $description 描述
 * @property string $pinyin 拼音
 * @property string $letter 首字母
 * @property int $frequency 热度
 * @package yuncms\article\models
 */
class Category extends ActiveRecord
{
    /**
     * @var string 父栏目名称
     */
    public $parent_name;

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['slug'], 'string', 'max' => 20],
            [['letter'], 'string', 'max' => 1],
            [['keywords', 'pinyin'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],

            [['parent_name'], 'in',
                'range' => static::find()->select(['name'])->column(),
                'message' => 'Category "{value}" not found.'],
            [['parent', 'sort', 'slug'], 'default'],
            [['parent'], 'filterParent', 'when' => function () {
                return !$this->isNewRecord;
            }],
            [['sort', 'frequency'], 'integer'],
            ['sort', 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('article', 'ID'),
            'name' => Yii::t('article', 'Category Name'),
            'parent' => Yii::t('article', 'Parent Category'),
            'slug' => Yii::t('article', 'Category Slug'),
            'keywords' => Yii::t('article', 'Category Keywords'),
            'description' => Yii::t('article', 'Category Description'),
            'pinyin' => Yii::t('article', 'Pinyin'),
            'letter' => Yii::t('article', 'Letter'),
            'frequency' => Yii::t('article', 'Frequency'),
            'sort' => Yii::t('article', 'Sort'),
            'allow_publish' => Yii::t('article', 'Allow Publish'),
            'parent_name' => Yii::t('article', 'Parent Category'),
            'created_at' => Yii::t('article', 'Created At'),
            'updated_at' => Yii::t('article', 'Updated At'),
        ];
    }

    /**
     * 获取父栏目
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent']);
    }

    /**
     * 获取子栏目
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(static::className(), ['parent' => 'id']);
    }

    /**
     * Use to loop detected.
     */
    public function filterParent()
    {
        $parent = $this->parent;
        $db = static::getDb();
        $query = (new Query)->select(['parent'])
            ->from(static::tableName())
            ->where('[[id]]=:id');
        while ($parent) {
            if ($this->id == $parent) {
                $this->addError('parent_name', Yii::t('article', 'Loop detected.'));
                return;
            }
            $parent = $query->params([':id' => $parent])->scalar($db);
        }
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (empty($this->pinyin)) {
            $py = new Pinyin();
            $this->pinyin = strtolower($py->permalink($this->name, ''));
        }
        if (empty($this->letter)) {
            $this->letter = strtoupper(substr($this->pinyin, 0, 1));
        }
        return parent::beforeSave($insert);
    }
}