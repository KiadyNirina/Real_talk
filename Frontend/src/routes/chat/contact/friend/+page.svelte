<script>
    import Sidebar from "../../../../lib/Sidebar.svelte";
    import { onMount } from "svelte";
    import { getUserInfo } from "../../../../api/user";
    import { getUserFriends } from "../../../../api/friend";
    import Icon from "@iconify/svelte";

    let user = null;
    let friends = [];
    let searchTerms = "";

    const fetchUser = async () => {
        try {
            user = await getUserInfo();
            console.log('Informations de l’utilisateur récupérées: ', user);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const fetchUserFriend = async () => {
        try {
            friends = await getUserFriends();
        } catch(err) {
            console.error('Error fetching user friends: ', err);
        }
    }

    onMount(async () => {
        await fetchUser();
        await fetchUserFriend();
    });
</script>

<div class="min-h-screen text-white">
    <div class="flex flex-col lg:flex-row min-h-screen p-0 lg:p-5 gap-5">
        
        <Sidebar activePath="/chat/room"/>

        <main class="flex-1 mt-20 lg:mt-0 lg:ml-80">
            <div class="h-full backdrop-blur-2xl border border-white/10 rounded-none lg:rounded-[2.5rem] p-6 lg:p-8 shadow-2xl flex flex-col min-h-[90vh]">
                
                {#if user}
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
                            placeholder="Rechercher un contact..." 
                            class="w-full bg-white/5 border border-white/10 focus:border-blue-500 rounded-2xl py-4 pl-12 pr-4 outline-none transition-all text-sm"
                        />
                    </div>

                    <div class="flex flex-wrap gap-2 mb-8">
                        <a href="/chat/contact/all" class="flex items-center px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-xs font-semibold text-gray-400 hover:text-white hover:bg-white/10 transition-all no-underline">
                            <Icon icon="solar:users-group-rounded-linear" class="mr-2" /> All Users
                        </a>
                        <a href="/chat/contact/friend" class="flex items-center px-4 py-2 rounded-xl bg-blue-500/10 border border-blue-500/20 text-xs font-semibold text-blue-400 no-underline">
                            <Icon icon="solar:heart-bold" class="mr-2" /> Friends
                        </a>
                        <a href="/chat/contact/online" class="flex items-center px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-xs font-semibold text-gray-400 hover:text-white hover:bg-white/10 transition-all no-underline">
                            <Icon icon="solar:bolt-circle-linear" class="mr-2" /> Online
                        </a>
                    </div>

                    <div class="flex-1 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold mb-4 ml-2">Mes Amis ({friends.length})</p>
                        
                        {#if friends.length > 0}
                            {#each friends as friend}
                                <a href="/chat/contact/friend/{friend.id}" class="flex items-center p-3 bg-white/[0.02] border border-white/5 hover:border-blue-500/30 hover:bg-white/[0.05] rounded-2xl transition-all no-underline group relative">
                                    
                                    <div class="relative mr-4">
                                        <img src="https://ui-avatars.com/api/?name={friend.name}&background=1e293b&color=fff" alt="" class="w-12 h-12 rounded-xl border border-white/10 object-cover" />
                                        {#if friend.is_online}
                                            <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-[#0a0a0a] rounded-full"></span>
                                        {/if}
                                    </div>

                                    <div class="flex-1 overflow-hidden">
                                        <div class="flex items-center">
                                            <h4 class="text-white font-bold truncate group-hover:text-blue-400 transition-colors">
                                                {friend.name}
                                            </h4>
                                            {#if friend.badge > 0}
                                                <span class="ml-2 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                                                    {friend.badge}
                                                </span>
                                            {/if}
                                        </div>
                                        <p class="text-[11px] text-gray-500 flex items-center mt-0.5">
                                            {#if friend.is_online}
                                                <span class="text-green-500 font-medium italic">En ligne</span>
                                            {:else}
                                                Dernière vue : {friend.last_seen}
                                            {/if}
                                        </p>
                                    </div>

                                    <!-- Action -->
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Icon icon="solar:chat-line-linear" width="20" class="text-blue-500" />
                                    </div>
                                </a>
                            {/each}
                        {:else}
                            <div class="text-center py-20 bg-white/[0.02] rounded-[2rem] border border-dashed border-white/10">
                                <Icon icon="solar:user-plus-linear" width="48" class="mx-auto text-gray-600 mb-4" />
                                <p class="text-gray-400 font-medium">Aucun ami pour le moment</p>
                            </div>
                        {/if}
                    </div>

                {:else}
                    <div class="animate-pulse space-y-6">
                        <div class="h-14 w-64 bg-white/5 rounded-2xl"></div>
                        <div class="h-16 w-full bg-white/5 rounded-2xl"></div>
                        <div class="space-y-3">
                            <div class="h-20 w-full bg-white/5 rounded-2xl"></div>
                            <div class="h-20 w-full bg-white/5 rounded-2xl"></div>
                            <div class="h-20 w-full bg-white/5 rounded-2xl"></div>
                        </div>
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
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
</style>