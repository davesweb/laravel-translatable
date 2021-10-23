<?php

namespace Davesweb\LaravelTranslatable\Tests;

use Davesweb\LaravelTranslatable\Tests\Models\TestModel;
use Davesweb\LaravelTranslatable\Models\TranslationModel;
use Davesweb\LaravelTranslatable\Tests\Models\TestModelTranslation;

/**
 * @internal
 * @coversNothing
 */
class ModelTest extends TestCase
{
    public function testItReturnsCorrectTranslationObject()
    {
        /** @var TestModel $testModel */
        $testModel    = TestModel::query()->findOrFail(1);
        $translations = $testModel->getTranslations();

        $this->assertCount(2, $translations);

        $translations->each(function (TranslationModel $translation) {
            $this->assertInstanceOf(TestModelTranslation::class, $translation);
        });
    }

    public function testItReturnsTranslationsInRequestedLocale()
    {
        /** @var TestModel $testModel */
        $testModel   = TestModel::query()->findOrFail(1);
        $translation = $testModel->getTranslation('nl');

        $this->assertInstanceOf(TestModelTranslation::class, $translation);
        $this->assertEquals('nl', $translation->locale);
    }

    public function testItReturnsNullForUnknownLocale()
    {
        /** @var TestModel $testModel */
        $testModel   = TestModel::query()->findOrFail(1);
        $translation = $testModel->getTranslation('xx');

        $this->assertNull($translation);
    }

    public function testItReturnsTranslationInCurrentLocale()
    {
        $this->app->setLocale('nl');

        /** @var TestModel $testModel */
        $testModel   = TestModel::query()->findOrFail(1);
        $translation = $testModel->translation();

        $this->assertInstanceOf(TestModelTranslation::class, $translation);
        $this->assertEquals('nl', $translation->locale);
    }

    public function testItReturnsNullIfCurrentLocaleIsInvalid()
    {
        $this->app->setLocale('xx');

        /** @var TestModel $testModel */
        $testModel   = TestModel::query()->findOrFail(1);
        $translation = $testModel->translation();

        $this->assertNull($translation);
    }

    public function testItDoesNotTranslateNonTranslatableAttribute()
    {
        /** @var TestModel $testModel */
        $testModel = TestModel::query()->findOrFail(1);

        $value = $testModel->translate('untranslated');

        $this->assertNull($value);
    }

    public function testItTranslatesAttributeInCurrentLocale()
    {
        $this->app->setLocale('nl');

        /** @var TestModel $testModel */
        $testModel = TestModel::query()->findOrFail(1);

        $this->assertEquals('Nederlandse naam', $testModel->translate('name'));
        $this->assertEquals('Nederlandse inhoud', $testModel->translate('content'));

        $this->app->setLocale('en');

        $this->assertEquals('English name', $testModel->translate('name'));
        $this->assertEquals('English content', $testModel->translate('content'));
    }

    public function testItTranslatesAttributeInRequestedLocale()
    {
        $this->app->setLocale('nl');

        /** @var TestModel $testModel */
        $testModel = TestModel::query()->findOrFail(1);

        $name = $testModel->getTranslation('en')->name;
        $content = $testModel->getTranslation('en')->content;

        $this->assertEquals('English name', $name);
        $this->assertEquals('English content', $content);
    }
}
