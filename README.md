# rde/php-telegram-connection

簡化 telegram api 請求

---

### 建立連線

```php

use Rde\Telegram\Connection;

// 成功建立連線後, $comm->me 會存放此 bot 的 id 資料 ([Telegram API getMe])
$conn = new Connection($token);

```

[Telegram API getMe]:https://core.telegram.org/bots/api#getme