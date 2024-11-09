<script>
    import { onMount } from 'svelte';
    import { getUserInfo, checkUsersWithFriendStatus } from '../../../../lib/auth';
    import NavChat from '../../../../lib/navlat/navChat.svelte';

    let currentUser = null;
    let allUser = [];
    let intervalId;

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
            // console.log('Informations de l’utilisateur récupérées', currentUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const fetchAllUserWithFriendStatus = async () => {
        try {
            allUser = await checkUsersWithFriendStatus();
            // console.log('Informations des utilisateur récupérées', allUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    onMount(async () => {

        // Récupérer les données utilisateurs
        await fetchUser();
        await fetchAllUserWithFriendStatus();

        // Polling toutes les 5 secondes
        intervalId = setInterval(fetchAllUserWithFriendStatus, 5000);

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
            <div class="nav">
                <a href="/chat/room"><p>Room</p></a>
                <a id="active" href="/chat/contact"><p>Contact</p></a>
            </div>
            <div class="input">
                <input type="search" name="" id="" placeholder="Enter the user name">
            </div>
                <button class="add">
                    <a href="/chat/contact/all" class="active">
                        <img src="/all-active.png" alt="">
                        <span>All users</span>
                    </a>
                </button>
                <button class="add">
                    <a href="/chat/contact/friend" >
                        <img src="/amis.png" alt="">
                        <span>Contact</span>
                    </a>
                </button>
                <button class="add">
                    <a href="/chat/contact/online" >
                        <img src="/online.png" alt="">
                        <span>Contact online</span>
                    </a>
                </button>
            <div class="list">
                {#if allUser.length > 0}
                    {#each allUser as user}
                        <a href="/chat/contact/friend/{user.id}" class="profile">
                            <img src="/utilisateur.png" alt="">
                            <div class="name">
                                <p class="smallName">
                                    {user.name}
                                </p>
                                <p class="part">
                                    Friend
                                    {#if user.friend_status == "accepted"}
                                        <img src="/accepter.png" alt="">
                                    {:else if user.sender_id != currentUser.id && user.friend_status == "pending"}
                                    <p>oui ou non</p>
                                    {:else if user.sender_id == currentUser.id && user.friend_status == "pending"}
                                        <img src="/en_cours.png" alt="">
                                    {:else}
                                        <img src="/refuser.png" alt="">
                                    {/if}
                                    
                                </p>
                            </div>
                        </a>        
                    {/each}
                {:else}
                    <p>No user found</p>
                {/if}
            </div>

        </div>
        {:else}
        <div class="right">
            <div class="nav">
                <a href="/chat/room"><p>Room</p></a>
                <a id="active" href="/chat/contact"><p>Contact</p></a>
            </div>
            <div class="input">
                <input type="search" name="" id="" placeholder="Enter the user name">
            </div>
            <button class="add">
                <a href="/chat/contact/all" class="active">
                    <img src="/all-active.png" alt="">
                    <span>All users</span>
                </a>
            </button>
            <button class="add">
                <a href="/chat/contact/friend">
                    <img src="/amis.png" alt="">
                    <span>Contact</span>
                </a>
            </button>
            <button class="add">
                <a href="/chat/contact/online" >
                    <img src="/online.png" alt="">
                    <span>Contact online</span>
                </a>
            </button>
            <div class="list">
                <p>Loading...</p>
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
        border: 1px solid rgba(255, 255, 255, 0.057);
        margin: 5px;
        padding: 15px;
        border-radius: 35px;
    }
    .nav{
        display: flex;
        border: 1px solid rgba(255, 255, 255, 0.165);
        border-radius: 20px;
    }
    .nav a{
        width: 100%;
        color: rgba(255, 255, 255, 0.361);
        border-radius: 20px;
        text-align: center;
        text-decoration: none;
    }
    .nav a:hover{
        background-color: rgba(255, 255, 255, 0.196);
    }
    #active{
        color: white;
        background-color: rgba(255, 255, 255, 0.064);
        border-radius: 0px 20px 20px 0px;
    }
    .add{
        border: none;
        border-radius: 20px;
        background-color: transparent;
        padding: 0;
    }
    .add img{
        height: 20px;
        margin-right: 5px;
    }
    .add a{
        text-decoration: none;
        padding: 10px;
        color: white;
        align-items: center;
        display: flex;
        transition: 0.2s;
    }
    .add a:hover{
        color: rgb(0, 90, 0);
    }
    .profile{
        display: flex;
        align-items: center;
        border-radius: 15px;
        padding: 5px;
        text-decoration: none;
        color: white;
    }
    .profile:hover{
        background-color: rgba(255, 255, 255, 0.097);
    }
    .profile img{
        border: 1px solid rgba(255, 255, 255, 0.624);
        border-radius: 100%;
        height: 40px;
        margin-right: 15px;
    }
    .list{
        margin-top: 10px;
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
    .active span{
        color: green;
        font-weight: 900;
    }
    .input{
        display: flex;
        align-items: center;
        margin-top: 10px;
    }
    .input input{
        width: 100%;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.093);
        padding: 15px;
        border: none;
        color: white;
        font-size: 15px;
        margin-bottom: 10px;
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
            padding: 10px;
        }
        .add a{
            padding: 2px;
        }
    }
</style>