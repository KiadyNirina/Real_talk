<script>
    import NavChat from "../../../../../lib/navlat/navChat.svelte";
    import { onMount, tick } from "svelte";
    import { getUserInfo, getUserSelectedInfo } from "../../../../../api/user";
    import { getUserFriendOnline, checkFriend, sendInvitation, cancelInvitation, acceptInvitation, rejectInvitation, deleteFriend } from "../../../../../api/friend";
    import { sendMessage, getMessage } from "../../../../../api/message";
    import { page } from "$app/stores";
    import pusher from "../../../../../lib/pusher";
    import { writable } from "svelte/store";

    // États réactifs
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

    // Fonction pour recharger la page
    const reloadPage = () => location.reload();

    // Fonction pour gérer les erreurs
    const handleError = (error, context) => {
        console.error(`Error in ${context}:`, error);
    };

    // Récupère les informations de l'utilisateur connecté
    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
            console.log('User info fetched', currentUser);
        } catch (error) {
            handleError(error, 'fetching user data');
        }
    };

    // Récupère la liste des utilisateurs en ligne
    const fetchAllUser = async () => {
        try {
            alluser = await getUserFriendOnline();
            console.log('Online users info fetched', alluser);
        } catch (error) {
            handleError(error, 'fetching online users');
        }
    };

    // Récupère les informations de l'utilisateur sélectionné
    const fetchUserSelected = async (id) => {
        try {
            userSelected = await getUserSelectedInfo(id);
            console.log('Selected user info fetched', userSelected);
        } catch (error) {
            handleError(error, 'fetching selected user data');
        }
    };

    // Récupère le statut de l'ami (ami, en attente, etc.)
    const fetchUserStatus = async (id) => {
        try {
            userSelectedStatus = await checkFriend(id);
            console.log('Selected friend status fetched', userSelectedStatus);
        } catch (error) {
            handleError(error, 'fetching friend status');
        }
    };

    // Envoie une invitation d'ami
    const sendFriendRequest = async () => {
        try {
            await sendInvitation({ receiver_id: userSelectedId });
            console.log('Friend request sent successfully!');
            reloadPage();
        } catch (error) {
            handleError(error, 'sending friend request');
        }
    };

    // Annule une invitation d'ami
    const cancelFriendRequest = async () => {
        try {
            await cancelInvitation(userSelectedStatus.id);
            console.log('Friend request canceled successfully!');
            reloadPage();
        } catch (error) {
            handleError(error, 'canceling friend request');
        }
    };

    // Accepte une invitation d'ami
    const acceptFriendRequest = async () => {
        try {
            await acceptInvitation(userSelectedStatus.id);
            console.log('Friend request accepted successfully!');
            reloadPage();
        } catch (error) {
            handleError(error, 'accepting friend request');
        }
    };

    // Rejette une invitation d'ami
    const rejectFriendRequest = async () => {
        try {
            await rejectInvitation(userSelectedStatus.id);
            console.log('Friend request rejected successfully!');
            reloadPage();
        } catch (error) {
            handleError(error, 'rejecting friend request');
        }
    };

    // Supprime un ami
    const removeFriend = async () => {
        try {
            await deleteFriend(userSelected.id);
            console.log('Friend removed successfully!');
            reloadPage();
        } catch (error) {
            handleError(error, 'removing friend');
        }
    };

    // Envoie un message
    const sendFriendMessage = async () => {
        try {
            if (!$newMessage.trim()) return;

            const newMessageData = {
                id: Date.now(), // ID temporaire
                sender_id: currentUser.id,
                receiver_id: userSelectedId,
                message: $newMessage,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString(),
            };

            // Envoyer le message temporaire (si besoin d'un affichage immédiat)
            messages.update((msgs) => [...msgs, newMessageData]);

            const response = await sendMessage({
                receiver_id: userSelectedId,
                message: $newMessage,
            });

            if (response.data && response.data.message) {
                messages.update((msgs) =>
                    msgs.map((msg) =>
                        msg.id === newMessageData.id ? response.data.message : msg
                    )
                );
            }

            newMessage.set('');
        } catch (error) {
            handleError(error, 'sending message');
            messages.update((msgs) => msgs.filter((msg) => msg.id !== newMessageData.id));
        }
    };

    // Récupère les messages
    const fetchMessages = async (id) => {
        try {
            const response = await getMessage(id);

            if (response.messages && Array.isArray(response.messages)) {
                // Tri des messages par date de création (les plus anciens en premier)
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

    // Définition du conteneur des messages pour le défilement
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
                messages.update((msgs) => [...msgs, data.message]);
                scrollToBottom();
                console.log('Message received in real-time:', data);
            } else {
                console.error('Invalid message data received from Pusher:', data);
            }
        });
    };

    // Fonction pour supprimer un message
    const deleteMessage = async (messageId) => {
        try {
            // Appel à l'API pour supprimer le message
            // await deleteMessageAPI(messageId);
            messages.update((msgs) => msgs.filter((msg) => msg.id !== messageId));
            console.log('Message deleted successfully!');
        } catch (error) {
            handleError(error, 'deleting message');
        }
    };

    // Fonction pour signaler un message
    const reportMessage = async (messageId) => {
        try {
            // Appel à l'API pour signaler le message
            // await reportMessageAPI(messageId);
            console.log('Message reported successfully!');
        } catch (error) {
            handleError(error, 'reporting message');
        }
    };

    const openMessageMenu = (messageId, type) => {
        if (selectedMessageId === messageId) {
            messageMenuOpen = !messageMenuOpen;
        } else {
            messageMenuOpen = true;
        }
        selectedMessageId = messageId;
    };

    // Initialisation du composant
    onMount(async () => {
        await fetchUser();
        await fetchAllUser();

        if (userSelectedId) {
            await fetchUserSelected(userSelectedId);
            await fetchUserStatus(userSelectedId);
            await fetchMessages(userSelectedId);
            await tick();          // Attendre que le DOM soit mis à jour après le rendu des messages
            scrollToBottom();      // Défilement vers le dernier message
            subscribeToPusher();   // Abonnement aux messages en temps réel
        }
    });

    const openMenu = () => {
        menu = !menu;
    }
