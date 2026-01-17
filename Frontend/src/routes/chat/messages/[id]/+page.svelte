<script>
    import Sidebar from "../../../../lib/Sidebar.svelte";
    import { onMount, tick } from "svelte";
    import { getUserInfo, getUserSelectedInfo } from "../../../../api/user";
    import { getUserFriendOnline, checkFriend, sendInvitation, cancelInvitation, acceptInvitation, rejectInvitation, deleteFriend } from "../../../../api/friend";
    import { sendMessage, getMessage } from "../../../../api/message";
    import { page } from "$app/stores";
    import pusher from "../../../../lib/pusher";
    import { writable } from "svelte/store";
    import Icon from "@iconify/svelte";

    let currentUser = null;
    let alluser = [];
    let userSelected = null;
    let userSelectedStatus = null;
    let alertUnfriend = false;
    let loading = writable(false);
    let menu = false;
    let messageMenuOpen = false;
    let selectedMessageId = null;

    // États pour les messages
    export let messages = writable([]);
    export let newMessage = writable('');

    // Récupère l'ID de l'utilisateur sélectionné depuis l'URL
    $: userSelectedId = $page.params.id;

    const reloadPage = () => location.reload();

    const handleError = (error, context) => {
        console.error(`Error in ${context}:`, error);
    };

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
        } catch (error) {
            handleError(error, 'fetching user data');
        }
    };

    const fetchAllUser = async () => {
        try {
            alluser = await getUserFriendOnline();
            console.log('Online users info fetched', alluser);
        } catch (error) {
            handleError(error, 'fetching online users');
        }
    };

    const fetchUserSelected = async (id) => {
        try {
            userSelected = await getUserSelectedInfo(id);
            console.log('Selected user info fetched', userSelected);
        } catch (error) {
            handleError(error, 'fetching selected user data');
        }
    };

    const fetchUserStatus = async (id) => {
        try {
            userSelectedStatus = await checkFriend(id);
            console.log('Selected friend status fetched', userSelectedStatus);
        } catch (error) {
            handleError(error, 'fetching friend status');
        }
    };

    const sendFriendRequest = async () => {
        try {
            await sendInvitation({ receiver_id: userSelectedId });
            console.log('Friend request sent successfully!');
            reloadPage();
        } catch (error) {
            handleError(error, 'sending friend request');
        }
    };

    const cancelFriendRequest = async () => {
        try {
            await cancelInvitation(userSelectedStatus.id);
            console.log('Friend request canceled successfully!');
            reloadPage();
        } catch (error) {
            handleError(error, 'canceling friend request');
        }
    };

    const acceptFriendRequest = async () => {
        try {
            await acceptInvitation(userSelectedStatus.id);
            reloadPage();
        } catch (error) {
            handleError(error, 'accepting friend request');
        }
    };

    const rejectFriendRequest = async () => {
        try {
            await rejectInvitation(userSelectedStatus.id);
            reloadPage();
        } catch (error) {
            handleError(error, 'rejecting friend request');
        }
    };

    const removeFriend = async () => {
        try {
            await deleteFriend(userSelected.id);
            reloadPage();
        } catch (error) {
            handleError(error, 'removing friend');
        }
    };

    const sendFriendMessage = async () => {
        try {
            if (!$newMessage.trim()) return;

            const tempId = Date.now();
            const newMessageData = {
                id: tempId,
                sender_id: currentUser.id,
                receiver_id: userSelectedId,
                message: $newMessage,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString(),
            };

            messages.update((msgs) => [...msgs, newMessageData]);
            const textToSend = $newMessage;
            newMessage.set('');
            
            await tick();
            scrollToBottom();

            const response = await sendMessage({
                receiver_id: userSelectedId,
                message: textToSend,
            });

            if (response.data && response.data.message) {
                messages.update((msgs) =>
                    msgs.map((msg) =>
                        msg.id === tempId ? response.data.message : msg
                    )
                );
            }
        } catch (error) {
            handleError(error, 'sending message');
            messages.update((msgs) => msgs.filter((msg) => msg.id !== newMessageData.id));
        }
    };

    const fetchMessages = async (id) => {
        try {
            const response = await getMessage(id);
            if (response.messages && Array.isArray(response.messages)) {
                const sortedMessages = response.messages.sort((a, b) =>
                    new Date(a.created_at) - new Date(b.created_at)
                );
                messages.set(sortedMessages);
            } else {
                console.error('Invalid data format received from API:', response);
                messages.set([]);
            }
        } catch (error) {
            handleError(error, 'fetching messages');
        }
    };

    let messageContainer;
    const scrollToBottom = () => {
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    };

    const subscribeToPusher = () => {
        const channel = pusher.subscribe(`chat.${userSelectedId}`);
        channel.bind('MessageSent', async (data) => {
            if (data.message) {
                messages.update((msgs) => {
                    if (msgs.find(m => m.id === data.message.id)) return msgs;
                    return [...msgs, data.message];
                });
                await tick();
                scrollToBottom();
            }
        });
    };

    const deleteMessage = async (messageId) => {
        try {
            messages.update((msgs) => msgs.filter((msg) => msg.id !== messageId));
            messageMenuOpen = false;
        } catch (error) {
            handleError(error, 'deleting message');
        }
    };

    const openMessageMenu = (messageId) => {
        selectedMessageId = selectedMessageId === messageId && messageMenuOpen ? null : messageId;
        messageMenuOpen = !!selectedMessageId;
    };

    onMount(async () => {
        await fetchUser();
        await fetchAllUser();

        if (userSelectedId) {
            await fetchUserSelected(userSelectedId);
            await fetchUserStatus(userSelectedId);
            await fetchMessages(userSelectedId);
            await tick();
            scrollToBottom();
            subscribeToPusher();
        }
    });

    const toggleMenu = () => menu = !menu;
