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

`$conn->me` 結構請參考 [Telegram API getMe]

發送訊息
```php
use Rde\Telegram\Structure;

$payload = new Structure(array(
    'chat_id' => $int
));

$payload->{'text'} = 'some text string';

$telegram_response = $conn->sendMessage($payload);

```


### cli tool

發送訊息
```sh
$ ./send <token> <chat_id> 'your text'
```

bot api 轉發
```sh
$ ./bot <token> <method> <payload>
```

[Telegram API getMe]:https://core.telegram.org/bots/api#getme