<?php

namespace Davesweb\LaravelTranslatable\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Davesweb\LaravelTranslatable\Models\TranslationModel;

/**
 * @property string                        $primaryKey
 * @property Collection|TranslationModel[] $translations
 *
 * @method HasMany hasMany(string $related, ?string $foreignKey = null, ?string $localKey = null)
 */
trait HasTranslations
{
    protected array $translationsCache = [];

    public function translations(): HasMany
    {
        return $this->hasMany(
            $this->getTranslationClassname(),
            $this->getTranslationForeignKey(),
            $this->getTranslationObject()->primaryKey
        );
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

    public function translate(string $key): mixed
    {
        $translation = $this->getTranslation($this->getCurrentLocale());

        if (null === $translation) {
            return null;
        }

        return $translation->{$key};
    }

    public function translation(): ?TranslationModel
    {
        return $this->getTranslation($this->getCurrentLocale());
    }

    protected function getCurrentLocale(): string
    {
        return app()->getLocale();
    }

    private function getTranslationObject(): Model
    {
        return resolve($this->getTranslationClassname());
    }

    private function getTranslationForeignKey(): string
    {
        return Str::of(self::class)->classBasename()->snake() . '_' . $this->primaryKey;
    }

    private function getTranslationClassname(): string
    {
        if (property_exists($this, 'translation')) {
            return $this->translation;
        }

        return static::class . 'Translation';
    }
}
