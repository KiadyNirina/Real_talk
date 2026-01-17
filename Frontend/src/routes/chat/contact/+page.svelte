<script>
    import Sidebar from "../../../lib/Sidebar.svelte";
    import Icon from "@iconify/svelte";
    import { onMount, onDestroy } from "svelte";
    import { getUserInfo } from '../../../api/user';
    import { checkUsersWithFriendStatus, getUserFriends, getUserFriendOnline } from '../../../api/friend';

    let currentUser = null;
    let dataList = [];
    let searchTerms = "";
    let currentView = "all";
    let intervalId;

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const fetchData = async () => {
        try {
            if (currentView === "all") {
                dataList = await checkUsersWithFriendStatus();
            } else if (currentView === "friend") {
                dataList = await getUserFriends();
            } else if (currentView === "online") {
                dataList = await getUserFriendOnline();
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    const changeView = (view) => {
        currentView = view;
        fetchData();
    }

    onMount(async () => {
        await fetchUser();
        await fetchData();

        if (currentView === "online") {
            intervalId = setInterval(() => {
                fetchData();
            }, 10000);
        }
    });

    onDestroy(() => {
        if (intervalId) clearInterval(intervalId);
    });

    $: {
        if (currentView === "online") {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(() => {
                fetchData();
            }, 10000);
        } else {
            if (intervalId) clearInterval(intervalId);
        }
    }
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
                            placeholder="Rechercher un utilisateur..." 
                            class="w-full bg-white/5 border border-white/10 focus:border-blue-500 rounded-2xl py-4 pl-12 pr-4 outline-none transition-all text-sm"
                        />
                    </div>

                    <div class="flex flex-wrap gap-2 mb-8">
                        <button on:click={() => changeView('all')} class="flex items-center px-4 py-2 rounded-xl text-xs font-semibold no-underline transition-all {currentView === 'all' ? 'bg-blue-500/10 border border-blue-500/20 text-blue-400' : 'bg-white/5 border border-white/5 text-gray-400 hover:text-white hover:bg-white/10'}">
                            <Icon icon="solar:users-group-rounded-bold" class="mr-2" /> All Users
                        </button>
                        <button on:click={() => changeView('friend')} class="flex items-center px-4 py-2 rounded-xl text-xs font-semibold no-underline transition-all {currentView === 'friend' ? 'bg-blue-500/10 border border-blue-500/20 text-blue-400' : 'bg-white/5 border border-white/5 text-gray-400 hover:text-white hover:bg-white/10'}">
                            <Icon icon="solar:heart-bold" class="mr-2" /> Friends
                        </button>
                        <button on:click={() => changeView('online')} class="flex items-center px-4 py-2 rounded-xl text-xs font-semibold no-underline transition-all {currentView === 'online' ? 'bg-green-500/10 border border-green-500/20 text-green-400' : 'bg-white/5 border border-white/5 text-gray-400 hover:text-white hover:bg-white/10'}">
                            <Icon icon="solar:bolt-circle-bold" class="mr-2" /> Online
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                        {#if currentView === 'all'}
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold mb-4 ml-2">Découvrir du monde</p>
                            {#if dataList.length > 0}
                                {#each dataList as user}
                                    <a href="/chat/messages/{user.id}" class="flex items-center p-4 bg-white/[0.02] border border-white/5 hover:border-white/10 rounded-2xl transition-all">
                                        <img src="https://ui-avatars.com/api/?name={user.name}&background=random" alt="" class="w-12 h-12 rounded-full border border-white/10 mr-4" />
                                        <div class="flex-1">
                                            <h4 class="text-white font-bold text-sm lg:text-base">{user.name}</h4>
                                            <div class="flex items-center mt-1">
                                                {#if user.friend_status === "accepted"}
                                                    <span class="text-[10px] text-green-400 font-bold flex items-center bg-green-500/10 px-2 py-0.5 rounded-md">
                                                        <Icon icon="solar:check-circle-bold" class="mr-1" /> Ami
                                                    </span>
                                                {:else if user.friend_status === "pending"}
                                                    {#if user.sender_id !== currentUser.id}
                                                        <span class="text-[10px] text-yellow-400 font-bold flex items-center bg-yellow-500/10 px-2 py-0.5 rounded-md">
                                                            <Icon icon="solar:letter-bold" class="mr-1" /> Invitation reçue
                                                        </span>
                                                    {:else}
                                                        <span class="text-[10px] text-blue-400 font-bold flex items-center bg-blue-500/10 px-2 py-0.5 rounded-md">
                                                            <Icon icon="solar:plain-bold" class="mr-1" /> En attente
                                                        </span>
                                                    {/if}
                                                {:else}
                                                    <span class="text-[10px] text-gray-500 font-bold flex items-center">
                                                        Pas encore ami
                                                    </span>
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            {#if user.friend_status === "not_friends"}
                                                <button class="p-2.5 bg-white/5 hover:bg-blue-600 rounded-xl transition-all text-gray-400 hover:text-white border-none cursor-pointer">
                                                    <Icon icon="solar:user-plus-bold" width="18" />
                                                </button>
                                            {:else if user.friend_status === "accepted"}
                                                <a href="/chat/messages/{user.id}" class="p-2.5 bg-blue-600/20 hover:bg-blue-600 rounded-xl transition-all text-blue-400 hover:text-white no-underline">
                                                    <Icon icon="solar:chat-line-bold" width="18" />
                                                </a>
                                            {/if}
                                        </div>
                                    </a>
                                {/each}
                            {:else}
                                <p class="text-center text-gray-500 py-10">Aucun utilisateur trouvé.</p>
                            {/if}

                        {:else if currentView === 'friend'}
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold mb-4 ml-2">Mes Amis ({dataList.length})</p>
                            {#if dataList.length > 0}
                                {#each dataList as friend}
                                    <a href="/chat/messages/{friend.id}" class="flex items-center p-3 bg-white/[0.02] border border-white/5 hover:border-blue-500/30 hover:bg-white/[0.05] rounded-2xl transition-all no-underline group relative">
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

                        {:else if currentView === 'online'}
                            <div class="flex items-center justify-between mb-4 ml-2">
                                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Contacts en ligne</p>
                                <span class="flex items-center text-[10px] text-green-500 bg-green-500/10 px-2 py-1 rounded-full animate-pulse">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                    Live Sync
                                </span>
                            </div>
                            {#if dataList.length > 0}
                                {#each dataList as friend}
                                    <a href="/chat/messages/{friend.id}" class="flex items-center p-4 bg-white/[0.02] border border-white/5 hover:border-green-500/30 hover:bg-white/[0.05] rounded-2xl transition-all no-underline group">
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
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
</style>