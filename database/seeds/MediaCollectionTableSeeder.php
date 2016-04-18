<?php
use Illuminate\Database\Seeder;

class MediaCollectionTableSeeder extends Seeder{
	public function run() {
		DB::table('media_collections')->insert(['id' => '1', 'name'=>'User Images' ]);
		DB::table('media_collections')->insert(['id' => '2', 'name'=>'Content Images']);
	}
}