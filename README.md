# abraovic/phpush

## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. Run the following command to install the package and add it as a requirement to your project's `composer.json`:

```bash
composer require abraovic/phpush
```

## Examples

You can find more examples under examples section of this lib

```php
<?php
require 'vendor/autoload.php';

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

try {

    $type = PHPush\Push\Push::IOS;
    $credentials = [
        'device_token' => 'fake-token', // for multiple use an array 'device_token' => ['fake-token-1', 'fake-token-2', ...]
        'certificate_path' => 'fake-cert',
        'certificate_phrase' => '',
        'dev' => true
    ];

    // enable printing payload before send (for development purpose)
    PHPush\Push\Push::$printPayload = true;

    $push = new PHPush\Push\Push($type, $credentials);
    $message = new PHPush\Push\Message($type, "Hello");
    $message->setBadge(200);
    $message->setBody('body');
    $iosMsg = $message->getMessage();
    $iosMsg->setSound('default');

    if ($push->sendMessage($message)) {
        echo "sent";
    }
} catch (PHPushException $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
```

## Docker

This lib includes docker environment with all extensions installed so you can try it. In order to use it run:
```bash
docker build -t abraovic/phpush -f .docker/Dockerfile .
docker-compose up -d

## after docker in up and running open the container go to /opt and run composer install/update
docker exec -it phpush-test /bin/bash
cd /opt
composer install
```

## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details.


## Copyright and license

The abraovic/phpush library is copyright © [Ante Braovic](http://antebraovic.me) and licensed for use under the MIT License.

[packagist]: https://packagist.org/packages/abraovic/phpush
[composer]: http://getcomposer.org/
[contributing]: https://github.com/abraovic/phpush/blob/master/CONTRIBUTORS.md