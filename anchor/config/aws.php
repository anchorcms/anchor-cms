<?php

return array(
    'region' => 'AWS_REGION', // Region to connect to. See http://docs.aws.amazon.com/general/latest/gr/rande.html for a list of available regions. ex: us-east-1
    'accessKey' => 'YOUR_AWS_ACCESS_KEY', // AWS access key ID
    'secretKey' => 'YOUR_AWS_SECRET_KEY', // AWS secret access key
    'version' => 'AWS_VERSION', // The version of the webservice to utilize. ex: latest
    's3Uploader' => false, // Switch default file uploader to AWS S3, to enable s3 uploader set the value to TRUE
    's3' => array(
        'bucket' => 'YOUR_AWS_BUCKET_NAME', // Amazon S3 stores data as objects within buckets, set your S3 bucket name you want to store data into it
        'acl' => 'BUCKET_OBJECTS_ACLs', // Amazon S3 access control lists (ACLs) enable you to manage access to buckets and objects. see http://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#canned-acl for a list of available ACLs
    ),
);