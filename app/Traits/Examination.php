<?php

namespace App\Traits;

use App\Models\Cbt;
use App\Models\Submisi;
use Carbon\Carbon;
use Arr;

trait Examination
{
    // check if current page is answered and not sure is true
    public function getNotSureStatus()
    {
        return in_array($this->page, $this->submisi->nomor_urut_jawaban_ragu);
    }

    public function calculateSisaWaktu(Cbt $cbt, Submisi $submisi)
    {
        $waktu_berjalan = Carbon::now()->format('U') - $submisi->start_at->format('U');
        return ($cbt->durasi * 60) - $waktu_berjalan;
    }

    // finish and lock submisi
    public function finish($user)
    {
        $cbt = Cbt::where('jenjang', $user->jenjang)->where('archived', '!=', 1)->first();

        $submisi = Submisi::where('cbt_id', $cbt->id)->where('user_id', $user->id)->first();

        $kunci_jawaban = $cbt->soal->pluck('jawaban', 'urut');
        $jawaban = [];

        foreach ($submisi->nomor_urut_teracak as $key => $num) {
            $jawaban[$num] = Arr::get($submisi->jawaban, $key);
        }

        $nomor_jawaban_benar = [];
        $nomor_jawaban_salah = [];

        foreach ($kunci_jawaban as $key => $value) {
            if (Arr::get($jawaban, $key) == $value) {
                array_push($nomor_jawaban_benar, $key);
            } else {
                array_push($nomor_jawaban_salah, $key);
            }
        }

        $nilai = (100 / $submisi->cbt->soal->where('jenis', 'pg')->count()) * count($nomor_jawaban_benar);

        // update
        $submisi->nomor_urut_jawaban_benar = $nomor_jawaban_benar;
        $submisi->nomor_urut_jawaban_salah = $nomor_jawaban_salah;
        $submisi->nilai = $nilai;
        $submisi->sisa_waktu = $this->calculateSisaWaktu($cbt, $submisi);
        $submisi->finish_at = Carbon::now();
        $submisi->locked = true;

        if (! $cbt->soal->where('jenis', 'es')->count()) {
            $submisi->score_locked = true;
        }

        $submisi->save();

        return $submisi;
    }
}
