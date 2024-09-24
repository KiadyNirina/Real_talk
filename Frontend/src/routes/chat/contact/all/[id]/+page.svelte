<script>
    import NavChat from "../../../../../lib/navlat/navChat.svelte";
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";
    import { getUserInfo, getAllUserOnline, getUserSelectedInfo, sendInvitation, checkFriend } from "../../../../../lib/auth";
    import { page } from "$app/stores";

    let intervalId;
    let currentUser = null;
    let invitationSended;
    let alluser = [];
    let userSelectedId = null;
    let userSelected = null;
    let userSelected2 = null;

    $: userSelectedId = $page.params.id;

    let formData = {
        receiver_id : 2,
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
            alluser = await getAllUserOnline();
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
            console.log('Friend request sending with success!', invitationSended);
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

    onMount(async () => {
        await fetchUser();
        await fetchAllUser();

        if(userSelectedId) {
            await fetchUserSelected(userSelectedId);
            await seeFriendStatus(userSelectedId);
        }

        // Polling toutes les 5 secondes
        intervalId = setInterval(() => {
            fetchAllUser();
            seeFriendStatus(userSelectedId);
        }, 10000);

        // Nettoyer l'intervalle au démontage
        return () => {
        clearInterval(intervalId);
        };
    });
</script>

<div class="body">
    <div class="content">
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
                    </div>
                    <div class="message">
                        <div class="content-message">
                            <img src="/utilisateur.png" alt="">
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat, obcaecati?
                            </p>
                        </div>
        
                        <div class="content-message-send">
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat, obcaecati?
                            </p>
                        </div>
                        <div class="content-message-send">
                            <p>
                                Lorem ipsum dolor
                            </p>
                        </div>
                    </div>
                    {#if userSelected2 === "pending"}
                        <form>
                            <div class="input">
                                <button id="cancel">Invitation envoyé</button>
                            </div>
                        </form>
                    {:else if userSelected2 === "friends"}
                        <form>
                            <div class="input">
                                <textarea name="" id="" placeholder="Enter the message"></textarea>
                                <button><img src="/message-sender.png" alt=""></button>
                            </div>
                        </form>
                    {:else}
                        <form on:submit={sendFriendRequest}>
                            <div class="input">
                                <input type="text" value={userSelectedId}>
                                <button id="add">Ajouter</button>
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
    #cancel{
        width: 100%;
        background: rgb(0, 81, 255);
        padding: 10px;
        color: white;
        font-size: 15px;
    }
</style>