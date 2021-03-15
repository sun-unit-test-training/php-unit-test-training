<?php

namespace Modules\Exercise09\Tests\Unit\Services;

use Modules\Exercise09\Constants\Combat;
use Modules\Exercise09\Services\CombatService;
use Tests\TestCase;

class CombatServiceTest extends TestCase
{
    private $defaultInput;

    private $combatService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultInput = [
            'dua_phep' => 0,
            'quan_su' => 0,
            'chia_khoa' => 0,
            'kiem_anh_sang' => 0,
        ];
        $this->combatService = new CombatService();
    }

    /**
     * No items
     */
    public function test_he_start_without_anything()
    {
        $result = $this->combatService->calculateAttackResult($this->defaultInput);
        $this->assertEquals(Combat::ROOM_NOT_FOUND, $result);
    }

    // ===================== 1 ITEM ONLY =====================
    public function test_he_start_with_dua_phep_only()
    {
        $input = array_merge($this->defaultInput, ['dua_phep' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_FINDABLE, $result);
    }

    public function test_he_start_with_quan_su_only()
    {
        $input = array_merge($this->defaultInput, ['quan_su' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_FINDABLE, $result);
    }

    public function test_he_start_with_chia_khoa_only()
    {
        $input = array_merge($this->defaultInput, ['chia_khoa' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_NOT_FOUND, $result);
    }

    public function test_he_start_with_kiem_anh_sang_only()
    {
        $input = array_merge($this->defaultInput, ['kiem_anh_sang' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_NOT_FOUND, $result);
    }

    // ===================== 2 ITEMS =====================
    public function test_he_start_with_dua_phep_and_quan_su()
    {
        $input = array_merge($this->defaultInput, ['dua_phep' => 1, 'quan_su' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_FINDABLE, $result);
    }

    public function test_he_start_with_dua_phep_and_chia_khoa()
    {
        $input = array_merge($this->defaultInput, ['dua_phep' => 1, 'chia_khoa' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_ACCESSIBLE, $result);
    }

    public function test_he_start_with_dua_phep_and_kiem_anh_sang()
    {
        $input = array_merge($this->defaultInput, ['dua_phep' => 1, 'kiem_anh_sang' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_FINDABLE, $result);
    }

    public function test_he_start_with_quan_su_and_chia_khoa()
    {
        $input = array_merge($this->defaultInput, ['quan_su' => 1, 'chia_khoa' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_ACCESSIBLE, $result);
    }

    public function test_he_start_with_quan_su_and_kiem_anh_sang()
    {
        $input = array_merge($this->defaultInput, ['quan_su' => 1, 'kiem_anh_sang' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_FINDABLE, $result);
    }

    public function test_he_start_with_chia_khoa_and_kiem_anh_sang()
    {
        $input = array_merge($this->defaultInput, ['chia_khoa' => 1, 'kiem_anh_sang' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_NOT_FOUND, $result);
    }

    // ===================== 3 ITEMS =====================
    public function test_he_start_without_dua_phep()
    {
        $input = array_merge($this->defaultInput, ['quan_su' => 1, 'chia_khoa' => 1, 'kiem_anh_sang' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::WON, $result);
    }

    public function test_he_start_without_quan_su()
    {
        $input = array_merge($this->defaultInput, ['dua_phep' => 1, 'chia_khoa' => 1, 'kiem_anh_sang' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::WON, $result);
    }

    public function test_he_start_without_chia_khoa()
    {
        $input = array_merge($this->defaultInput, ['dua_phep' => 1, 'quan_su' => 1, 'kiem_anh_sang' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_FINDABLE, $result);
    }

    public function test_he_start_without_kiem_anh_sang()
    {
        $input = array_merge($this->defaultInput, ['dua_phep' => 1, 'chia_khoa' => 1, 'quan_su' => 1]);
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::ROOM_ACCESSIBLE, $result);
    }

    // ===================== 4 ITEMS =====================
    public function test_he_start_full_4_items()
    {
        $input = ['dua_phep' => 1, 'chia_khoa' => 1, 'quan_su' => 1, 'kiem_anh_sang' => 1];
        $result = $this->combatService->calculateAttackResult($input);
        $this->assertEquals(Combat::WON, $result);
    }
}
