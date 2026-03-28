<?php

use Livewire\Component;
use App\Models\Hero;

new class extends Component
{
    public $search = '';
    public $bluePicks = [];
    public $redPicks = [];
    public $blueBans = [];
    public $redBans = [];

    public function with() {
        return [
            'heroes' => Hero::where('name', 'like', "%{$this->search}%")->get(),
        ];
    }
};
?>
<div class="min-h-screen bg-[#0a0e13] text-gray-200 font-sans selection:bg-blue-500">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="flex justify-between items-center p-4 bg-[#161b22] border-b border-gray-800 shadow-2xl">
        <div class="flex items-center gap-3">
            <div class="text-blue-500 font-black italic text-xl mr-2">BANS</div>
            <div class="flex gap-1">
                @for ($i = 0; $i < 5; $i++)
                    <div class="w-10 h-10 border border-blue-900 bg-gray-900 flex items-center justify-center overflow-hidden grayscale opacity-70">
                        <div class="w-full h-full bg-slate-800"></div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="flex flex-col items-center">
            <div class="text-4xl font-black italic tracking-tighter text-white">00:30</div>
            <div class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-bold">Tournament Draft</div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex gap-1">
                @for ($i = 0; $i < 5; $i++)
                    <div class="w-10 h-10 border border-red-900 bg-gray-900 flex items-center justify-center overflow-hidden grayscale opacity-70">
                        <div class="w-full h-full bg-slate-800"></div>
                    </div>
                @endfor
            </div>
            <div class="text-red-500 font-black italic text-xl ml-2">BANS</div>
        </div>
    </div>

    <div class="grid grid-cols-12 h-[calc(100vh-85px)] overflow-hidden">

        <div class="col-span-3 bg-gradient-to-b from-[#0d1a2d] to-[#0a0e13] border-r border-blue-900/30">
            <div class="p-4 space-y-3">
                @for ($i = 0; $i < 5; $i++)
                    <div class="group relative h-28 w-full bg-[#1c242d] border-l-[6px] border-blue-600 overflow-hidden shadow-lg transition-all hover:brightness-125">
                        <div class="absolute inset-0 bg-blue-900/20 z-10"></div>
                        <div class="absolute bottom-2 left-4 z-20">
                            <div class="text-xs font-bold text-blue-400 uppercase tracking-widest">Player {{ $i+1 }}</div>
                            <div class="text-2xl font-black italic text-white uppercase tracking-tighter">Choosing...</div>
                        </div>
                        <div class="absolute right-0 top-0 h-full w-2/3 bg-slate-700 skew-x-[-12deg] translate-x-8"></div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="col-span-6 flex flex-col bg-[#0d1117] relative">
                        <div class="col-span-6 flex flex-col bg-[#0d1117] border-x border-gray-800 h-[calc(100vh-85px)]">

                <div class="p-4 bg-[#161b22] border-b border-gray-800">
                    <div class="relative group">
                        <input type="text" wire:model.live="search"
                               placeholder="SEARCH YOUR CHAMPION..."
                               class="w-full bg-[#0a0e13] border border-gray-700 p-3 pl-10 text-sm font-bold italic text-white focus:outline-none focus:border-blue-500 transition-all rounded-md">
                        <svg class="w-5 h-5 absolute left-3 top-3 text-gray-500 group-focus-within:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 gap-y-6 gap-x-2 justify-items-center">
                        @forelse($heroes as $hero)
                            <div wire:click="selectHero({{ $hero->id }})" class="group cursor-pointer flex flex-col items-center">
                                <div class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full border-2 border-gray-700 p-0.5 group-hover:border-blue-500 group-hover:scale-110 transition-all duration-200 overflow-hidden shadow-lg bg-gray-900">
                                    <img src="{{ asset('storage/heroes/' . $hero->image) }}" class="w-full h-full object-cover rounded-full">
                                    <div class="absolute inset-0 bg-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>
                                <span class="mt-2 text-[10px] font-bold text-gray-500 group-hover:text-white uppercase tracking-tighter truncate w-16 text-center">
                        {{ $hero->name }}
                    </span>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-10 text-gray-700 font-bold italic uppercase">No Data</div>
                        @endforelse
                    </div>
                </div>

                <div class="p-4 bg-[#161b22] border-t border-gray-800">
                    <button class="w-full bg-blue-700 hover:bg-blue-600 text-white font-black italic py-3 uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-blue-900/20">
                        Confirm Pick
                    </button>
                </div>
            </div>
        </div>

        <div class="col-span-3 bg-gradient-to-b from-[#2d0d0d] to-[#0a0e13] border-l border-red-900/30">
            <div class="p-4 space-y-3">
                @for ($i = 0; $i < 5; $i++)
                    <div class="group relative h-28 w-full bg-[#241c1c] border-r-[6px] border-red-600 overflow-hidden shadow-lg transition-all hover:brightness-125 text-right">
                        <div class="absolute inset-0 bg-red-900/20 z-10"></div>
                        <div class="absolute bottom-2 right-4 z-20">
                            <div class="text-xs font-bold text-red-400 uppercase tracking-widest">Player {{ $i+1 }}</div>
                            <div class="text-2xl font-black italic text-white uppercase tracking-tighter">Waiting...</div>
                        </div>
                        <div class="absolute left-0 top-0 h-full w-2/3 bg-slate-700 skew-x-[12deg] translate-x-[-2rem]"></div>
                    </div>
                @endfor
            </div>
        </div>

    </div>
</div>

<style>
    /* Ẩn thanh cuộn nhưng vẫn cuộn được cho đẹp */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #1e293b;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #3b82f6;
    }
</style>
