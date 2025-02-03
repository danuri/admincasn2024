<?php
namespace Config;

class Minio {
    public $endpoint;
    public $accessKey;
    public $secretKey;
    public $bucket;
    public function __construct() {
        $this->endpoint = getenv('MINIO_URL');
        $this->accessKey = getenv('MINIO_ACCESS_KEY');
        $this->secretKey = getenv('MINIO_SECRET_KEY');
        $this->bucket = getenv('MINIO_BUCKET');
    }
}