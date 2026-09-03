<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::withCount('siswa');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tingkat', 'LIKE', "%{$search}%")
                  ->orWhere('jurusan', 'LIKE', "%{$search}%")
                  ->orWhere('rombel', 'LIKE', "%{$search}%");
            });
        }

        $kelas = $query->orderBy('tingkat')->orderBy('jurusan')->orderBy('rombel')->paginate(20);

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tingkat' => [
                'required', 'string', 'max:10',
                Rule::unique('kelas')->where(function ($q) use ($request) {
                    return $q->where('jurusan', $request->jurusan)
                             ->where('rombel', $request->rombel);
                }),
            ],
            'jurusan' => 'required|string|max:50',
            'rombel' => 'required|string|max:20',
        ], [
            'tingkat.unique' => 'Kelas dengan tingkat, jurusan, dan rombel yang sama sudah ada.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Kelas::create($request->only('tingkat', 'jurusan', 'rombel'));

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, int $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tingkat' => [
                'required', 'string', 'max:10',
                Rule::unique('kelas')->ignore($kelas->id_kelas, 'id_kelas')->where(function ($q) use ($request) {
                    return $q->where('jurusan', $request->jurusan)
                             ->where('rombel', $request->rombel);
                }),
            ],
            'jurusan' => 'required|string|max:50',
            'rombel' => 'required|string|max:20',
        ], [
            'tingkat.unique' => 'Kelas dengan tingkat, jurusan, dan rombel yang sama sudah ada.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $kelas->update($request->only('tingkat', 'jurusan', 'rombel'));

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(int $id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->siswa()->count() > 0) {
            return back()->with('error', 'Kelas tidak bisa dihapus karena masih ada siswa di dalamnya.');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}