<?php

namespace Davesweb\LaravelTranslatable\Tests;

use Davesweb\LaravelTranslatable\Tests\Models\TestModel;
use Davesweb\LaravelTranslatable\Tests\Models\TestModelTranslation;

class TranslationModelTest extends TestCase
{
    public function testItReturnsTheCorrectTranslatesModel()
    {
        /** @var TestModelTranslation $translation */
        $translation = TestModelTranslation::query()->findOrFail(1);

        $translatesModel = $translation->getTranslatesModel();

        $this->assertInstanceOf(TestModel::class, $translatesModel);
    }
}