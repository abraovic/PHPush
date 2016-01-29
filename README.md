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
        'device_token' => 'fake-token',
        'certificate_path' => 'fake-cert-path',
        'certificate_phrase' => 'fake-cert-phrase',
        'dev' => true
    ];
    
    $push = new PHPush\Push\Push($type, $credentials);
    $message = new PHPush\iOS\Message();
    $message->setAlert("This will be shown");
    $message->setBadge(2);
    $push->sendMessage($message);

} catch (PHPushException $e) {

    echo 'Caught exception: ' . $e->getMessage() . "\n";

}
```

## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details.


## Copyright and license

The abraovic/phpush library is copyright © [Ante Braovic](http://antebraovic.me) and licensed for use under the Apache2 License.

[packagist]: https://packagist.org/packages/abraovic/phpush
[composer]: http://getcomposer.org/
[contributing]: https://github.com/abraovic/phpush/blob/master/CONTRIBUTORS.md