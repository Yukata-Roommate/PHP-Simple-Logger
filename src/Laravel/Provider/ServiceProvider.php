<?php

namespace YukataRm\Laravel\SimpleLogger\Provider;

use Illuminate\Support\ServiceProvider as Provider;

use YukataRm\Laravel\SimpleLogger\Facade\Manager;
use YukataRm\Laravel\SimpleLogger\Facade\Logger;

/**
 * ServiceProvider
 * Facadeの登録とパッケージに含まれるファイルの公開の設定を行う
 * 
 * @package YukataRm\Laravel\SimpleLogger\Provider
 */
class ServiceProvider extends Provider
{
    /**
     * publications配下を公開する際に使うルートパス
     *
     * @var string
     */
    private string $publicationsPath = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "publications";

    /**
     * アプリケーションの起動時に実行する
     * FacadeとManagerの紐づけを行う
     * 
     * @return void
     */
    public function register(): void
    {
        // FacadeとManagerの紐づけ
        $this->app->singleton(Logger::class, function () {
            return new Manager();
        });
    }

    /**
     * アプリケーションのブート時に実行する
     * パッケージに含まれるファイルの公開の設定を行う
     * 
     * @return void
     */
    public function boot(): void
    {
        // config配下の公開
        // 自作パッケージ共通タグ
        $this->publishes([
            $this->publicationsPath . DIRECTORY_SEPARATOR . "config" => config_path(),
        ], "publications");

        // このパッケージのみ
        $this->publishes([
            $this->publicationsPath . DIRECTORY_SEPARATOR . "config" => config_path(),
        ], "simple-logger");
    }
}
