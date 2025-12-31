<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HadithApi
{
    public string $baseUrl = 'https://hadith-api-go.vercel.app';

    public function books(): array
    {
        $res = Http::timeout(15)->get($this->baseUrl . '/api/books');
        return $res->ok() ? ($res->json() ?? []) : [];
    }

    public function hadithByNumber(string $bookSlug, int $number): array
    {
        $res = Http::timeout(15)->get($this->baseUrl . "/api/hadith/{$bookSlug}/{$number}");
        return $res->ok() ? ($res->json() ?? []) : [];
    }
}
