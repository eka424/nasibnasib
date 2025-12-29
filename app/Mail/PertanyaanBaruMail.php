<?php

namespace App\Mail;

use App\Models\PertanyaanUstadz;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PertanyaanBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PertanyaanUstadz $pertanyaan)
    {
    }

    public function build(): self
    {
        $kategoriLabel = ucfirst(str_replace('_', ' ', $this->pertanyaan->kategori ?? 'Umum'));

        return $this->subject('Pertanyaan baru - '.$kategoriLabel)
            ->view('emails.pertanyaan-baru', [
                'pertanyaan' => $this->pertanyaan,
                'kategoriLabel' => $kategoriLabel,
                'penanya' => $this->pertanyaan->penanya,
                'linkModerasi' => url('/admin/moderasi-pertanyaan'),
            ]);
    }
}
