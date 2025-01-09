<?php

namespace Tabatii\LocalMail;

use Closure;

class LocalMail
{
    protected bool | Closure $dev_mode = false;

    protected string | Closure $tailwind = 'https://cdn.tailwindcss.com?plugins=forms';

    protected array $css = [
        __DIR__.'/../dist/localmail.css',
    ];

    public function enableDevMode(bool | Closure $value = true): static
    {
        $this->dev_mode = $value;
        $this->setMailerConfig($value);
        return $this;
    }

    public function setMailerConfig(bool | Closure $value = true): static
    {
        if ($value && is_null(config('mail.mailers.localmail'))) {
            config(['mail.mailers.localmail' => ['transport' => 'localmail']]);
        }
        return $this;
    }

    public function css(): string
    {
        if ($this->dev_mode) {
            return "<script src='{$this->tailwind}'></script>".PHP_EOL;
        }
        return collect($this->css)->reduce(function ($carry, $css) {
            if (($contents = @file_get_contents($css)) === false) {
                throw new \RuntimeException("Unable to load LocalMail dashboard CSS path [{$css}].");
            }
            return $carry."<style>{$contents}</style>".PHP_EOL;
        }, '');
    }
}
