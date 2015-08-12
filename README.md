# rde/php-telegram-connection

簡化 telegram api 請求

---

### 建立連線

```php

use Rde\Telegram\Connection;

// 成功建立連線後, $comm->me 會存放此 bot 的 id 資料
$conn = new Connection($token);

```

`$comm->me` 結構請參考 [Telegram API getMe]

### cli tool

發送訊息
```sh

$ ./send <token> <chat_id> 'your text'

```

bot api
```sh

$ ./bot <token> <method> <payload>

```

[Telegram API getMe]:https://core.telegram.org/bots/api#getme