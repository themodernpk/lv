<?php


class SettingsTableSeeder extends Seeder
{

	public function run()
	{
		$items = [

			[   'key' => 'login-attemps',
				'type' => 'text',
				'label' => 'Number of login attempts',
				'value' => 3,
				'created_at' => Dates::now(),
				'updated_at' => Dates::now(),
			],
			[   'key' => 'app-name',
				'type' => 'text',
				'label' => 'Application Name',
				'value' => 'WebReinvent App Framework',
				'created_at' => Dates::now(),
				'updated_at' => Dates::now(),
			],
			[   'key' => 'item-per-page',
				'type' => 'text',
				'label' => 'Show paginaion after number of items',
				'value' => 10,
				'created_at' => Dates::now(),
				'updated_at' => Dates::now(),
			],

		];

		DB::table('settings')->insert($items);

	}

}
