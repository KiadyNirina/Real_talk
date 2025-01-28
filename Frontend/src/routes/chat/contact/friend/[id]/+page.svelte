<script>
    import NavChat from "../../../../../lib/navlat/navChat.svelte";
    import { onMount } from "svelte";
    import { getUserInfo, getUserSelectedInfo } from "../../../../../api/user";
    import { getUserFriendOnline, checkFriend, sendInvitation, acceptInvitation, rejectInvitation, deleteFriend } from "../../../../../api/friend";
    import { sendMessage, getMessage } from "../../../../../api/message";
    import { page } from "$app/stores";
    import pusher from "../../../../../lib/pusher";
    import axios from "axios";
    import { writable } from "svelte/store";

    let currentUser = null;
    let invitationSended;
    let invitationAccepted;
    let invitationRejected;
    let alluser = [];
    let userSelectedId = null;
    let userSelected = null;
    let userSelected2 = null;

    $: userSelectedId = $page.params.id;

    let formData = {
        receiver_id: $page.params.id,
    };

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
            console.log('User info fetched', currentUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    };

    const fetchAllUser = async () => {
        try {
            alluser = await getUserFriendOnline();
            console.log('Online users info fetched', alluser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    };

    const fetchUserSelected = async (id) => {
        try {
            userSelected = await getUserSelectedInfo(id);
            console.log('Selected user info fetched', userSelected);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    };

    const reloadPage = () => {
        location.reload();
    };

    const sendFriendRequest = async () => {
        try {
            invitationSended = await sendInvitation(formData);
            console.log('Friend request sent successfully!', invitationSended);
            reloadPage();
        } catch (error) {
            console.error('Error sending friend request:', error);
        }
    };

    const acceptFriendRequest = async (invitation) => {
        try {
            invitationAccepted = await acceptInvitation(invitation);
            console.log('Friend request accepted successfully!', invitationAccepted);
            reloadPage();
        } catch (error) {
            console.error('Error accepting friend request:', error);
        }
    };

    const rejectFriendRequest = async (invitation) => {
        try {
            invitationRejected = await rejectInvitation(invitation);
            console.log('Friend request rejected successfully!', invitationRejected);
            reloadPage();
        } catch (error) {
            console.error('Error rejecting friend request:', error);
        }
    };

    const seeFriendStatus = async (id) => {
        try {
            userSelected2 = await checkFriend(id);
            console.log('Selected friend status fetched', userSelected2);
        } catch (error) {
            console.error('Error fetching friend status:', error);
        }
    };

    const removeFriend = async (id) => {
        try {
            await deleteFriend(id);
            console.log('Friend removed successfully!');
            reloadPage();
        } catch (error) {
            console.error('Error removing friend:', error);
        }
    };

    let loading = false;

    function handleClick() {
        loading = true;
        setTimeout(async () => {
            await sendFriendRequest();
            loading = false;
        }, 5000);
    }

    function handleClickAccept() {
        loading = true;
        setTimeout(async () => {
            await acceptFriendRequest(userSelected2.id);
            loading = false;
        }, 5000);
    }

    function handleClickReject() {
        loading = true;
        setTimeout(async () => {
            await rejectFriendRequest(userSelected2.id);
            loading = false;
        }, 5000);
    }

    let alertUnfriend = false;

    const handleUnfriend = () => {
        alertUnfriend = true;
    };

    const unfriendYes = () => {
        loading = true;
        setTimeout(async () => {
            await removeFriend(userSelected.id);
            loading = false;
        }, 5000);
    };

    const unfriendNo = () => {
        alertUnfriend = false;
    };

    export let messages = writable([]);
    export let newMessage = writable('');

    const sendFriendMessage = async () => {
        try {
            if (!$newMessage.trim()) return;

            await axios.post('http://localhost:8000/api/send-message', {
                receiver_id: $page.params.id,
                message: $newMessage,
            }, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            });

            newMessage.set('');
        } catch (error) {
            console.error('Error sending message:', error);
        }
    };

    const fetchMessage = async (id) => {
        try {
            const response = await axios.get(`http://localhost:8000/api/messages/${id}`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            });
            messages.set(response.data);
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    };

    onMount(async () => {
        await fetchUser();
        await fetchAllUser();

        if (userSelectedId) {
            await fetchUserSelected(userSelectedId);
            await seeFriendStatus(userSelectedId);
            await fetchMessage(userSelectedId);
        }

        const channel = pusher.subscribe(`chat.${userSelectedId}`);
        channel.bind('MessageSent', (data) => {
            messages.update((msgs) => [...msgs, data.message]);
        });
    });
</script>

<div class="body">
    <div class="content">
        {#if alertUnfriend}
            <div class="overlay"></div>
            <div class="alertLogout">
                <p>Do you really want to unfriend this user?</p>
                <div class="action">
                    <button id="yes" on:click={unfriendYes}>Yes</button>
                    <button on:click={unfriendNo}>No</button>
                </div>
            </div>
        {/if}
        <NavChat/>
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
                                    <p>{user.name} {#if user.name == currentUser.name}
                                        (You)
                                    {/if}</p>
                                    <p class="part">
                                        online
                                        <img src="/accepter.png" alt="">
                                    </p>
                                </div>
                            </a>
                        {/each}
                    {:else}
                        <p>No user online</p>
                    {/if}
                </div>
            </div>
            {#if userSelected && userSelected2}
                <div class="col2">
                    <div class="profile">
                        <img src="/utilisateur.png" alt="">
                        <p>{userSelected.name}</p>
                        {#if userSelected2.status == 'accepted'}
                            <button on:click={handleUnfriend}>Friend ✔️</button>
                        {/if}
                    </div>
                    <div class="message">
                        {#if messages.length > 0}
                            {#each messages as message}
                                {#if message.sender_id === currentUser.id}
                                    <div class="content-message-send">
                                        <p>
                                            {message.message} <br>
                                            <span>{message.created_at}</span>
                                        </p>
                                    </div>
                                {:else}
                                    <div class="content-message">
                                        <img src="/utilisateur.png" alt="">
                                        <p>
                                            {message.message} <br>
                                            <span>{message.created_at}</span>
                                        </p>
                                    </div>
                                {/if}
                            {/each}
                        {:else}
                            <p>No message for the moment</p>
                        {/if}
                    </div>
                    {#if userSelected2.status === "pending" && userSelected2.sender_id === currentUser.id}
                        <form>
                            <div class="input">
                                <button id="cancel">Invitation sent</button>
                            </div>
                        </form>
                    {:else if userSelected2.status === "pending" && userSelected2.receiver_id === currentUser.id}
                            <div class="input">
                                {#if !loading}
                                    <button id="cancel" on:click={handleClickAccept}>Accept</button>
                                    <button id="add" on:click={handleClickReject}>Reject</button>
                                {:else}
                                    <button id="cancel">Loading...</button>
                                    <button id="add">Loading...</button>
                                {/if}
                            </div>
                    {:else if userSelected2.status === "accepted"}
                        <form on:submit={sendFriendMessage}>
                            <div class="input">
                                <textarea bind:value={$newMessage} placeholder="Enter the message"></textarea>
                                <button><img src="/message-sender.png" alt=""></button>
                            </div>
                        </form>
                    {:else}
                        <form on:submit={handleClick}>
                            <div class="input">
                                {#if !loading}
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
        border: 1px solid rgba(255, 255, 255, 0.165);
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
    .profile:hover {
        background-color: rgba(255, 255, 255, 0.097);
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
    }
    .content-message {
        display: flex;
        width: 60%;
    }
    .content-message img {
        height: 40px;
    }
    .content-message p {
        background-color: rgba(255, 255, 255, 0.404);
        padding: 15px;
        border-radius: 15px;
        font-size: 15px;
        margin: 5px;
    }
    .content-message-send p {
        background-color: green;
        padding: 15px;
        border-radius: 15px;
        font-size: 15px;
        margin: 5px;
    }
    .content-message p span, .content-message-send p span {
        font-size: 10px;
        color: rgb(202, 202, 202);
        position: relative;
        top: 10px;
    }
    .content-message-send {
        width: 60%;
        margin-left: auto;
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
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
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
            margin-bottom: 50px;
        }
        .message {
            flex: 1;
            overflow-y: auto;
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
            width: auto;
            padding: 0;
            height: 100%;
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
        }
        .message img {
            height: 20px;
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