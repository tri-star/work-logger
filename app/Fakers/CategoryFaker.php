<?php
namespace WorkLogger\Fakers;

class CategoryFaker extends \Faker\Provider\Base
{
    public function categoryName()
    {
        $categories = [
            'カテゴリA',
            'カテゴリB',
            'カテゴリC',
            'カテゴリD',
            'カテゴリE',
            'カテゴリF',
        ];
        $key = array_rand($categories);
        return $categories[$key];
    }
}
