<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskonLevel extends Model
{
    protected $table = 'diskon_level';

    protected $fillable = [
        'level_member',
        'persentase_diskon',
        'diskon_manual_aktif',
        'nominal_diskon_manual',
    ];

    protected $casts = [
        'diskon_manual_aktif' => 'boolean',
        'persentase_diskon'   => 'float',
        'nominal_diskon_manual' => 'float',
    ];

    /**
     * Ambil nilai diskon aktif untuk level ini.
     * Jika diskon manual aktif, pakai nominal_diskon_manual.
     * Jika tidak, pakai persentase_diskon.
     */
    public function getDiskonAktif(): float
    {
        if ($this->diskon_manual_aktif && $this->nominal_diskon_manual > 0) {
            return $this->nominal_diskon_manual;
        }
        return $this->persentase_diskon;
    }
}
