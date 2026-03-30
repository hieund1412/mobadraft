<div class="h-screen max-h-screen bg-[#06090c] text-gray-100 flex flex-col overflow-hidden"
     @if($isStarted) wire:poll.1s="decrementTimer" @endif>

    <script src="https://cdn.tailwindcss.com"></script>

    <div
        class="h-16 bg-black border-b-2 border-yellow-500/20 flex justify-between items-center px-10 flex-none shadow-2xl">
        <div class="text-blue-500 font-black italic text-2xl tracking-tighter uppercase">Blue Side</div>

        <div class="flex flex-col items-center">
            @if(!$isStarted)
                <button wire:click.prevent="startDraft"
                        class="bg-yellow-500 text-black font-black px-12 py-2 italic hover:bg-yellow-400 transition-all uppercase skew-x-[-15deg] text-lg shadow-[0_0_20px_rgba(234,179,8,0.3)]">
                    Start Draft
                </button>
            @else
                <div
                    class="text-5xl font-mono font-black text-yellow-500 leading-none drop-shadow-[0_0_15px_rgba(234,179,8,0.6)]">
                    {{ str_pad($timeLeft, 2, '0', STR_PAD_LEFT) }}
                </div>
                <div class="text-[10px] text-gray-500 uppercase tracking-[0.4em] mt-1 font-bold animate-pulse">
                    {{ str_replace('_', ' ', $this->phases[$this->currentPhaseIndex]['name']) }}
                </div>
            @endif
        </div>

        <div class="text-red-600 font-black italic text-2xl tracking-tighter uppercase text-right">Red Side</div>
    </div>

    <div class="bg-[#0d1117] p-4 flex justify-between border-b border-gray-800 flex-none items-center shadow-inner">
        <div class="flex gap-2">
            @for ($i = 0; $i < 5; $i++)
                @php $isBanning = ($isStarted && $this->phases[$this->currentPhaseIndex]['side'] == 'blue' && $this->phases[$this->currentPhaseIndex]['type'] == 'ban' && count($blueBans) == $i); @endphp
                <div
                    class="w-14 h-14 bg-gray-950 border-2 {{ $isBanning ? 'border-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.5)] animate-pulse scale-110' : 'border-blue-900/30' }} overflow-hidden relative">
                    @if(isset($blueBans[$i]))
                        <img src="{{ asset('heroes/'.$blueBans[$i]['image']) }}"
                             class="w-full h-full object-cover grayscale opacity-50 border-b-4 border-red-600">
                    @elseif($isBanning && $selectedHeroId)
                        <img src="{{ asset('heroes/'.\App\Models\Hero::find($selectedHeroId)->image) }}"
                             class="w-full h-full object-cover opacity-20 animate-pulse">
                    @endif
                </div>
            @endfor
        </div>

        <div class="hidden lg:block text-[11px] font-black text-gray-600 uppercase tracking-[0.6em] italic">Banning
            Phase
        </div>

        <div class="flex gap-2">
            @for ($i = 0; $i < 5; $i++)
                @php $isBanning = ($isStarted && $this->phases[$this->currentPhaseIndex]['side'] == 'red' && $this->phases[$this->currentPhaseIndex]['type'] == 'ban' && count($redBans) == $i); @endphp
                <div
                    class="w-14 h-14 bg-gray-950 border-2 {{ $isBanning ? 'border-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.5)] animate-pulse scale-110' : 'border-red-900/30' }} overflow-hidden relative">
                    @if(isset($redBans[$i]))
                        <img src="{{ asset('heroes/'.$redBans[$i]['image']) }}"
                             class="w-full h-full object-cover grayscale opacity-50 border-b-4 border-red-600">
                    @elseif($isBanning && $selectedHeroId)
                        <img src="{{ asset('heroes/'.\App\Models\Hero::find($selectedHeroId)->image) }}"
                             class="w-full h-full object-cover opacity-20 animate-pulse">
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">

        <div
            class="w-1/4 border-r border-blue-900/40 bg-gradient-to-b from-[#0d1a2d] to-black overflow-y-auto custom-scrollbar p-5 space-y-4">
            @for ($i = 0; $i < 5; $i++)
                @php
                    $pick = $bluePicks[$i] ?? null;
                    $isPicking = ($isStarted && $this->phases[$this->currentPhaseIndex]['side'] == 'blue' && $this->phases[$this->currentPhaseIndex]['type'] == 'pick' && count($bluePicks) == $i);
                @endphp
                <div
                    class="h-32 bg-[#05080a] border-l-[6px] {{ $pick ? 'border-blue-500 shadow-blue-500/10' : ($isPicking ? 'border-yellow-400 animate-pulse scale-[1.02]' : 'border-gray-800') }} relative overflow-hidden transition-all duration-300 shadow-2xl">
                    @if($pick)
                        <img src="{{ asset('heroes/'.$pick['image']) }}"
                             class="absolute inset-0 w-full h-full object-cover object-top opacity-80">
                        <div
                            class="absolute bottom-3 left-4 z-10 font-black italic text-3xl uppercase text-white drop-shadow-2xl tracking-tighter">{{ $pick['name'] }}</div>
                    @elseif($isPicking && $selectedHeroId)
                        <img src="{{ asset('heroes/'.\App\Models\Hero::find($selectedHeroId)->image) }}"
                             class="absolute inset-0 w-full h-full object-cover object-top opacity-30 animate-pulse">
                    @endif
                    <div
                        class="absolute top-2 left-4 text-xs text-blue-400 font-black uppercase tracking-tighter opacity-50">
                        Player {{ $i+1 }}</div>
                </div>
            @endfor
        </div>

        <div class="w-1/2 flex flex-col bg-[#06090c] border-x border-gray-800 relative shadow-inner">
            <div class="p-5 bg-[#0d1117] border-b border-gray-800 flex-none">
                <input type="text" wire:model.live="search" placeholder="FIND YOUR CHAMPION..."
                       class="w-full bg-black border-2 border-gray-800 p-4 text-white italic outline-none focus:border-yellow-500/50 uppercase text-sm tracking-widest transition-all shadow-inner">
            </div>

            <div class="flex-1 overflow-y-scroll p-6 custom-scrollbar">
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-6 pb-20">
                    @foreach($heroes as $hero)
                        @php $occ = in_array($hero->id, $this->getAllOccupiedIds()); @endphp
                        <div wire:click.prevent="{{ $occ ? '' : 'selectHero('.$hero->id.')' }}"
                             class="flex flex-col items-center group {{ $occ ? 'opacity-10 grayscale cursor-not-allowed' : 'cursor-pointer hover:scale-110' }} transition-all duration-200">
                            <div
                                class="relative w-20 h-20 rounded-full border-4 {{ $selectedHeroId == $hero->id ? 'border-yellow-400 shadow-[0_0_25px_rgba(234,179,8,0.7)]' : 'border-gray-800' }} p-1.5 bg-gray-900 transition-all">
                                <img src="{{ asset('heroes/'.$hero->image) }}"
                                     class="w-full h-full object-cover rounded-full">
                                @if($occ)
                                    <div
                                        class="absolute inset-0 flex items-center justify-center text-red-600 font-black text-4xl">
                                        ✕
                                    </div>
                                @endif
                            </div>
                            <span
                                class="text-[11px] mt-3 font-black {{ $selectedHeroId == $hero->id ? 'text-yellow-400' : 'text-gray-400' }} group-hover:text-white uppercase text-center tracking-tighter">{{ $hero->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-6 bg-[#0d1117] border-t border-gray-800 flex-none">
                <button wire:click.prevent="confirm" @if(!$selectedHeroId) disabled @endif
                class="w-full py-5 font-black italic uppercase text-3xl transition-all {{ !$selectedHeroId ? 'bg-gray-800 text-gray-600' : 'bg-blue-600 hover:bg-blue-500 text-white shadow-[0_0_30px_rgba(37,99,235,0.3)] active:scale-95' }}">
                    Lock-In
                </button>
            </div>
        </div>

        <div
            class="w-1/4 border-l border-red-900/40 bg-gradient-to-b from-[#2d0d0d]/30 to-black overflow-y-auto custom-scrollbar p-5 space-y-4">
            @for ($i = 0; $i < 5; $i++)
                @php
                    $pick = $redPicks[$i] ?? null;
                    $isPicking = ($isStarted && $this->phases[$this->currentPhaseIndex]['side'] == 'red' && $this->phases[$this->currentPhaseIndex]['type'] == 'pick' && count($redPicks) == $i);
                @endphp
                <div
                    class="h-32 bg-[#0a0505] border-r-[6px] {{ $pick ? 'border-red-600 shadow-red-500/10' : ($isPicking ? 'border-yellow-400 animate-pulse scale-[1.02]' : 'border-gray-800') }} relative overflow-hidden transition-all duration-300 text-right shadow-2xl">
                    @if($pick)
                        <img src="{{ asset('heroes/'.$pick['image']) }}"
                             class="absolute inset-0 w-full h-full object-cover object-top opacity-80">
                        <div
                            class="absolute bottom-3 right-4 z-10 font-black italic text-3xl uppercase text-white drop-shadow-2xl tracking-tighter">{{ $pick['name'] }}</div>
                    @elseif($isPicking && $selectedHeroId)
                        <img src="{{ asset('heroes/'.\App\Models\Hero::find($selectedHeroId)->image) }}"
                             class="absolute inset-0 w-full h-full object-cover object-top opacity-30 animate-pulse text-right">
                    @endif
                    <div
                        class="absolute top-2 right-4 text-xs text-red-500 font-black uppercase tracking-tighter opacity-50">
                        Player {{ $i+1 }}</div>
                </div>
            @endfor
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            display: block !important;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #06090c;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
            border: 2px solid #06090c;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }

        .custom-scrollbar {
            overflow-y: scroll !important;
        }
    </style>
</div>
