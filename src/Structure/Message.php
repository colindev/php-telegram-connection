<?php namespace Rde\Telegram\Structure;

use Rde\Telegram\Structure;

class Message extends Structure
{
    /** @var int */
    public $chat_id;

    /** @var string */
    public $text;

    /** @var boolean */
    public $disable_web_page_preview;

    /** @var int */
    public $reply_to_message_id;

    public $reply_markup;

    public function __construct($chat_id)
    {
        $this->chat_id = (int) $chat_id;
    }
}
