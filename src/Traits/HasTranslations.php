<?php

namespace Davesweb\LaravelTranslatable\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Davesweb\LaravelTranslatable\Models\TranslationModel;

trait HasTranslations
{
    protected array $translationsCache = [];

    public function translations(): HasMany
    {
        return $this->hasMany($this->translation, $this->getTranslationForeignKey(), $this->getTranslationObject()->primaryKey);
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function getTranslation(string $locale): ?TranslationModel
    {
        if (empty($this->translationsCache)) {
            foreach ($this->getTranslations() as $translation) {
                $this->translationsCache[$locale] = $translation;
            }
        }

        return $this->translationsCache[$locale] ?? null;
    }
    
    protected function getCurrentLocale(): string
    {
        return app()->getLocale();
    }
    
    private function getTranslationObject(): Model
    {
        return resolve($this->translation);
    }
    
    private function getTranslationForeignKey(): string
    {
        return Str::of(self::class)->classBasename()->snake() . '_' . $this->primaryKey;
    }
}
