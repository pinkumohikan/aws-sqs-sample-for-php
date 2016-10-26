<?php

namespace App\Queue;

use Aws\Sqs\SqsClient;

class Handler
{
    const DEFAULT_REGION = 'ap-northeast-1';

    private $sqsClient;
    private $queueUrl;

    public function __construct($queueName, $region = self::DEFAULT_REGION)
    {
        $this->sqsClient = SqsClient::factory(array(
            'credentials' => array(
                'key'    => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY')
            ),
            'region' => $region
        ));
        $queueUrlObject = $this->sqsClient->getQueueUrl(array('QueueName' => $queueName));
        $this->queueUrl = $queueUrlObject['QueueUrl'];
    }

    public function push($messageBody, $delaySeconds = 0)
    {
        return $this->sqsClient->sendMessage(array(
            'QueueUrl'     => $this->queueUrl,
            'MessageBody'  => $messageBody,
            'DelaySeconds' => $delaySeconds
        ));
    }

    public function pop($maxMessages = 1)
    {
        $response = $this->sqsClient->receiveMessage(array(
            'QueueUrl'            => $this->queueUrl,
            'MaxNumberOfMessages' => $maxMessages
        ));

        $messages = array();
        foreach ((array) $response->getPath('Messages/*') as $propertyName => $values) {
            // 取得件数: 2件以上
            if (is_array($values)) {
                foreach ($values as $recordNumber => $value) {
                    $messages[$recordNumber][$propertyName] = $value;
                }

            // 取得件数: 1件
            } else {
                // 複数件取得時とデータ構造を合わせるために1層くする
                $messages[0][$propertyName] = $values;
            }
        }

        return $messages;
    }
}
