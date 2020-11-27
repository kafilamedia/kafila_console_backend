<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EndSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $cbt;
    public $submisi;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cbt, $submisi)
    {
        $this->cbt = $cbt;
        $this->submisi = $submisi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nilai = null;

        $soal = $this->cbt->soal;

        if ($soal->where('jenis', 'pg')->count()) {
            $kunci_jawaban = $soal->pluck('jawaban', 'urut');
            $jawaban = [];

            foreach ($this->submisi->nomor_urut_teracak as $key => $num) {
                $jawaban[$num] = array_get($this->submisi->jawaban, $key);
            }
            
            $nomor_jawaban_benar = [];
            $nomor_jawaban_salah = [];

            foreach ($kunci_jawaban as $key => $value) {
                if (array_get($jawaban, $key) == $value) {
                    array_push($nomor_jawaban_benar, $key);
                } else {
                    array_push($nomor_jawaban_salah, $key);
                }
            }

            // $nilai = (100 / $this->cbt->jumlah_soal) * count($nomor_jawaban_benar);
            $nilai = (100 / $this->cbt->soal->where('jenis', 'pg')->count()) * count($nomor_jawaban_benar);

            // update
            $this->submisi->nomor_urut_jawaban_benar = $nomor_jawaban_benar;
            $this->submisi->nomor_urut_jawaban_salah = $nomor_jawaban_salah;
        }

        // update
        $this->submisi->nilai = $nilai;
        $this->submisi->finish_at = now();
        $this->submisi->locked = true;

        // LOCK SCORE if ALL SOAL is PG (score_locked)
        if (is_null($this->cbt->bobot)) {
            $this->submisi->score_locked = true;
        }

        $this->submisi->save();
    }
}
