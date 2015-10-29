# rde/php-telegram-connection

簡化 telegram api 請求

---

### 用法

建立連線
```php

use Rde\Telegram\Connection;

// 成功建立連線後, $conn->me 會存放此 bot 的 id 資料
$conn = new Connection($token);

```
`$token` 取得方式請參考 [Telegram Bot Token]
`$conn->me` 結構請參考 [Telegram Bot API getMe]

發送訊息
```php
use Rde\Telegram\Structure;

$payload = new Structure(array(
    'chat_id' => $int
));

$payload->{'text'} = 'some text string';

$telegram_response = $conn->sendMessage($payload);

```
使用 post
```php
$conn->method('POST');
//$conn->sendMessage($payload);
```

### cli tool

bot api 轉發
```sh
$ ./bot <token|bot_name> <method> [payload]
```

使用 post
```sh
$ METHOD=POST ./bot <token|bot_name> <method> [payload]
```

發送訊息
```sh
$ ./send <token|bot_name> <chat_id> 'your text'
```

[Telegram Bot Token]:https://core.telegram.org/bots/api#authorizing-your-bot
[Telegram Bot API getMe]:https://core.telegram.org/bots/api#getme
