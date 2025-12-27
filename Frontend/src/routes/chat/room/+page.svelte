<script>
    import Sidebar from "../../../lib/Sidebar.svelte";
    import Icon from "@iconify/svelte";
    import { onMount } from "svelte";
    import { getUserInfo } from "../../../api/user";

    let currentUser = null;
    let searchTerms = "";

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    onMount(async () => {
        await fetchUser();
    });

    const rooms = [
        { id: 2, name: "Designers Hub", participants: 25, icon: "solar:palet-2-bold" },
        { id: 3, name: "Project Alpha", participants: 12, icon: "solar:mask-happly-bold" },
        { id: 4, name: "General Chat", participants: 156, icon: "solar:users-group-rounded-bold" }
    ];
</script>

<div class="min-h-screen text-white">
    <div class="flex flex-col lg:flex-row min-h-screen p-0 lg:p-5 gap-5">
        
        <Sidebar activePath="/chat/room"/>

        <main class="flex-1 lg:mt-0 lg:ml-80">
            <div class="h-full backdrop-blur-2xl border border-white/10 rounded-none lg:rounded-[2.5rem] p-6 lg:p-8 shadow-2xl flex flex-col min-h-[90vh]">
                
                {#if currentUser}
                    <div class="flex p-1 bg-white/5 rounded-2xl mb-8 w-full max-w-md border border-white/5">
                        <a href="/chat/room" class="flex-1 flex items-center justify-center py-3 rounded-xl bg-blue-600 text-white font-semibold no-underline shadow-lg shadow-blue-600/20 transition-all">
                            <Icon icon="solar:users-group-two-rounded-bold" class="mr-2" />
                            Rooms
                        </a>
                        <a href="/chat/contact/friend" class="flex-1 flex items-center justify-center py-3 rounded-xl text-gray-400 hover:text-white transition-all no-underline font-medium">
                            <Icon icon="solar:user-rounded-bold" class="mr-2" />
                            Contacts
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="md:col-span-2 relative">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                <Icon icon="solar:magnifer-linear" class="text-gray-500" width="20" />
                            </div>
                            <input 
                                type="search" 
                                bind:value={searchTerms}
                                placeholder="Rechercher un salon..." 
                                class="w-full bg-white/5 border border-white/10 focus:border-blue-500 rounded-2xl py-4 pl-12 pr-4 outline-none transition-all text-sm"
                            />
                        </div>
                        <button class="bg-white hover:bg-blue-600 hover:text-white text-black font-bold rounded-2xl py-4 px-6 flex items-center justify-center transition-all group border-none cursor-pointer">
                            <Icon icon="solar:add-circle-bold" class="mr-2 group-hover:rotate-90 transition-transform" width="20" />
                            Create room
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto space-y-3 custom-scrollbar">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold mb-4 ml-2">Salons disponibles</p>
                        
                        {#each rooms as room}
                            <a href="/chat/room/{room.id}" class="flex items-center p-4 bg-white/5 border border-white/5 hover:border-blue-500/50 hover:bg-white/[0.08] rounded-[1.5rem] transition-all no-underline group">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white mr-5 shadow-lg shadow-blue-600/10">
                                    <Icon icon={room.icon} width="28" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-bold text-lg mb-0.5 group-hover:text-blue-400 transition-colors">{room.name}</h3>
                                    <p class="text-gray-500 text-xs flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        {room.participants} membres connectés
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-white/5 text-gray-500 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    <Icon icon="solar:alt-arrow-right-linear" width="20" />
                                </div>
                            </a>
                        {/each}
                    </div>

                {:else}
                    <div class="animate-pulse space-y-8">
                        <div class="h-14 w-64 bg-white/5 rounded-2xl"></div>
                        <div class="h-16 w-full bg-white/5 rounded-2xl"></div>
                        <div class="space-y-4">
                            <div class="h-24 w-full bg-white/5 rounded-3xl"></div>
                            <div class="h-24 w-full bg-white/5 rounded-3xl"></div>
                        </div>
                    </div>
                {/if}

            </div>
        </main>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>