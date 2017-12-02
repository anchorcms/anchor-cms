<?php
use Aws\S3\S3Client;

/**
 * Created by PhpStorm.
 * User: abdulah
 * Date: 14/11/17
 * Time: 11:09
 */
class s3 extends uploader
{
    /**
     * @var S3Client $s3Client
     */
    protected $s3Client;
    /**
     * @var string $bucket
     */
    protected $bucket;
    /**
     * @var array $key
     */
    protected $key;

    /**
     * S3 constructor.
     * @param $accessKey
     * @param array $secretKey
     * @param $region
     * @param array $extensions
     * @param string $version
     */
    public function __construct($accessKey, $secretKey, $region, $extensions = array(), $version = 'latest')
    {
        // Instantiate an Amazon S3 client.
        $this->s3Client = new S3Client(
            array(
                'credentials' => array(
                    'key' => $accessKey,
                    'secret' => $secretKey,
                ),
                'region' => $region,
                'version' => $version,
            )
        );
        $this->set_extensions($extensions);
    }

    /**
     * @param $bucket
     * @param $file
     * @param $acl
     * @param null $filename
     * @return mixed
     */
    public function uploadToS3($bucket, $file, $acl, $filename = null)
    {
        // run validation on $_FILES input
        $this->validate($file);
        // create a nice filename
        if (is_null($filename)) {
            $filename = $this->get_filename($file['name']);
        }

        // Upload a publicly accessible file. The file size and type are determined by the SDK.
        try {
            return $this->s3Client->upload(
                $bucket,
                $filename,
                fopen($file['tmp_name'], 'rb'),
                $acl
            );
        } catch (Aws\S3\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
    }
}