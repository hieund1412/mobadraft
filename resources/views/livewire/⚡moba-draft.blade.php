<div class="min-h-screen bg-[#0a0e13] text-gray-200 font-sans shadow-inner"
     @if($isStarted) wire:poll.1s="decrementTimer" @endif>

    <script src="https://cdn.tailwindcss.com"></script>

    <div class="bg-[#161b22] border-b border-gray-800 p-3 flex justify-center items-center h-16 sticky top-0 z-50">
        @if(!$isStarted)
            <button wire:click="startDraft"
                    class="bg-blue-600 hover:bg-blue-500 text-white font-black px-12 py-2 uppercase italic skew-x-[-15deg] transition-all">
                START DRAFT
            </button>
        @else
            <div class="flex items-center gap-10">
                <div class="text-blue-500 font-bold tracking-widest animate-pulse uppercase">Ban Phase</div>
                <div
                    class="text-4xl font-mono font-black text-yellow-500 bg-black px-6 py-1 border border-yellow-500/40">
                    {{ str_pad($timeLeft, 2, '0', STR_PAD_LEFT) }}
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-12 h-[calc(100vh-64px)]">

        <div class="col-span-3 bg-gray-900/20 p-6 border-r border-gray-800 overflow-y-auto">
            <h2 class="text-blue-500 font-black italic text-xl mb-6 uppercase">Blue Bans</h2>
            <div class="space-y-4">
                @for ($i = 0; $i < 5; $i++)
                    <div class="flex items-center gap-4 p-3 bg-gray-900/80 border border-gray-800 rounded-lg">
                        <div
                            class="w-14 h-14 rounded-full border-2 border-gray-700 overflow-hidden bg-black flex-shrink-0">
                            @if(isset($blueBans[$i]))
                                <img src="{{ asset('storage/heroes/'.$blueBans[$i]['image']) }}"
                                     class="w-full h-full object-cover grayscale opacity-50">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center text-gray-800 font-black italic text-xl">{{ $i+1 }}</div>
                            @endif
                        </div>
                        <p class="font-black italic uppercase {{ isset($blueBans[$i]) ? 'text-red-500' : 'text-gray-700' }}">
                            {{ isset($blueBans[$i]) ? $blueBans[$i]['name'] : 'Empty' }}
                        </p>
                    </div>
                @endfor
            </div>
        </div>

        <div class="col-span-6 flex flex-col bg-[#0d1117]">
            <div class="p-4 bg-[#161b22] border-b border-gray-800">
                <input type="text" wire:model.live="search" placeholder="SEARCH HERO..."
                       class="w-full bg-black border border-gray-700 p-3 text-white focus:border-blue-500 outline-none font-bold italic uppercase">
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-6 justify-items-center">
                    @foreach($heroes as $hero)
                        @php
                            $isBanned = collect($blueBans)->contains('id', $hero->id);
                            $isSelected = $selectedHeroId == $hero->id;
                        @endphp

                        <div wire:click="selectHero({{ $hero->id }})"
                             class="flex flex-col items-center group {{ !$isStarted || $isBanned ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer hover:scale-110 transition-all' }}">

                            <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full border-[3px] transition-all
                                {{ $isSelected ? 'border-yellow-400 shadow-[0_0_15px_rgba(234,179,8,0.5)]' : 'border-gray-700' }}
                                {{ $isBanned ? 'grayscale border-red-900' : 'group-hover:border-blue-500' }} p-1 bg-gray-900 shadow-xl">
                                <img src="{{ asset('storage/heroes/'.$hero->image) }}"
                                     class="w-full h-full object-cover rounded-full">
                                @if($isBanned)
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-full h-0.5 bg-red-600 rotate-45"></div>
                                    </div>
                                @endif
                            </div>
                            <span
                                class="mt-2 text-[10px] font-black uppercase text-center {{ $isSelected ? 'text-yellow-400' : 'text-gray-500' }}">
                                {{ $hero->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-6 bg-[#161b22] border-t border-gray-800">
                <button wire:click="confirmBan" {{ (!$isStarted || !$selectedHeroId) ? 'disabled' : '' }}
                class="w-full py-4 font-black italic uppercase text-xl transition-all
                        {{ (!$isStarted || !$selectedHeroId) ? 'bg-gray-800 text-gray-600' : 'bg-red-600 hover:bg-red-500 text-white shadow-lg active:scale-95' }}">
                    CONFIRM BAN
                </button>
            </div>
        </div>

        <div class="col-span-3 bg-gray-900/20 p-6 border-l border-gray-800 text-right">
            <h2 class="text-red-600 font-black italic text-xl mb-6 uppercase">Red Bans</h2>
            <span class="text-gray-700 italic">Waiting...</span>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 10px;
        }
    </style>
</div>
