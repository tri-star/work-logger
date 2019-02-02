<?php
/**
 * アカウント作成テストクラス
 *
 * @author keita-nishimoto
 * @since 2016-11-07
 * @link https://github.com/keita-nishimoto/laravel-api-sample
 */

namespace Tests\Unit;

use App\Sample as Sample;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SampleTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        // Seederをfixtureとする
        Artisan::call('db:seed', ['--class' => 'Tests\Seeder\CreateSampleSeeder']);
        // Fixture生成機能
        factory(Sample::class)->create();
    }

    public function testSample()
    {
        $this->assertEquals('sample', Sample::getDataById(1)->value);
        $this->assertEquals(2, Sample::getDataById(2)->id, 'value is '.Sample::getDataById(2)->value);
    }
}
