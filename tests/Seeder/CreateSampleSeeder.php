<?php
/**
 * アカウント作成テストデータ投入
 *
 * @author keita-nishimoto
 * @since 2016-11-07
 * @link https://github.com/keita-nishimoto/laravel-api-sample
 */

namespace Tests\Seeder;

use Factories\Account\ValueFactory;
use Illuminate\Database\Seeder;

/**
 * Class CreateTestSeeder
 *
 * @category laravel-api-sample
 * @package Tests\Domain\Service\Account
 * @author keita-nishimoto
 * @since 2016-11-07
 * @link https://github.com/keita-nishimoto/laravel-api-sample
 */
class CreateSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sample')->truncate();

        $this->testSample();
    }

    /**
     * 異常系テスト
     * メールアドレスが既に登録されている
     */
    private function testSample()
    {
        $sample= [
            'id'         => 1,
            'value'      => 'sample',
        ];
        \DB::table('sample')->insert($sample);
    }
}
