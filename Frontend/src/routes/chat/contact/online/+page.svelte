<script>
    import Sidebar from "../../../../lib/Sidebar.svelte";
    import Icon from "@iconify/svelte";
    import { onMount } from "svelte";
    import { getUserInfo } from "../../../../api/user";
    import { getUserFriendOnline } from "../../../../api/friend";

    let currentUser = null;
    let usersList = [];
    let searchTerms = "";
    let activeFilter = "online";
    let intervalId;

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
            console.log('Informations de l’utilisateur récupérées: ', currentUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const fetchData = async () => {
        try {
            usersList = await getUserFriendOnline();
            console.log('Informations des utilisateurs online récupérées', usersList);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    onMount(async () => {
        await fetchUser();
        await fetchData();

        intervalId = setInterval(() => {
            fetchData();
        }, 10000);

        return () => {
            if (intervalId) clearInterval(intervalId);
        }
    });
</script>

<div class="min-h-screen text-white">
    <div class="flex flex-col lg:flex-row min-h-screen p-0 lg:p-5 gap-5">
        
        <Sidebar activePath="/chat/room"/>

        <main class="flex-1 lg:mt-0 lg:ml-80">
            <div class="h-full backdrop-blur-2xl border border-white/10 rounded-none lg:rounded-[2.5rem] p-6 lg:p-8 shadow-2xl flex flex-col min-h-[90vh]">
                
                {#if currentUser}
                    <div class="flex p-1 bg-white/5 rounded-2xl mb-8 w-full max-w-md border border-white/5">
                        <a href="/chat/room" class="flex-1 flex items-center justify-center py-3 rounded-xl text-gray-400 hover:text-white transition-all no-underline font-medium">
                            <Icon icon="solar:users-group-two-rounded-bold" class="mr-2" />
                            Rooms
                        </a>
                        <a href="/chat/contact" class="flex-1 flex items-center justify-center py-3 rounded-xl bg-blue-600 text-white font-semibold no-underline shadow-lg shadow-blue-600/20 transition-all">
                            <Icon icon="solar:user-rounded-bold" class="mr-2" />
                            Contacts
                        </a>
                    </div>

                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-500">
                            <Icon icon="solar:magnifer-linear" width="20" />
                        </div>
                        <input 
                            type="search" 
                            bind:value={searchTerms}
                            placeholder="Rechercher parmi vos contacts..." 
                            class="w-full bg-white/5 border border-white/10 focus:border-blue-500 rounded-2xl py-4 pl-12 pr-4 outline-none transition-all text-sm"
                        />
                    </div>

                    <div class="flex flex-wrap gap-2 mb-8">
                        <a href="/chat/contact/all" class="flex items-center px-4 py-2 rounded-xl {activeFilter === 'all' ? 'bg-blue-500/10 border-blue-500/20 text-blue-400' : 'bg-white/5 border-white/5 text-gray-400'} text-xs font-semibold no-underline hover:text-white transition-all">
                            <Icon icon="solar:users-group-rounded-linear" class="mr-2" /> All Users
                        </a>
                        <a href="/chat/contact/friend" class="flex items-center px-4 py-2 rounded-xl {activeFilter === 'friends' ? 'bg-blue-500/10 border-blue-500/20 text-blue-400' : 'bg-white/5 border-white/5 text-gray-400'} text-xs font-semibold no-underline hover:text-white transition-all">
                            <Icon icon="solar:heart-linear" class="mr-2" /> Friends
                        </a>
                        <a href="/chat/contact/online" class="flex items-center px-4 py-2 rounded-xl bg-green-500/10 border border-green-500/20 text-xs font-semibold text-green-400 no-underline shadow-[0_0_15px_rgba(34,197,94,0.1)]">
                            <Icon icon="solar:bolt-circle-bold" class="mr-2" /> Online Now
                        </a>
                    </div>

                    <div class="flex-1 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                        <div class="flex items-center justify-between mb-4 ml-2">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Contacts en ligne</p>
                            <span class="flex items-center text-[10px] text-green-500 bg-green-500/10 px-2 py-1 rounded-full animate-pulse">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                Live Sync
                            </span>
                        </div>
                        
                        {#if usersList.length > 0}
                            {#each usersList as friend}
                                <a href="/chat/contact/friend/{friend.id}" class="flex items-center p-4 bg-white/[0.02] border border-white/5 hover:border-green-500/30 hover:bg-white/[0.05] rounded-2xl transition-all no-underline group">
                                    
                                    <div class="relative mr-4">
                                        <img src="https://ui-avatars.com/api/?name={friend.name}&background=065f46&color=fff" alt="" class="w-12 h-12 rounded-xl border border-white/10" />
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-[#0a0a0a] rounded-full shadow-[0_0_10px_rgba(34,197,94,0.5)]"></span>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <h4 class="text-white font-bold group-hover:text-green-400 transition-colors">{friend.name}</h4>
                                        <p class="text-[11px] text-gray-500 flex items-center mt-0.5">
                                            Ami
                                            <Icon icon="solar:check-circle-bold" class="ml-1.5 text-blue-500" width="14" />
                                        </p>
                                    </div>

                                    <div class="flex items-center text-green-500 font-bold text-[10px] bg-green-500/5 px-3 py-1.5 rounded-lg border border-green-500/10 group-hover:bg-green-500 group-hover:text-white transition-all">
                                        CHAT <Icon icon="solar:plain-bold" class="ml-2" />
                                    </div>
                                </a>
                            {/each}
                        {:else}
                            <div class="text-center py-20 bg-white/[0.02] rounded-[2rem] border border-dashed border-white/10">
                                <Icon icon="solar:ghost-linear" width="48" class="mx-auto text-gray-600 mb-4" />
                                <p class="text-gray-400 font-medium">Personne n'est en ligne pour le moment</p>
                            </div>
                        {/if}
                    </div>

                {:else}
                    <div class="animate-pulse space-y-6">
                        <div class="h-14 w-64 bg-white/5 rounded-2xl"></div>
                        <div class="h-20 w-full bg-white/5 rounded-2xl"></div>
                        <div class="h-20 w-full bg-white/5 rounded-2xl"></div>
                    </div>
                {/if}

            </div>
        </main>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
</style>