</script>

<div class="body">
    <div class="content">
        {#if alertUnfriend}
            <div class="overlay"></div>
            <div class="alertLogout">
                <p>Do you really want to unfriend this user?</p>
                <div class="action">
                    <button id="yes" on:click={removeFriend}>Yes</button>
                    <button on:click={() => alertUnfriend = false}>No</button>
                </div>
            </div>
        {/if}
        <NavChat />
        {#if currentUser}
            <div class="right">
                <div class="col1">
                    <h1>Contact online</h1>
                    <div class="list">
                        {#if alluser.length > 0}
                            {#each alluser as user}
                                <a href="/chat/contact/friend/{user.id}" class="profile">
                                    <img src="/utilisateur.png" alt="">
                                    <div class="name">
                                        <p>{user.name} {#if user.name === currentUser.name}(You){/if}</p>
                                        <p class="part">online <img src="/accepter.png" alt=""></p>
                                    </div>
                                </a>
                            {/each}
                        {:else}
                            <p>No user online</p>
                        {/if}
                    </div>
                </div>
                {#if userSelected && userSelectedStatus}
                    <div class="col2">
                        <div class="profile">
                            <img src="/utilisateur.png" alt="">
                            <p>{userSelected.name}</p>
                            <button id="menu" on:click={openMenu}><img src="/menu.png" alt=""></button>
                        </div>
                        {#if menu}
                            <div class="menu">
                                <p>See profile</p>
                                {#if userSelectedStatus.status === 'accepted'}
                                    <p>Unfriend</p>
                                {:else if userSelectedStatus.status === 'pending' && userSelectedStatus.sender_id === currentUser.id}
                                    <p>Invitation sent</p>
                                {:else if userSelectedStatus.status === 'pending' && userSelectedStatus.receiver_id === currentUser.id}
                                    <p>Accept or refuse</p>
                                {:else}
                                    <p>Add</p>
                                {/if}
                                <p>Search</p>
                                <p>Report</p>
                                <p>Block</p>
                                <p>Remove content</p>
                            </div>
                        {/if}

                        <div class="message" bind:this={messageContainer}>
                            {#if $messages && $messages.length > 0}
                                {#each $messages as message}
                                    {#if message.sender_id === currentUser.id}
                                        <div class="content-message-send">
                                            <p>{message.message} <br><span>{message.created_at}</span></p>
                                            <div class="message-menu">
                                                <img src="/menu.png" alt="menu" on:click={() => openMessageMenu(message.id, 'sent')}>
                                                {#if messageMenuOpen && selectedMessageId === message.id}
                                                    <div class="message-menu-content">
                                                        <p on:click={() => deleteMessage(message.id)}>Delete</p>
                                                        <p on:click={() => reportMessage(message.id)}>Report</p>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                    {:else}
                                        <div class="content-message">
                                            <img src="/utilisateur.png" alt="">
                                            <p>{message.message} <br><span>{message.created_at}</span></p>
                                            <div class="message-menu">
                                                <img src="/menu.png" alt="menu" on:click={() => openMessageMenu(message.id, 'received')}>
                                                {#if messageMenuOpen && selectedMessageId === message.id}
                                                    <div class="message-menu-content">
                                                        <p on:click={() => reportMessage(message.id)}>Report</p>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                    {/if}
                                {/each}
                            {:else}
                                <p>No message for the moment</p>
                            {/if}
                        </div>
                        {#if userSelectedStatus.status === "pending" && userSelectedStatus.sender_id === currentUser.id}
                            <form>
                                <div class="input">
                                    <button id="cancel" on:click={cancelFriendRequest}>Invitation sent</button>
                                </div>
                            </form>
                        {:else if userSelectedStatus.status === "pending" && userSelectedStatus.receiver_id === currentUser.id}
                            <div class="input">
                                {#if !$loading}
                                    <button id="cancel" on:click={acceptFriendRequest}>Accept</button>
                                    <button id="add" on:click={rejectFriendRequest}>Reject</button>
                                {:else}
                                    <button id="cancel">Loading...</button>
                                    <button id="add">Loading...</button>
                                {/if}
                            </div>
                        {:else if userSelectedStatus.status === "accepted"}
                            <form on:submit|preventDefault={sendFriendMessage}>
                                <div class="input">
                                    <textarea bind:value={$newMessage} placeholder="Enter the message"></textarea>
                                    <button><img src="/message-sender.png" alt=""></button>
                                </div>
                            </form>
                        {:else}
                            <form on:submit|preventDefault={sendFriendRequest}>
                                <div class="input">
                                    {#if !$loading}
                                        <button id="add">Add</button>
                                    {:else}
                                        <button id="disable" disabled>Loading...</button>
                                    {/if}
                                </div>
                            </form>
                        {/if}
                    </div>
                {:else}
                    <div class="col2">
                        <div class="profile">
                            <img src="/utilisateur.png" alt="">
                            <p>User not found</p>
                        </div>
                        <div class="input">
                            <textarea placeholder="Enter the message"></textarea>
                            <button><img src="/message-sender.png" alt=""></button>
                        </div>
                    </div>
                {/if}
            </div>
        {:else}
            <div class="right">
                <div class="col1">
                    <h1>Contact online</h1>
                    <div class="list">
                        <p>Loading...</p>
                    </div>
                </div>
                <div class="col2">
                    <div class="profile">
                        <img src="/utilisateur.png" alt="">
                        <p>Loading...</p>
                    </div>
                    <div class="input">
                        <textarea placeholder="Enter the message"></textarea>
                        <button><img src="/message-sender.png" alt=""></button>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>

<style>
    .body {
        color: white;
        height: 100%;
    }
    .content {
        padding: 15px;
        display: flex;
        font-size: 20px;
        height: 100%;
    }
    .right {
        border: 1px solid rgba(255, 255, 255, 0.057);
        margin: 5px;
        padding: 15px;
        border-radius: 35px;
        display: flex;
        width: 75%;
        height: 100%;
    }
    .col1, .col2 {
        margin: 5px;
        padding: 15px;
    }
    .col1 {
        width: 25%;
        border-right: 1px solid rgba(255, 255, 255, 0.071);
    }
    .col2 {
        width: 75%;
        height: auto;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    h1 {
        font-size: 20px;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        color: rgba(255, 255, 255, 0.619);
    }
    .list {
        margin-top: 10px;
    }
    .profile {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: white;
        border-radius: 15px;
        padding: 5px;
    }
    .profile img {
        border: 1px solid rgba(255, 255, 255, 0.508);
        border-radius: 100%;
        height: 40px;
        margin-right: 15px;
    }
    .profile button {
        margin-left: auto;
        background-color: rgb(59, 59, 254);
        color: white;
        font-size: small;
        border: none;
        padding: 7px;
        border-radius: 10px;
        cursor: pointer;
    }
    #menu {
        background-color: transparent;
    }
    #menu img{
        border: none;
        height: 20px;
        display: flex;
        margin-left: auto;
        margin-right: auto;
    }
    #menu:hover {
        background-color: rgba(255, 255, 255, 0.097);
    }
    .menu {
        background-color: #202020;
        position: absolute;
        right: 60px;
        z-index: 99;
        font-size: 15px;
        font-family: 'poppins';
        border-radius: 10px;
        padding: 10px 0px;
        width: 200px;
    }
    .menu p{
        margin: 0;
        padding: 10px;
    }
    .menu p:hover{
        background-color: rgb(48, 48, 48);
        cursor: pointer;
    }
    .name {
        line-height: 5px;
    }
    .part {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.575);
        display: flex;
        align-items: center;
    }
    .part img {
        border: none;
        height: 10px;
        margin-left: 5px;
    }
    .message {
        flex: 1;
        overflow-y: auto;
        font-family: "poppins";
    }
    .content-message {
        display: flex;
        width: 60%;
        position: relative;
    }
    .content-message img {
        height: 40px;
    }
    .content-message p {
        background-color: rgba(255, 255, 255, 0.404);
        padding: 15px;
        border-radius: 15px;
        font-size: 13px;
        margin: 5px;
    }
    .content-message-send p {
        background-color: green;
        padding: 15px;
        border-radius: 15px;
        font-size: 13px;
        margin: 5px;
    }
    .content-message p span, .content-message-send p span {
        font-size: 10px;
        color: rgb(202, 202, 202);
        position: relative;
        top: 10px;
        display: flex;
        justify-content: right;
    }
    .content-message-send {
        display: flex;
        width: 60%;
        justify-content: right;
        margin-left: auto;
        position: relative;
    }
    .message-menu {
        display: none;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }
    .content-message:hover .message-menu {
        display: block;
        right: 10px;
    }
    .content-message-send:hover .message-menu {
        display: block;
        left: 10px;
    }
    .message-menu img {
        height: 20px;
        cursor: pointer;
    }
    .message-menu-content {
        background-color: #202020;
        position: absolute;
        z-index: 99;
        font-size: 15px;
        font-family: 'poppins';
        border-radius: 10px;
        padding: 10px 0px;
        width: 100px;
        top: 30px;
        right: 0;
    }
    .message-menu-content p {
        margin: 0;
        padding: 10px;
    }
    .message-menu-content p:hover {
        background-color: rgb(48, 48, 48);
        cursor: pointer;
    }
    .input {
        display: flex;
        align-items: center;
        margin-top: 5px;
        position: sticky;
        bottom: 0;
        width: 100%;
        background-color: #33333300;
        color: #fff;
        text-align: center;
        padding: 10px 20px;
    }
    .input button {
        background-color: transparent;
        border: none;
        border-radius: 15px;
        margin-left: 5px;
    }
    .input img {
        height: 30px;
        padding: 8px;
    }
    .input button:hover {
        background-color: rgb(36, 36, 36);
        cursor: pointer;
    }
    .input textarea {
        width: 100%;
        border-radius: 15px;
        background: rgb(45, 45, 45);
        padding: 10px;
        border: none;
        color: white;
    }
    #add {
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.287);
        padding: 10px;
        color: white;
        font-size: 15px;
    }
    #disable {
        cursor: not-allowed;
        color: grey;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.287);
        padding: 10px;
        font-size: 15px;
    }
    #cancel {
        width: 100%;
        background: rgb(0, 81, 255);
        padding: 10px;
        color: white;
        font-size: 15px;
    }
    .alertLogout {
        position: fixed;
        right: 40%;
        left: 40%;
        border: 1px solid rgba(255, 255, 255, 0.19);
        background-color: rgb(37, 37, 37);
        text-align: center;
        top: 40%;
        border-radius: 15px;
        padding: 5px;
        z-index: 9;
    }
    .alertLogout .action {
        display: flex;
    }
    .alertLogout .action button {
        text-align: center;
        padding: 10px;
        width: 50%;
        background-color: transparent;
        border: none;
        color: white;
    }
    #yes:hover {
        background-color: rgba(255, 0, 0, 0.496);
    }
    .alertLogout .action button:hover {
        cursor: pointer;
        background-color: rgba(128, 128, 128, 0.692);
        border-radius: 15px;
    }
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.671);
        z-index: 5;
    }
    @media screen and (max-width: 1000px) {
        .col1 {
            display: none;
        }
        .col2 {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .message {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 200px;
        }
        .content-message, .content-message-send {
            width: 80%;
        }
        .input {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #1b1b1b;
            color: #fff;
            text-align: center;
            padding: 5px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }
    }
    @media screen and (max-width: 700px) {
        .content {
            display: block;
            height: auto;
            width: auto;
            padding: 0;
        }
        .right {
            width: 92%;
            padding: 0;
            margin-top: 100px;
            position: fixed;
        }
        .message {
            border: none;
            border-radius: 10px;
            padding: 0px;
            height: auto;
            overflow-y: auto;
        }
        .message p {
            font-size: 12px;
            line-height: 17px;
            padding: 10px;
        }
        .alertLogout {
            right: 10%;
            left: 10%;
        }
        .content-message p span, .content-message-send p span {
            font-size: 8px;
            color: rgb(202, 202, 202);
            position: relative;
            top: 0px;
        }
    }
</style>