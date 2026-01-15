<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $fillable = [
    'judul', 'deskripsi', 'url_file', 'gdrive_url', 'tipe', 'kategori', 'seksi',
];


    // Biar bisa dipanggil: $galeri->file_src
    protected $appends = ['file_src'];

    public function getFileSrcAttribute(): string
    {
        $url = (string) ($this->url_file ?? '');

        if ($url === '') return '';

        // 1) Google Drive: ubah jadi direct link
        if (str_contains($url, 'drive.google.com')) {
            $id = $this->extractDriveFileId($url);
            if ($id) {
                // direct view for image/video
                return "https://drive.google.com/uc?id={$id}";
            }
        }

        // 2) YouTube (untuk embed iframe biasanya beda, tapi src mentah tetap)
        return $url;
    }

    private function extractDriveFileId(string $url): ?string
    {
        // pola 1: /file/d/{id}/view
        if (preg_match('~/file/d/([^/]+)~', $url, $m)) {
            return $m[1];
        }

        // pola 2: open?id={id} atau uc?id={id}
        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $q);
            if (!empty($q['id'])) return $q['id'];
        }

        return null;
    }
}
