<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('dummy-answers {userId}', function ($userId) {
	$user = App\Models\User::find($userId);
	$cbt = App\Models\Cbt::where('jenjang', $user->jenjang)->first();
	$submisi = App\Models\Submisi::where('user_id', $userId)->first();
	
	$kunci_jawaban = $cbt->soal->where('jenis','pg')->pluck('jawaban', 'urut');
	$jawaban = [];
	
	foreach ($submisi->nomor_urut_teracak as $key => $num) {
		$jawaban[$key] = Arr::get($kunci_jawaban, $num, 'lorem ipsum');
	}

	$submisi->update(['jawaban' => $jawaban]);

  $this->info('Dummy answers generated for user: ' . $userId);
})->describe('Generate dummy answers for examination');
