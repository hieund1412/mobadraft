<?php

namespace App\Livewire;

use App\Models\Hero;
use Livewire\Component;

class MobaDraft extends Component
{
    // Thứ tự phase chuẩn theo mô tả của bạn
    public $search = '';
    public $selectedRole = 'Tất cả'; // Mặc định hiển thị tất cả
    public $isStarted = false;
    public $timeLeft = 0;
    public $currentPhaseIndex = 0;
    public $selectedHeroId = null;
    public $blueBans = [];
    public $redBans = [];

    // Dùng mảng phẳng để dễ quản lý số lượng đã chọn trong từng phase
    public $bluePicks = [];
    public $redPicks = [];
    public $currentPhaseActionCount = 0;
    protected $phases = [
        // GIAI ĐOẠN 1
        ['name' => 'BLUE_BAN_1', 'side' => 'blue', 'type' => 'ban', 'count' => 3, 'timer' => 30],
        ['name' => 'RED_BAN_1', 'side' => 'red', 'type' => 'ban', 'count' => 3, 'timer' => 30],
        ['name' => 'BLUE_PICK_1', 'side' => 'blue', 'type' => 'pick', 'count' => 1, 'timer' => 20],
        ['name' => 'RED_PICK_1', 'side' => 'red', 'type' => 'pick', 'count' => 2, 'timer' => 30],
        ['name' => 'BLUE_PICK_2', 'side' => 'blue', 'type' => 'pick', 'count' => 2, 'timer' => 30],
        ['name' => 'RED_PICK_2', 'side' => 'red', 'type' => 'pick', 'count' => 1, 'timer' => 20],

        // GIAI ĐOẠN 2 (Mới bổ sung)
        ['name' => 'BLUE_BAN_2', 'side' => 'blue', 'type' => 'ban', 'count' => 2, 'timer' => 30],
        ['name' => 'RED_BAN_2', 'side' => 'red', 'type' => 'ban', 'count' => 2, 'timer' => 30],
        ['name' => 'RED_PICK_3', 'side' => 'red', 'type' => 'pick', 'count' => 1, 'timer' => 20],
        ['name' => 'BLUE_PICK_3', 'side' => 'blue', 'type' => 'pick', 'count' => 2, 'timer' => 30],
        ['name' => 'RED_PICK_4', 'side' => 'red', 'type' => 'pick', 'count' => 1, 'timer' => 20],
    ];

    // Lưu số lượng đã thực hiện TRONG PHASE HIỆN TẠI

    public function setRole($role)
    {
        $this->selectedRole = $role;
    }

    public function startDraft()
    {
        $this->isStarted = true;
        $this->currentPhaseIndex = 0;
        $this->currentPhaseActionCount = 0;
        $this->blueBans = [];
        $this->redBans = [];
        $this->bluePicks = [];
        $this->redPicks = [];
        $this->timeLeft = $this->phases[0]['timer'];
    }

    public function decrementTimer()
    {
        if (!$this->isStarted || $this->currentPhaseIndex >= count($this->phases)) return;
        if ($this->timeLeft > 0) {
            $this->timeLeft--;
        } else {
            $this->autoConfirm();
        }
    }

    protected function autoConfirm()
    {
        if (!$this->selectedHeroId) {
            $randomHero = Hero::whereNotIn('id', $this->getAllOccupiedIds())->inRandomOrder()->first();
            if ($randomHero) $this->selectedHeroId = $randomHero->id;
        }
        $this->confirm();
    }

    public function getAllOccupiedIds()
    {
        return collect($this->blueBans)->pluck('id')
            ->merge(collect($this->redBans)->pluck('id'))
            ->merge(collect($this->bluePicks)->pluck('id'))
            ->merge(collect($this->redPicks)->pluck('id'))
            ->toArray();
    }

    public function confirm()
    {
        if (!$this->selectedHeroId) return;

        $phase = $this->phases[$this->currentPhaseIndex];
        $hero = Hero::find($this->selectedHeroId);
        $heroData = $hero->toArray();

        // Lưu dữ liệu
        if ($phase['side'] == 'blue') {
            if ($phase['type'] == 'ban') $this->blueBans[] = $heroData;
            else $this->bluePicks[] = $heroData;
        } else {
            if ($phase['type'] == 'ban') $this->redBans[] = $heroData;
            else $this->redPicks[] = $heroData;
        }

        $this->selectedHeroId = null;
        $this->currentPhaseActionCount++;

        // Nếu phase hiện tại đã làm đủ số lượng (ví dụ Đỏ đã chọn đủ 2 con)
        if ($this->currentPhaseActionCount >= $phase['count']) {
            $this->nextPhase();
        }
    }

    protected function nextPhase()
    {
        if ($this->currentPhaseIndex < count($this->phases) - 1) {
            $this->currentPhaseIndex++;
            $this->currentPhaseActionCount = 0; // Reset đếm cho phase mới
            $this->timeLeft = $this->phases[$this->currentPhaseIndex]['timer'];
        } else {
            $this->isStarted = false; // Kết thúc giai đoạn 1
        }
    }

    public function selectHero($id)
    {
        if (!$this->isStarted || $this->isHeroOccupied($id)) return;
        $this->selectedHeroId = $id;
    }

    protected function isHeroOccupied($id)
    {
        return in_array($id, $this->getAllOccupiedIds());
    }

    public function render()
    {
        $query = Hero::query();

        // Lọc theo tên
        if (!empty($this->search)) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        // Lọc theo hệ (Role)
        if ($this->selectedRole !== 'Tất cả') {
            $query->where('role', 'like', "%{$this->selectedRole}%");
        }

        return view('livewire.index', [
            'heroes' => $query->get(),
        ]);
    }
}
