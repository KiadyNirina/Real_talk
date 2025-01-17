<script>
    import NavChat from "../../../../lib/navlat/navChat.svelte";
    import { onMount } from "svelte";
    import { getUserInfo } from "../../../../api/user";
    import { getUserFriends } from "../../../../api/friend";

    let user = null;
    let friends = [];
    let intervalId;

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

<div class="body">
    <div class="content">
        <NavChat/>
        {#if user}
            
        <div class="right">
            <div class="nav">
                <a href="/chat/room"><p>Room</p></a>
                <a id="active" href="/chat/contact"><p>Contact</p></a>
            </div>
            <div class="input">
                <input type="search" name="" id="" placeholder="Enter the user name">
            </div>
                <button class="add">
                    <a href="/chat/contact/all" >
                        <img src="/all.png" alt="">
                        <span>All users</span>
                    </a>
                </button>
                <button class="add">
                    <a href="/chat/contact/friend" class="active">
                        <img src="/amis-active.png" alt="">
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
                {#if friends.length > 0}
                    {#each friends as friend}
                    <a href="/chat/contact/friend/{friend.id}" class="profile">
                        <img src="/utilisateur.png" alt="">
                        <div class="name">
                            <p class="smallName">
                                {friend.name}
                                <span>1</span>
                            </p>
                            <p class="part">
                                Friend
                                <img src="/accepter.png" alt="">
                            </p>
                        </div>
                        {#if friend.is_online == true}
                            <p class="onLine">.</p>
                        {:else}
                            <p class="offline">{friend.last_seen}</p>
                        {/if}
                    </a>
                    {/each}
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
                <a href="/chat/contact/all" >
                    <img src="/all.png" alt="">
                    <span>All users</span>
                </a>
            </button>
            <button class="add">
                <a href="/chat/contact/friend" class="active">
                    <img src="/amis-active.png" alt="">
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
        height: 100%;
    }
    .content{
        padding: 15px;
        display: flex;
        font-size: 20px;
        height: 100%;
    }
    .right{
        width: 75%;
        height: 100%;
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
    .onLine{
        color: rgb(0, 198, 0);
        margin: 0 0 0 auto;
        font-weight: bold;
        font-size: 50px;
    }
    .offline{
        color: rgba(255, 255, 255, 0.546);
        margin: 0 0 0 auto;
        font-weight: bold;
        font-size: 10px;
        letter-spacing: 0.2px;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    }
    .active span{
        color: green;
        font-weight: 900;
    }
    .smallName span{
        background: rgb(255, 31, 31);
        padding: 3px;
        border-radius: 100%;
        font-size: 11px;
        margin-left: 5px;
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