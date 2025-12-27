<script>
    import Sidebar from "../../../../lib/Sidebar.svelte";
    import Icon from "@iconify/svelte";
    import { onMount } from "svelte";
    import { getUserInfo } from '../../../../api/user';
    import { checkUsersWithFriendStatus } from '../../../../api/friend';

    let currentUser = null;
    let allUsers = [];
    let searchTerms = "";

    const fetchUser = async () => {
        try {
            // Simulation
            currentUser = { id: 1, name: "Moi" };
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const fetchAllUsers = async () => {
        try {
            allUsers = await checkUsersWithFriendStatus();
            console.log("All users : ", allUsers)
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    onMount(async () => {
        await fetchUser();
        await fetchAllUsers();
    });
</script>

<div class="min-h-screen text-white">
    <div class="flex flex-col lg:flex-row min-h-screen p-0 lg:p-5 gap-5">
        
        <Sidebar activePath="/chat/room"/>

        <main class="flex-1 mt-20 lg:mt-0 lg:ml-80">
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
                            placeholder="Rechercher un utilisateur par nom..." 
                            class="w-full bg-white/5 border border-white/10 focus:border-blue-500 rounded-2xl py-4 pl-12 pr-4 outline-none transition-all text-sm"
                        />
                    </div>

                    <div class="flex flex-wrap gap-2 mb-8">
                        <a href="/chat/contact/all" class="flex items-center px-4 py-2 rounded-xl bg-blue-500/10 border border-blue-500/20 text-xs font-semibold text-blue-400 no-underline">
                            <Icon icon="solar:users-group-rounded-bold" class="mr-2" /> All Users
                        </a>
                        <a href="/chat/contact/friend" class="flex items-center px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-xs font-semibold text-gray-400 hover:text-white hover:bg-white/10 transition-all no-underline">
                            <Icon icon="solar:heart-linear" class="mr-2" /> Friends
                        </a>
                        <a href="/chat/contact/online" class="flex items-center px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-xs font-semibold text-gray-400 hover:text-white hover:bg-white/10 transition-all no-underline">
                            <Icon icon="solar:bolt-circle-linear" class="mr-2" /> Online
                        </a>
                    </div>

                    <div class="flex-1 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold mb-4 ml-2">Découvrir du monde</p>
                        
                        {#if allUsers.length > 0}
                            {#each allUsers as user}
                                <a href="/chat/contact/friend/{user.id}" class="flex items-center p-4 bg-white/[0.02] border border-white/5 hover:border-white/10 rounded-2xl transition-all">
                                    
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
                                            <a href="/chat/contact/friend/{user.id}" class="p-2.5 bg-blue-600/20 hover:bg-blue-600 rounded-xl transition-all text-blue-400 hover:text-white no-underline">
                                                <Icon icon="solar:chat-line-bold" width="18" />
                                            </a>
                                        {/if}
                                    </div>
                                </a>
                            {/each}
                        {:else}
                            <p class="text-center text-gray-500 py-10">Aucun utilisateur trouvé.</p>
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