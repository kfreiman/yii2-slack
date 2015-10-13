<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace kfreiman\SlackTarget;
use yii\log\Target;
use FullyBaked\Pslackr\Pslackr;
use FullyBaked\Pslackr\Messages\CustomMessage;

/**
 * SyslogTarget writes log to syslog.
 *
 * @author miramir <gmiramir@gmail.com>
 * @since 2.0
 */
class SlackTarget extends Target
{
    /**
     * @var string slack webhook url
     */
    public $url;


    /**
     * Writes log messages to syslog
     */
    public function export()
    {
        $slack = new FullyBaked\Pslackr\Pslackr($this->url);
        $message = new FullyBaked\Pslackr\Messages\CustomMessage($message->text);

        $slack->send($message);
    }

    /**
     * @inheritdoc
     */
    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof \Exception) {
                $text = (string) $text;
            } else {
                $text = VarDumper::export($text);
            }
        }
        $prefix = $this->getMessagePrefix($message);
        return "{$prefix}[$level][$category] $text";
    }
}
