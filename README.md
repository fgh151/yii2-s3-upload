S3 upload
=========
S3 upload extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist fgh151/yii2-s3-upload "*"
```

or add

```
"fgh151/yii2-s3-upload": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Add component to config file  :

```php
<?= 
'components' => [
    'storage' => [
        'class' => fgh151\yii2\s3upload\S3Storage::class,
        'key' => 's3-api-key',
        'secret' => 's3-api-secret',
        'bucket' => 'bucket-name'
        //You may also change region, provider, etc
    ],
] ?>
```

Your form model:

```php
class FormModel extends \yii\db\ActiveRecord
{
    public $uploadImage;
    public $pathToImage;

    public function rules()
    {
        return [
            ['uploadImage', 'file', 'extensions' => ['png', 'jpg', 'jpeg']],
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => fgh151\yii2\s3upload\S3UploadBehavior::class, //Behavior class
                'attribute' => 'uploadImage',
                'storageAttribute' => 'pathToImage', //Entity indefier in mapping clas
            ],
        ];
    }
    
    public function afterSave($insert,$changedAttributes){
        parent::afterSave($insert,$changedAttributes);
        if ($this->pathToImage !== null) {
            //TODO: save $this->pathToImage
        }
    }
}
```

Form field example:
```php
<?= $form->field($model, 'uploadImage')->fileInput() ?>
```