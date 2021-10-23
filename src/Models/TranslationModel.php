<?php

namespace Davesweb\LaravelTranslatable\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Model $translatesModel
 */
abstract class TranslationModel extends Model
{
    public function translatesModel(): BelongsTo
    {
        return $this->belongsTo(
            $this->getTranslatesClassname(),
            $this->getTranslatesOwnerKey(),
            $this->primaryKey
        );
    }

    public function getTranslatesModel(): Model
    {
        return $this->translatesModel;
    }

    private function getTranslatesObject(): Model
    {
        return resolve($this->getTranslatesClassname());
    }

    private function getTranslatesOwnerKey(): string
    {
        return Str::of($this->getTranslatesClassname())->classBasename()->snake() . '_' . $this->getTranslatesObject()->primaryKey;
    }

    private function getTranslatesClassname(): string
    {
        if (property_exists($this, 'translates')) {
            return $this->translates;
        }

        return (string) Str::of(static::class)->replaceLast('Translation', '');
    }
}
