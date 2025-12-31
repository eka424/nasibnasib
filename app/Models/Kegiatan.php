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
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    protected $appends = [
        'google_calendar_url',
        'tanggal_mulai_label',
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
