<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PrayerTimesService
{
    public function getDaily(?string $cityId = null, ?Carbon $date = null): array
    {
        // ✅ Pakai timezone aplikasi (set ke Asia/Makassar untuk Bali)
        $tz = (string) config('app.timezone', 'Asia/Makassar');

        $cityId = $cityId ?: (string) config('services.myquran.city_id');
        $date   = $date ? $date->copy()->timezone($tz) : Carbon::now($tz);

        // ✅ myQuran v2 support yyyy-mm-dd
        $datePath = $date->format('Y-m-d');

        $base = rtrim((string) config('services.myquran.base_url'), '/');
        $url  = "{$base}/sholat/jadwal/{$cityId}/{$datePath}";

        $cacheKey = "sholat:myquran:{$cityId}:{$datePath}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($url) {
            $res = Http::timeout(10)->retry(2, 250)->get($url);

            if (!$res->ok()) {
                return [
                    'ok' => false,
                    'error' => 'HTTP ' . $res->status(),
                    'url' => $url,
                ];
            }

            $json = $res->json();

            $data   = data_get($json, 'data', []);
            $lokasi = (string) data_get($data, 'lokasi', '');
            $daerah = (string) data_get($data, 'daerah', '');
            $jadwal = (array) data_get($data, 'jadwal', []);

            $mapped = [
                ['name' => 'Subuh',   'time' => (string) data_get($jadwal, 'subuh', '')],
                ['name' => 'Dzuhur',  'time' => (string) data_get($jadwal, 'dzuhur', '')],
                ['name' => 'Ashar',   'time' => (string) data_get($jadwal, 'ashar', '')],
                ['name' => 'Maghrib', 'time' => (string) data_get($jadwal, 'maghrib', '')],
                ['name' => 'Isya',    'time' => (string) data_get($jadwal, 'isya', '')],
            ];

            $mapped = array_values(array_filter($mapped, fn ($x) => !empty($x['time'])));

            return [
                'ok' => true,
                'lokasi' => $lokasi,
                'daerah' => $daerah,
                'times' => $mapped,
                'url' => $url,
            ];
        });
    }
}
