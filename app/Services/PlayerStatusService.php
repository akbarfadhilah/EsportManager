<?php

namespace App\Services;

use App\Models\EsportTeam;
use App\Models\Player;
use App\Models\Region;

/**
 * Central point for keeping player jabatan & team membership consistent.
 *
 * Rules:
 *  - A player can only hold ONE jabatan at a time (team staff, team roster,
 *    region official, or "None").
 *  - Jabatan is decided by the column/position the player is placed in.
 *  - Any player not referenced anywhere becomes jabatan "None" + Free Agent.
 */
class PlayerStatusService
{
    public const TEAM_STAFF_ROLES = [
        'pemilik_id'       => 'Owner',
        'ceo_id'           => 'CEO',
        'pelatih_utama_id' => 'Head Coach',
        'pelatih_kedua_id' => 'Assistant Coach',
        'manager_id'       => 'Manager',
        'analis_id'        => 'Analyst',
    ];

    public const REGION_ROLES = [
        'ketua_persatuan_game_id' => 'Ketua Persatuan Game',
        'pelatih_timnas_id'       => 'Pelatih Timnas',
    ];

    /**
     * Detach the given players from every team staff column, every region
     * role column and any roster membership, guaranteeing they will only
     * ever hold a single jabatan within the new assignment.
     */
    public function clearAssignments(array $playerIds): void
    {
        $playerIds = array_values(array_unique(array_filter($playerIds)));

        if (!$playerIds) {
            return;
        }

        foreach (array_keys(self::TEAM_STAFF_ROLES) as $column) {
            EsportTeam::whereIn($column, $playerIds)->update([$column => null]);
        }

        foreach (array_keys(self::REGION_ROLES) as $column) {
            Region::whereIn($column, $playerIds)->update([$column => null]);
        }

        // Remove roster membership (jabatan Player). Staff/region holders are
        // re-derived below by syncAll(), so scoping to jabatan "Player" is safe.
        Player::whereIn('id', $playerIds)
            ->where('jabatan', 'Player')
            ->update(['jabatan' => 'None', 'esport_team_id' => null]);
    }

    /**
     * Recompute every player's jabatan & team from the current team/region
     * assignments. Players not referenced anywhere become "None" / Free Agent.
     */
    public function syncAll(): void
    {
        $assignments = [];

        // Team staff roles
        $staffColumns = array_merge(['id'], array_keys(self::TEAM_STAFF_ROLES));
        foreach (EsportTeam::select($staffColumns)->get() as $team) {
            foreach (self::TEAM_STAFF_ROLES as $column => $jabatan) {
                if ($team->{$column}) {
                    $assignments[$team->{$column}] = ['jabatan' => $jabatan, 'esport_team_id' => $team->id];
                }
            }
        }

        // Team roster / legacy club membership: any player whose record still
        // points to an existing club keeps that club as their team, even when
        // they hold a staff role that has no matching column anymore (e.g. the
        // old player-level CEO/team inputs). Staff & region columns take
        // priority below / above.
        $teamIds = EsportTeam::pluck('id')->all();
        foreach (Player::select(['id', 'jabatan', 'esport_team_id'])
            ->whereNotNull('esport_team_id')
            ->get() as $player) {
            if (!isset($assignments[$player->id])
                && in_array($player->esport_team_id, $teamIds)) {
                $assignments[$player->id] = ['jabatan' => 'Player', 'esport_team_id' => $player->esport_team_id];
            }
        }

        // Region roles (highest priority — overwrites any former assignment)
        $regionColumns = array_merge(['id'], array_keys(self::REGION_ROLES));
        foreach (Region::select($regionColumns)->get() as $region) {
            foreach (self::REGION_ROLES as $column => $jabatan) {
                if ($region->{$column}) {
                    $assignments[$region->{$column}] = ['jabatan' => $jabatan, 'esport_team_id' => null];
                }
            }
        }

        foreach (Player::select(['id', 'jabatan', 'esport_team_id'])->get() as $player) {
            if (isset($assignments[$player->id])) {
                $a = $assignments[$player->id];
                if ($player->jabatan !== $a['jabatan'] || (int) $player->esport_team_id !== (int) $a['esport_team_id']) {
                    $player->forceFill(['jabatan' => $a['jabatan'], 'esport_team_id' => $a['esport_team_id']])->save();
                }
            } elseif ($player->jabatan !== 'None' || $player->esport_team_id !== null) {
                $player->forceFill(['jabatan' => 'None', 'esport_team_id' => null])->save();
            }
        }
    }
}