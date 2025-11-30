# local-otel-laravel

## このリポジトリについて

Zennの記事のサンプルが置いてあります。
ぜひ記事の方をご覧ください。

TODO ココニハル


## Laravelのサンプルアプリについて

Zennの記事に書いていない部分の補足をこちらに書きます。

これ以降の作業は全てlaravelコンテナ内で実施しています。

```console
docker compose run --service-ports laravel bash
```


### Laravelのインストール

Laravelのインストールは公式の通りに実施しました。
DBにMySQLを使う以外は適当に選んでいます。

https://readouble.com/laravel/12.x/ja/installation.html

```console
# Laravel installerを取得
root@397d1852481f:/opt/laravel# composer global require laravel/installer
Changed current directory to /root/.composer
./composer.json has been created
Running composer update laravel/installer
Loading composer repositories with package information
Updating dependencies
Lock file operations: 31 installs, 0 updates, 0 removals
  - Locking carbonphp/carbon-doctrine-types (3.2.0)
...
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 31 installs, 0 updates, 0 removals
  - Downloading doctrine/inflector (2.1.0)
...
21 package suggestions were added by new dependencies, use `composer suggest` to see details.
Generating autoload files
20 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
No security vulnerability advisories found.
Using version ^5.23 for laravel/installer


# Laravelアプリケーションを作成
root@397d1852481f:/opt/laravel# /root/.composer/vendor/laravel/installer/bin/laravel new example-app

   _                               _
  | |                             | |
  | |     __ _ _ __ __ ___   _____| |
  | |    / _` |  __/ _` \ \ / / _ \ |
  | |___| (_| | | | (_| |\ V /  __/ |
  |______\__,_|_|  \__,_| \_/ \___|_|


 ┌ Which starter kit would you like to install? ────────────────┐
 │ None                                                         │
 └──────────────────────────────────────────────────────────────┘

 ┌ Which testing framework do you prefer? ──────────────────────┐
 │ PHPUnit                                                      │
 └──────────────────────────────────────────────────────────────┘

 ┌ Do you want to install Laravel Boost to improve AI assisted coding? ┐
 │ No                                                                  │
 └─────────────────────────────────────────────────────────────────────┘

Creating a "laravel/laravel" project at "./example-app"
Installing laravel/laravel (v12.10.1)
  - Downloading laravel/laravel (v12.10.1)
  - Installing laravel/laravel (v12.10.1): Extracting archive
Created project in /opt/laravel/example-app
Loading composer repositories with package information
Updating dependencies
Lock file operations: 111 installs, 0 updates, 0 removals
  - Locking brick/math (0.14.1)
...
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 111 installs, 0 updates, 0 removals
  - Downloading doctrine/lexer (3.0.1)
...
67 package suggestions were added by new dependencies, use `composer suggest` to see details.
Generating optimized autoload files
81 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
No security vulnerability advisories found.
> @php -r "file_exists('.env') || copy('.env.example', '.env');"

   INFO  Application key set successfully.

 ┌ Which database will your application use? ───────────────────┐
 │ MySQL                                                        │
 └──────────────────────────────────────────────────────────────┘

 ┌ Default database updated. Would you like to run the default database migrations? ┐
 │ No                                                                               │
 └──────────────────────────────────────────────────────────────────────────────────┘

 ┌ Would you like to run npm install and npm run build? ────────┐
 │ No                                                           │
 └──────────────────────────────────────────────────────────────┘

   INFO  Application ready in [example-app]. You can start your local development using:

➜ cd example-app
➜ npm install && npm run build
➜ composer run dev

  New to Laravel? Check out our documentation. Build something amazing!

# exit...
```

この時点で一度動作確認(migrateなども流れる)を実施します。

```console
root@397d1852481f:/opt/laravel# ./entrypoint.sh
...

```

http://localhost:8000/ にアクセスしてLaravelページが表示されれば成功です。
確認できたら `Ctrl + C` でserverを閉じます。


### OpenTelemetry関連ライブラリのインストール

OpenTelemetry関連のライブラリを入れる前に注意があります。
`OTEL_SDK_DISABLED` や `OTEL_PHP_AUTOLOAD_ENABLED` など、環境変数でOTELが有効化されているとcomposerが落ちてしまうので明示してdisableしておきましょう。

```console
cd /opt/laravel/example-app/
export OTEL_SDK_DISABLED=true
export OTEL_PHP_AUTOLOAD_ENABLED=false
```

Laravelにおいては以下の3つが必須のようです。

```console
composer require \
    open-telemetry/exporter-otlp \
    open-telemetry/opentelemetry-auto-laravel \
    open-telemetry/sdk
```

Guzzle HttpやHttpファサードを使っているケースが多いと思うので、オプションで以下の2つも入れておきます。

```console
composer require \
    open-telemetry/opentelemetry-auto-guzzle \
    open-telemetry/opentelemetry-auto-psr18
```
