<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'poster',
        'kuota', // ✅ tambah
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'kuota' => 'integer', // ✅ tambah
    ];

    protected $appends = [
        'google_calendar_url',
        'tanggal_mulai_label',
        'kuota_label',
        'sisa_kuota',
        'is_penuh',
    ];

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(PendaftaranKegiatan::class);
    }

    public function getTanggalMulaiLabelAttribute(): string
    {
        if (!$this->tanggal_mulai) return '-';
        return $this->tanggal_mulai->locale('id')->translatedFormat('l, d M Y • H:i') . ' WITA';
    }

    public function getKuotaLabelAttribute(): string
    {
        return $this->kuota ? number_format($this->kuota) . ' orang' : 'Terbuka';
    }

    public function getSisaKuotaAttribute(): ?int
    {
        if (!$this->kuota) return null;

        // pakai pendaftarans_count kalau sudah di-load withCount
        $terdaftar = isset($this->pendaftarans_count)
            ? (int) $this->pendaftarans_count
            : (int) $this->pendaftarans()->count();

        return max(0, $this->kuota - $terdaftar);
    }

    public function getIsPenuhAttribute(): bool
    {
        if (!$this->kuota) return false;

        $terdaftar = isset($this->pendaftarans_count)
            ? (int) $this->pendaftarans_count
            : (int) $this->pendaftarans()->count();

        return $terdaftar >= $this->kuota;
    }

    public function getGoogleCalendarUrlAttribute(): ?string
    {
        try {
            if (!$this->tanggal_mulai) return null;

            $start = $this->tanggal_mulai->copy()->utc();
            $end = $this->tanggal_selesai
                ? $this->tanggal_selesai->copy()->utc()
                : $start->copy()->addHours(2);

            $fmt = fn ($d) => $d->format('Ymd\THis\Z');

            return 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                . '&text=' . urlencode($this->nama_kegiatan ?? 'Kegiatan Masjid')
                . '&dates=' . $fmt($start) . '/' . $fmt($end)
                . '&details=' . urlencode(trim(strip_tags((string)($this->deskripsi ?? ''))))
                . '&location=' . urlencode((string)($this->lokasi ?? ''));
        } catch (\Throwable $e) {
            return null;
        }
    }
}
