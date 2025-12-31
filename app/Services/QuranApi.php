<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class QuranApi
{
    // sesuaikan jika base URL repo kamu beda
    public string $baseUrl = 'https://al-quran-indonesia-api.vercel.app';

    public function surahList(): array
    {
        $res = Http::timeout(15)->get($this->baseUrl . '/surah');
        return $res->ok() ? ($res->json() ?? []) : [];
    }

    public function surahDetail(int $nomor): array
    {
        $res = Http::timeout(15)->get($this->baseUrl . '/surah/' . $nomor);
        return $res->ok() ? ($res->json() ?? []) : [];
    }
}
