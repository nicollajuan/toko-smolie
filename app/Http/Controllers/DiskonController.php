<?php

namespace App\Http\Controllers;

use App\Models\DiskonLevel;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    public function index()
    {
        $diskon = DiskonLevel::orderByRaw("FIELD(level_member, 'Bronze', 'Silver', 'Gold', 'Platinum')")->get();

        // Jika tabel masih kosong (belum migrate), buat data default
        if ($diskon->isEmpty()) {
            $defaults = [
                ['level_member' => 'Bronze',   'persentase_diskon' => 0,  'diskon_manual_aktif' => false, 'nominal_diskon_manual' => 0],
                ['level_member' => 'Silver',   'persentase_diskon' => 5,  'diskon_manual_aktif' => false, 'nominal_diskon_manual' => 0],
                ['level_member' => 'Gold',     'persentase_diskon' => 10, 'diskon_manual_aktif' => false, 'nominal_diskon_manual' => 0],
                ['level_member' => 'Platinum', 'persentase_diskon' => 15, 'diskon_manual_aktif' => false, 'nominal_diskon_manual' => 0],
            ];
            foreach ($defaults as $d) {
                DiskonLevel::create($d);
            }
            $diskon = DiskonLevel::orderByRaw("FIELD(level_member, 'Bronze', 'Silver', 'Gold', 'Platinum')")->get();
        }

        return view('admin.diskon', compact('diskon'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'diskon'                  => 'required|array',
            'diskon.*.persentase'     => 'required|numeric|min:0|max:100',
            'diskon.*.manual_aktif'   => 'nullable|boolean',
            'diskon.*.nominal_manual' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->diskon as $id => $data) {
            DiskonLevel::where('id', $id)->update([
                'persentase_diskon'     => $data['persentase'],
                'diskon_manual_aktif'   => isset($data['manual_aktif']) ? true : false,
                'nominal_diskon_manual' => $data['nominal_manual'],
            ]);
        }

        return redirect()->route('admin.diskon')->with('success', 'Pengaturan diskon berhasil disimpan.');
    }
}
