<?php

use Livewire\Component;
use App\Models\Hero;

new class extends Component
{
    public $search = '';
    public $bluePicks = [];
    public $redPicks = [];
    public $currentStep = 0;

    // Logic xử lý khi click chọn tướng
    public function selectHero($id) {
        $hero = Hero::find($id);

        // Ví dụ: Click là vào đội xanh
        if (count($this->bluePicks) < 5) {
            $this->bluePicks[] = $hero;
            $this->currentStep++;
        }
    }

    // Hàm lấy danh sách tướng để hiển thị
    public function with() {
        return [
            'heroes' => Hero::where('name', 'like', "%{$this->search}%")->get(),
        ];
    }
};
?>
<div>
    <div class="p-5 bg-gray-900 text-white">
        <input type="text" wire:model.live="search" placeholder="Tìm tướng..." class="text-black p-2">

        <div class="grid grid-cols-6 gap-4 mt-5">
            @foreach($heroes as $hero)
                <div wire:click="selectHero({{ $hero->id }})" class="cursor-pointer border p-2">
                    <p>{{ $hero->name }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            <h3 class="text-blue-400">Blue Team:</h3>
            @foreach($bluePicks as $pick)
                <span>{{ $pick['name'] }}, </span>
            @endforeach
        </div>
    </div>
</div>
