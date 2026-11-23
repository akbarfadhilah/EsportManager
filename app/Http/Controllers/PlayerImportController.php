<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlayerImportController extends Controller
{
    private const FIELDS = [
        'nama_asli' => 'Real name',
        'nickname_game' => 'Nickname',
        'tanggal_lahir' => 'Birth date (DD/MM/YYYY)',
        'region' => 'Region',
        'role_utama' => 'Role utama 1',
        'role_cadangan_1' => 'Role cadangan 1',
        'role_cadangan_2' => 'Role cadangan 2',
        'gaya_permainan_1' => 'Gaya permainan 1',
        'gaya_permainan_2' => 'Gaya permainan 2',
        'targeting_urut_1' => 'Gaya targeting 1',
        'targeting_urut_2' => 'Gaya targeting 2',
        'targeting_urut_3' => 'Gaya targeting 3',
        'deskripsi_pemain' => 'Deskripsi',
    ];

    public function create()
    {
        return view('players.import', [
            'fields' => self::FIELDS,
            'importErrors' => [],
        ]);
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'headers' => ['required', 'string', 'max:20000'],
            'rows' => ['required', 'string', 'max:6000000'],
            'mapping' => ['required', 'string', 'max:20000'],
        ]);

        $headers = json_decode($validated['headers'], true);
        $rows = json_decode($validated['rows'], true);
        $mapping = json_decode($validated['mapping'], true);

        if (! is_array($headers) || ! array_is_list($headers)
            || ! is_array($rows) || ! array_is_list($rows) || ! is_array($mapping)
            || count($headers) < 1 || count($headers) > 100
            || count($rows) < 1 || count($rows) > 500) {
            throw ValidationException::withMessages([
                'file' => 'File harus memiliki header dan berisi antara 1 sampai 500 baris data.',
            ]);
        }

        $usedColumns = [];
        foreach (self::FIELDS as $field => $label) {
            $column = $mapping[$field] ?? null;
            if ($column === null || $column === '') {
                if (! in_array($field, ['role_cadangan_1', 'role_cadangan_2', 'deskripsi_pemain'], true)) {
                    throw ValidationException::withMessages(['mapping' => "Kolom {$label} wajib dipetakan."]);
                }
                continue;
            }

            if (! is_numeric($column) || (int) $column < 0 || (int) $column >= count($headers)) {
                throw ValidationException::withMessages(['mapping' => "Pemetaan kolom untuk {$label} tidak valid."]);
            }

            if (in_array((int) $column, $usedColumns, true)) {
                throw ValidationException::withMessages(['mapping' => 'Setiap kriteria harus menggunakan kolom yang berbeda.']);
            }
            $usedColumns[] = (int) $column;
        }

        $regions = Region::query()->get()->keyBy(fn (Region $region) => mb_strtolower(trim($region->nama_negara)));
        $errors = [];
        $players = [];

        foreach ($rows as $index => $row) {
            if (! is_array($row) || ! array_is_list($row) || count($row) > count($headers)) {
                $errors[] = 'Baris '.($index + 2).': format baris tidak valid.';
                continue;
            }

            $values = [];
            $invalidCell = false;
            foreach ($row as $cell) {
                if (! is_scalar($cell) && $cell !== null) {
                    $invalidCell = true;
                    break;
                }
            }
            if ($invalidCell) {
                $errors[] = 'Baris '.($index + 2).': isi kolom harus berupa teks atau angka.';
                continue;
            }

            foreach (self::FIELDS as $field => $label) {
                $column = $mapping[$field] ?? null;
                $values[$field] = $column === null || $column === ''
                    ? ''
                    : trim((string) ($row[(int) $column] ?? ''));
            }

            $line = $index + 2;
            foreach (['nama_asli', 'nickname_game', 'tanggal_lahir', 'region', 'role_utama', 'gaya_permainan_1', 'gaya_permainan_2', 'targeting_urut_1', 'targeting_urut_2', 'targeting_urut_3'] as $requiredField) {
                if ($values[$requiredField] === '') {
                    $errors[] = "Baris {$line}: {$this->fieldLabel($requiredField)} wajib diisi.";
                }
            }

            $date = null;
            if ($values['tanggal_lahir'] !== '') {
                try {
                    $parsedDate = Carbon::createFromFormat('!d/m/Y', $values['tanggal_lahir']);
                    if ($parsedDate === false || $parsedDate->format('d/m/Y') !== $values['tanggal_lahir']) {
                        throw new \InvalidArgumentException();
                    }
                    $date = $parsedDate->format('Y-m-d');
                } catch (\InvalidArgumentException $exception) {
                    $errors[] = "Baris {$line}: Birth date harus menggunakan format DD/MM/YYYY dan tanggal yang valid.";
                }
            }

            $region = $values['region'] === '' ? null : $regions->get(mb_strtolower($values['region']));
            if ($values['region'] !== '' && ! $region) {
                $errors[] = "Baris {$line}: Region \"{$values['region']}\" tidak ditemukan.";
            }

            $mainRole = $values['role_utama'];
            if ($mainRole !== '' && ! in_array($mainRole, Player::ATHLETE_ROLE_OPTIONS, true)) {
                $errors[] = "Baris {$line}: Role utama \"{$mainRole}\" tidak dikenal.";
            }

            $reserveRoles = array_values(array_filter([
                $values['role_cadangan_1'],
                $values['role_cadangan_2'],
            ], fn (string $role) => $role !== ''));
            if (count($reserveRoles) !== count(array_unique($reserveRoles))) {
                $errors[] = "Baris {$line}: Role cadangan tidak boleh duplikat.";
            }
            foreach ($reserveRoles as $reserveRole) {
                if (! in_array($reserveRole, Player::ATHLETE_ROLE_OPTIONS, true) || $reserveRole === $mainRole) {
                    $errors[] = "Baris {$line}: Role cadangan harus valid dan berbeda dari Role utama.";
                }
            }

            $gameplay = [$values['gaya_permainan_1'], $values['gaya_permainan_2']];
            if (count(array_unique($gameplay)) !== 2) {
                $errors[] = "Baris {$line}: Dua gaya permainan harus berbeda.";
            }
            foreach ($gameplay as $style) {
                if ($style !== '' && ! in_array($style, Player::ATHLETE_GAMEPLAY_OPTIONS, true)) {
                    $errors[] = "Baris {$line}: Gaya permainan \"{$style}\" tidak dikenal.";
                }
            }

            $targeting = [
                $values['targeting_urut_1'],
                $values['targeting_urut_2'],
                $values['targeting_urut_3'],
            ];
            if (count(array_unique($targeting)) !== 3) {
                $errors[] = "Baris {$line}: Gaya targeting harus berisi tiga pilihan yang berbeda.";
            }
            foreach ($targeting as $target) {
                if ($target !== '' && ! in_array($target, Player::TARGETING_ORDER_OPTIONS, true)) {
                    $errors[] = "Baris {$line}: Gaya targeting \"{$target}\" tidak dikenal.";
                }
            }

            $players[] = [
                'nama_asli' => $values['nama_asli'],
                'nickname_game' => $values['nickname_game'],
                'tanggal_lahir' => $date,
                'region_id' => $region?->id,
                'role_utama' => $mainRole,
                'role_cadangan' => $reserveRoles,
                'gaya_permainan' => $gameplay,
                'targeting_urut' => $targeting,
                'deskripsi_pemain' => $values['deskripsi_pemain'],
                'role_kurang_kompeten' => array_values(array_diff(
                    Player::ATHLETE_ROLE_OPTIONS,
                    array_merge([$mainRole], $reserveRoles),
                )),
            ];
        }

        if ($errors !== []) {
            return response()->view('players.import', [
                'fields' => self::FIELDS,
                'importErrors' => array_values(array_unique($errors)),
            ], 422);
        }

        $request->session()->put('player_import_preview', $players);

        return view('players.import-preview', [
            'players' => $players,
            'fields' => self::FIELDS,
            'regionNames' => Region::query()->pluck('nama_negara', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $players = $request->session()->get('player_import_preview');
        abort_unless(is_array($players) && count($players) > 0, 422, 'Preview impor tidak ditemukan. Silakan mulai kembali.');

        DB::transaction(function () use ($players) {
            foreach ($players as $player) {
                $player['kategori_personel'] = Player::PERSONNEL_TYPE_ATHLETE;
                $player['jabatan'] = 'None';
                $player['esport_team_id'] = null;
                Player::create($player);
            }
        });

        $request->session()->forget('player_import_preview');

        return redirect()->route('players.index')->with('success', count($players).' atlet berhasil diimpor.');
    }

    public function cancel(Request $request)
    {
        $request->session()->forget('player_import_preview');

        return redirect()->route('players.index');
    }

    private function fieldLabel(string $field): string
    {
        return self::FIELDS[$field] ?? $field;
    }
}
