<?php
namespace fgh151\yii2\s3upload;

use Aws\S3\S3Client;
use Aws\Sdk;
use yii\base\Component;

class S3Storage extends Component
{
    /** @var string S3 api key */
    public $key;
    /** @var string S3 api secret */
    public $secret;
    /** @var string S3 region */
    public $region = 'us-east-1';
    /** @var string S3 provider endpoind */
    public $endpoint = 'https://storage.yandexcloud.net';
    /** @var string S3 provider api version */
    public $version = 'latest';
    /** @var string Bucket name */
    public $bucket = '';

    /** @var S3Client */
    public $client;

    public function init()
    {
        $sharedConfig = [
            'credentials' => [
                'key'      => $this->key,
                'secret'   => $this->secret,
            ],
            'region'   => $this->region,
            'endpoint' => $this->endpoint,
            'version'  => $this->version,
        ];

        $sdk = new Sdk($sharedConfig);
        $this->client = $sdk->createS3();
        parent::init();
    }

}