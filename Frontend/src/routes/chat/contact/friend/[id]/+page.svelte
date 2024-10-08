<script>
    import NavChat from "../../../../../lib/navlat/navChat.svelte";
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";
    import { getUserInfo, getUserFriendOnline, getUserSelectedInfo, sendInvitation, checkFriend, acceptInvitation, rejectInvitation, sendMessage, getMessage } from "../../../../../lib/auth";
    import { page } from "$app/stores";
    //import { echo } from "../../../../../lib/echo";

    let intervalId;
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
        receiver_id : $page.params.id,
    }

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
            console.log('Informations de l’utilisateur récupérées', currentUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const fetchAllUser = async () => {
        try {
            alluser = await getUserFriendOnline();
            console.log('Informations des utilisateurs online récupérées', alluser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const fetchUserSelected = async (id) => {
        try {
            userSelected = await getUserSelectedInfo(id);
            console.log('Informations de l’utilisateur séléctionné récupérées', userSelected);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const sendFriendRequest = async() => {
        try {
            invitationSended = await sendInvitation(formData);
            console.log('Friend request sent with success!', invitationSended);
        } catch (error) {
            console.error('Error sending friend request:', error);
        }
    }

    const acceptFriendRequest = async (invitation) => {
        try {
            invitationAccepted = await acceptInvitation(invitation);
            console.log('Friend request accepted with success!', invitationAccepted);
        } catch (error) {
            console.error('Error sending friend request:', error);
        }
    }

    const rejectFriendRequest = async (invitation) => {
        try {
            invitationRejected = await rejectInvitation(invitation);
            console.log('Friend request accepted with success!', invitationRejected);
        } catch (error) {
            console.error('Error sending friend request:', error);
        }
    }

    const seeFriendStatus = async (id) => {
        try {
            userSelected2 = await checkFriend(id);
            console.log('Informations de l’utilisateur friend séléctionné récupérées', userSelected2);
            
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    let loading = false;

    function handleClick() {
        loading = true; 
        setTimeout(async () => {
            await sendFriendRequest(); 
            loading = false;
        }, 5000);
    }

    function handleClickAccept () {
        loading = true;
        setTimeout(async () => {
            await acceptFriendRequest(userSelected2.id); 
            loading = false;
        }, 5000);
    }

    function handleClickReject () {
        loading = true;
        setTimeout(async () => {
            await rejectFriendRequest(userSelected2.id); 
            loading = false;
        }, 5000);
    }

    let alertUnfriend = false;

    const handleUnfriend = () => {
        alertUnfriend = true;
    }

    const unfriendYes = () => {
        goto('/');
    }

    const unfriendNo = () => {
        alertUnfriend = false;
    }

    let messageSent;
    let allMess = [];

    let messages = {
        receiver_id: $page.params.id,
        message: '',
    }

    // Fonction pour envoyer le message
    const sendFriendMessage = async () => {
        try {
            messageSent = await sendMessage(messages);
            console.log('Message sent with success!', messageSent);
        } catch (error) {
            console.error('Error sending message:', error);
        }
    }

    const fetchMessage = async (id) => {
        try {
            allMess = await getMessage(id);
            console.log('Message fetched with success!', allMess);
        } catch (error) {
            console.error('Error fetching message:', error);
        }
    }

    onMount(async () => {
        await fetchUser();
        await fetchAllUser();

        if(userSelectedId) {
            await fetchUserSelected(userSelectedId);
            await seeFriendStatus(userSelectedId);
            await fetchMessage(userSelectedId);
        }

        /*echo.private(`chat.${userSelectedId}`)
        .listen("MessageSent", (e) => {
            messages.update(currentMessages => [...currentMessages, e.message]);
        });*/

        // Polling toutes les 5 secondes
        intervalId = setInterval(() => {
            fetchAllUser();
            seeFriendStatus(userSelectedId);
            fetchMessage(userSelectedId);
        }, 5000);

        // Nettoyer l'intervalle au démontage
        return () => {
        clearInterval(intervalId);
        };
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
                            <a href="/chat/contact/friend/2" class="profile">
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
                        {#each allMess as message}
                            {#if message.sender_id === currentUser.id}
                                <div class="content-message-send">
                                    <p>
                                        {message.message}
                                    </p>
                                </div>
                            {:else}
                                <div class="content-message">
                                    <img src="/utilisateur.png" alt="">
                                    <p>
                                        {message.message}
                                    </p>
                                </div>
                            {/if}
                        {/each}
                    </div>
                    {#if userSelected2.status === "pending" && userSelected2.sender_id === currentUser.id}
                        <form>
                            <div class="input">
                                <button id="cancel">Invitation envoyé</button>
                            </div>
                        </form>
                    {:else if userSelected2.status === "pending" && userSelected2.receiver_id === currentUser.id}
                            <div class="input">
                                {#if loading == false}
                                    <button id="cancel" on:click={handleClickAccept}>Accepter</button>
                                {:else}
                                    <button id="cancel">Loading...</button>
                                {/if}

                                {#if loading == false}
                                    <button id="add" on:click={handleClickReject}>Reffuser</button>
                                {:else}
                                    <button id="add">Loading...</button>
                                {/if}
                            </div>
                            
                    {:else if userSelected2.status === "accepted"}
                        <form on:submit={sendFriendMessage}>
                            <div class="input">
                                <textarea bind:value={messages.message} id="" placeholder="Enter the message"></textarea>
                                <button><img src="/message-sender.png" alt=""></button>
                            </div>
                        </form>
                    {:else}
                        <form on:submit={handleClick}>
                            <div class="input">
                                {#if loading == false}
                                    <button id="add">Ajouter</button>
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
                        <textarea name="" id="" placeholder="Enter the message"></textarea>
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
                    <textarea name="" id="" placeholder="Enter the message"></textarea>
                    <button><img src="/message-sender.png" alt=""></button>
                </div>
            </div>

        </div>
        {/if}
    </div>
</div>

<style>
    .body{
        color: white;
    }
    .content{
        padding: 15px;
        display: flex;
        font-size: 20px;
    }
    .right{
        width: 75%;
    }
    .right{
        border: 1px solid rgba(255, 255, 255, 0.165);
        margin: 5px;
        padding: 15px;
        border-radius: 35px;
        display: flex;
    }
    .col1, .col2{
        margin: 5px;
        padding: 15px;
        /* border-radius: 35px; */
    }
    .col1{
        width: 25%;
        border-right: 1px solid rgba(255, 255, 255, 0.185);
    }
    .col2{
        width: 75%;
    }
    h1{
        font-size: 20px;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        color: rgba(255, 255, 255, 0.619);
    }
    .list{
        margin-top: 10px;
    }
    .profile{
        display: flex;
        align-items: center;
        text-decoration: none;
        color: white;
        border-radius: 15px;
        padding: 5px;
    }
    .profile img{
        border: 1px solid rgba(255, 255, 255, 0.508);
        border-radius: 100%;
        height: 40px;
        margin-right: 15px;
    }
    .profile:hover{
        background-color: rgba(255, 255, 255, 0.097);
    }
    .profile button{
        margin-left: auto;
        background-color: rgb(59, 59, 254);
        color: white;
        font-size: small;
        border: none;
        padding: 7px;
        border-radius: 10px;
        cursor: pointer;
    }
    .name{
        line-height: 5px;
    }
    .part{
        font-size: 13px;
        color: rgba(255, 255, 255, 0.575);
        display: flex;
        align-items: center;
    }
    .part img{
        border: none;
        height: 10px;
        margin-left: 5px;
    }
    .message{
        border: 1px solid rgba(255, 255, 255, 0.139);
        border-radius: 10px;
        padding: 10px;
    }
    .content-message{
        display: flex;
        align-items: center;
        width: 60%;
    }
    .content-message img{
        height: 40px;
    }
    .content-message p{
        background-color: rgba(255, 255, 255, 0.404);
        padding: 15px;
        border-radius: 15px;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 18px;
        margin: 5px;
    }
    .content-message-send p{
        background-color: green;
        padding: 15px;
        border-radius: 15px;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 18px;
        margin: 5px;
    }
    .content-message-send{
        width: 60%;
        margin-left: auto;
    }
    .input{
        display: flex;
        align-items: center;
        margin-top: 5px;
    }
    .input button{
        background-color: transparent;
        border: none;
        border-radius: 15px;
    }
    .input img{
        height: 30px;
        padding: 10px;
    }
    .input button:hover{
        background-color: rgba(255, 255, 255, 0.13);
        cursor: pointer;
    }
    .input textarea{
        width: 100%;
        border-radius: 15px;
        background: transparent;
        padding: 10px;
        border: none;
        color: white;
    }
    #add{
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.287);
        padding: 10px;
        color: white;
        font-size: 15px;
    }
    #disable{
        cursor: not-allowed;
        color: grey;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.287);
        padding: 10px;
        font-size: 15px;
    }
    #cancel{
        width: 100%;
        background: rgb(0, 81, 255);
        padding: 10px;
        color: white;
        font-size: 15px;
    }
    .alertLogout{
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
    .alertLogout .action{
        display: flex;
    }
    .alertLogout .action button{
        text-align: center;
        padding: 10px;
        width: 50%;
        background-color: transparent;
        border: none;
        color: white;
    }
    #yes:hover{
        background-color: rgba(255, 0, 0, 0.496);
    }
    .alertLogout .action button:hover{
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
        background-color: rgba(0, 0, 0, 0.671); /* Couche sombre semi-transparente */
        z-index: 5;
    }
</style>