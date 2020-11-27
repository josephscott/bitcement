# BitCement

# Quick Start

```shell
composer require josephscott/bitcement
cp -r vendor/josephscott/bitcement/quickstart/* .
chgrp -R www-data cache/
chmod -R 775 cache/
php -S localhost:9999 -t public
```

To get the very latest, use this first line instead:

```shell
composer require --dev josephscott/bitcement:dev-trunk
```