</script>

<div class="min-h-screen text-white font-['Poppins']">
    <!-- Overlay Suppression Ami -->
    {#if alertUnfriend}
        <div class="fixed inset-0 bg-black/80 backdrop-blur-md z-[100] flex items-center justify-center p-4">
            <div class="bg-[#1a1a1a] border border-white/10 p-8 rounded-[2.5rem] max-w-sm w-full text-center shadow-2xl">
                <div class="w-16 h-16 bg-red-500/10 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <Icon icon="solar:user-minus-bold" width="32" />
                </div>
                <h3 class="text-xl font-bold mb-2">Supprimer l'ami ?</h3>
                <p class="text-gray-400 text-sm mb-8">Voulez-vous vraiment retirer {userSelected?.name} de vos contacts ?</p>
                <div class="flex gap-3">
                    <button on:click={() => alertUnfriend = false} class="flex-1 py-3 rounded-xl bg-white/5 hover:bg-white/10 font-semibold transition-all border-none text-white cursor-pointer">Annuler</button>
                    <button on:click={removeFriend} class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 font-semibold transition-all border-none text-white cursor-pointer">Supprimer</button>
                </div>
            </div>
        </div>
    {/if}

    <div class="flex flex-col lg:flex-row h-screen p-0 lg:p-5 gap-5">
        
        <Sidebar activePath="/chat/room"/>

        <main class="flex-1 lg:mt-0 lg:ml-80 flex gap-5 overflow-hidden">
            
            <!-- Liste Online -->
            <div class="hidden xl:flex flex-col w-72 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] p-6 shadow-2xl">
                <h2 class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold mb-6">Contacts en ligne</h2>
                <div class="space-y-2 overflow-y-auto custom-scrollbar">
                    {#if alluser.length === 0}
                        <p class="text-sm text-gray-500">Aucun contact en ligne.</p>
                    {/if}

                    {#each alluser as user}
                        <a href="/chat/contact/friend/{user.id}" class="flex items-center p-3 rounded-2xl hover:bg-white/5 transition-all no-underline group {user.id == userSelectedId ? 'bg-blue-600/10 border border-blue-500/20' : ''}">
                            <div class="relative mr-3">
                                <img src="https://ui-avatars.com/api/?name={user.name}&background=random" alt="" class="w-10 h-10 rounded-full" />
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#0a0a0a] rounded-full"></span>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-sm font-semibold truncate {user.id == userSelectedId ? 'text-blue-400' : 'text-gray-300'}">{user.name}</p>
                                <p class="text-[10px] text-green-500 font-medium">En ligne</p>
                            </div>
                        </a>
                    {/each}
                </div>
            </div>

            <!-- Fenêtre de Chat -->
            <div class="flex-1 backdrop-blur-2xl border border-white/10 rounded-none lg:rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden relative">
                
                {#if userSelected && userSelectedStatus}
                    <header class="p-4 lg:p-6 border-b border-white/10 flex items-center justify-between bg-white/[0.02]">
                        <div class="flex items-center">
                            <div class="relative mr-4">
                                <img src="https://ui-avatars.com/api/?name={userSelected.name}&background=0284c7&color=fff" alt="" class="w-12 h-12 rounded-2xl shadow-lg" />
                                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-[#0a0a0a] rounded-full"></span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg m-0">{userSelected.name}</h3>
                                <p class="text-xs text-green-500 flex items-center font-medium m-0 mt-1">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                    En ligne
                                </p>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <button on:click={toggleMenu} class="p-3 hover:bg-white/5 rounded-xl transition-all border-none bg-transparent cursor-pointer text-gray-400">
                                <Icon icon="solar:menu-dots-bold" width="24" />
                            </button>
                            
                            {#if menu}
                                <div class="absolute right-0 mt-2 w-56 bg-[#1a1a1a] border border-white/10 rounded-2xl shadow-2xl z-50 py-2 overflow-hidden animate-in fade-in zoom-in duration-200">
                                    <button class="w-full text-left px-4 py-3 text-sm hover:bg-white/5 flex items-center bg-transparent border-none text-white cursor-pointer transition-colors">
                                        <Icon icon="solar:user-circle-linear" class="mr-3" width="18" /> Profil
                                    </button>
                                    {#if userSelectedStatus.status === 'accepted'}
                                        <button on:click={() => { alertUnfriend = true; menu = false; }} class="w-full text-left px-4 py-3 text-sm hover:bg-red-500/10 text-red-400 flex items-center bg-transparent border-none cursor-pointer font-medium transition-colors">
                                            <Icon icon="solar:user-minus-linear" class="mr-3" width="18" /> Supprimer l'ami
                                        </button>
                                    {/if}
                                    <button class="w-full text-left px-4 py-3 text-sm hover:bg-white/5 flex items-center bg-transparent border-none text-gray-500 cursor-pointer transition-colors">
                                        <Icon icon="solar:shield-warning-linear" class="mr-3" width="18" /> Signaler
                                    </button>
                                </div>
                            {/if}
                        </div>
                    </header>

                    <!-- Zone Messages -->
                    <div 
                        class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] bg-fixed"
                        bind:this={messageContainer}
                    >
                    {#if $messages.length === 0}
                        <p class="text-sm text-gray-500 text-center mt-10">Aucun message. Commencez la conversation !</p>
                    {/if}

                        {#each $messages as msg (msg.id)}
                            <div class="flex {msg.sender_id == currentUser?.id ? 'justify-end' : 'justify-start'} group relative">
                                <div class="max-w-[80%] lg:max-w-[60%] flex items-end gap-3">
                                    {#if msg.sender_id != currentUser?.id}
                                        <img src="https://ui-avatars.com/api/?name={userSelected.name}" alt="" class="w-8 h-8 rounded-full mb-1 border border-white/10" />
                                    {/if}
                                    
                                    <div class="relative group">
                                        <div class="px-5 py-3 rounded-[1.5rem] text-sm leading-relaxed
                                            {msg.sender_id == currentUser?.id 
                                                ? 'bg-blue-600 text-white rounded-br-none shadow-lg shadow-blue-600/10' 
                                                : 'bg-white/10 text-gray-200 rounded-bl-none backdrop-blur-md border border-white/5'}">
                                            {msg.message}
                                            <div class="text-[9px] mt-2 opacity-50 text-right font-medium">
                                                {new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                                            </div>
                                        </div>

                                        <!-- Menu Message -->
                                        <button 
                                            class="absolute top-0 {msg.sender_id == currentUser?.id ? '-left-8' : '-right-8'} p-1 text-gray-500 hover:text-white opacity-0 group-hover:opacity-100 transition-all border-none bg-transparent cursor-pointer"
                                            on:click={() => openMessageMenu(msg.id)}
                                        >
                                            <Icon icon="solar:menu-dots-bold" width="14" />
                                        </button>

                                        {#if messageMenuOpen && selectedMessageId === msg.id}
                                            <div class="absolute {msg.sender_id == currentUser?.id ? 'right-0' : 'left-0'} top-full mt-2 z-[60] bg-[#222] border border-white/10 rounded-xl py-2 shadow-2xl min-w-[120px]">
                                                {#if msg.sender_id == currentUser?.id}
                                                    <button on:click={() => deleteMessage(msg.id)} class="w-full text-left px-4 py-2 text-xs hover:bg-red-500/20 text-red-400 border-none bg-transparent cursor-pointer">Supprimer</button>
                                                {/if}
                                                <button class="w-full text-left px-4 py-2 text-xs hover:bg-white/10 text-white border-none bg-transparent cursor-pointer">Signaler</button>
                                            </div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        {/each}
                    </div>

                    <!-- Input / Actions Statut -->
                    <footer class="p-2 lg:p-6 bg-white/[0.02] border-t border-white/10 mb-[80px] lg:mb-0">
                        {#if userSelectedStatus.status === "accepted"}
                            <form on:submit|preventDefault={sendFriendMessage} class="flex items-center gap-3 bg-white/5 border border-white/10 p-2 rounded-2xl focus-within:border-blue-500/50 transition-all shadow-inner">
                                <button type="button" class="p-3 text-gray-500 hover:text-white transition-colors bg-transparent border-none cursor-pointer">
                                    <Icon icon="solar:paperclip-linear" width="22" />
                                </button>
                                <textarea 
                                    bind:value={$newMessage}
                                    placeholder="Écrivez votre message..." 
                                    class="flex-1 bg-transparent border-none outline-none text-white text-sm py-2 resize-none max-h-32"
                                    rows="1"
                                    on:keydown={(e) => e.key === 'Enter' && !e.shiftKey && (e.preventDefault(), sendFriendMessage())}
                                ></textarea>
                                <button 
                                    type="submit"
                                    disabled={!$newMessage.trim()}
                                    class="p-3 bg-blue-600 hover:bg-blue-500 disabled:bg-gray-800 disabled:text-gray-500 text-white rounded-xl transition-all border-none cursor-pointer shadow-lg shadow-blue-600/20"
                                >
                                    <Icon icon="solar:paper-plane-bold" width="20" />
                                </button>
                            </form>
                        {:else if userSelectedStatus.status === "pending" && userSelectedStatus.sender_id === currentUser?.id}
                            <div class="bg-blue-500/10 border border-blue-500/20 p-5 rounded-[1.5rem] flex flex-col md:flex-row items-center justify-between gap-4">
                                <div class="flex items-center text-blue-400 text-sm">
                                    <Icon icon="solar:info-circle-bold" class="mr-3" width="24" />
                                    Invitation en attente de réponse.
                                </div>
                                <button on:click={cancelFriendRequest} class="px-6 py-2 bg-red-500/20 hover:bg-red-500/40 text-red-400 rounded-xl text-xs font-bold border-none cursor-pointer transition-all">
                                    Annuler l'invitation
                                </button>
                            </div>
                        {:else if userSelectedStatus.status === "pending" && userSelectedStatus.receiver_id === currentUser?.id}
                            <div class="flex flex-col gap-3">
                                <p class="text-xs text-gray-400 text-center mb-1">{userSelected.name} vous a envoyé une invitation</p>
                                <div class="flex gap-4">
                                    <button on:click={acceptFriendRequest} class="flex-1 py-4 bg-blue-600 hover:bg-blue-500 rounded-2xl font-bold border-none text-white cursor-pointer transition-all shadow-lg shadow-blue-600/20">
                                        Accepter
                                    </button>
                                    <button on:click={rejectFriendRequest} class="flex-1 py-4 bg-white/5 hover:bg-red-500/20 rounded-2xl font-bold border-none text-white cursor-pointer transition-all">
                                        Refuser
                                    </button>
                                </div>
                            </div>
                        {:else}
                            <button on:click={sendFriendRequest} class="w-full py-5 bg-blue-600 hover:bg-blue-500 rounded-2xl font-bold text-white cursor-pointer transition-all flex items-center justify-center shadow-lg shadow-blue-600/20">
                                <Icon icon="solar:user-plus-bold" class="mr-3" width="24" />
                                Ajouter aux amis
                            </button>
                        {/if}
                    </footer>
                {:else}
                    <div class="flex-1 flex flex-col items-center justify-center text-gray-600">
                        <Icon icon="solar:ghost-linear" width="64" class="mb-4 opacity-20" />
                        <p class="text-sm font-medium tracking-widest uppercase opacity-40">Sélectionnez une conversation</p>
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