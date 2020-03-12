<?php
namespace fgh151\yii2\s3upload;

use yii\base\Behavior;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 *
 * @property string $uploadName
 *
 * @property Model $owner
 */
class S3UploadBehavior extends Behavior
{
    /** @var string Attribute with file upload */
    public $attribute;
    /** @var string Attribute to save path */
    public $storageAttribute;
    /** @var string custom pahh in backet */
    public $path = '';
    /** @var string */
    private $_key;
    /** @var UploadedFile */
    private $_file;

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'upload',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'upload'
        ];
    }

    public function upload()
    {
        $this->_file = UploadedFile::getInstanceByName($this->getUploadName());
        if (($this->_file !== null) && is_uploaded_file($this->_file->tempName)) {
            /** @var S3Storage $storage */
            $storage = \Yii::$app->storage;
            $r = $storage->client->putObject([
                'Bucket' => $storage->bucket,
                'Key' => $this->getBucketKey(),
                'Body' => fopen($this->_file->tempName, 'r')
            ]);
            $this->owner->{$this->storageAttribute} = $r->get("ObjectURL");
        }
    }

    /**
     * @return string
     */
    private function getUploadName()
    {
        return  Html::getInputName($this->owner, $this->attribute);
    }

    /**
     * @return string
     */
    private function getBucketKey()
    {
        if ($this->_key === null) {
            $this->_key = $this->path . '/' . $this->_file->baseName . '_' . time() . '.' . $this->_file->extension;
        }
        return $this->_key;
    }
}