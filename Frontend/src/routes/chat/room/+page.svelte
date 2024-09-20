<script>
    import NavChat from "../../../lib/navlat/navChat.svelte";
    import { getUserInfo } from "../../../lib/auth";
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";

    let currentUser = null;

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
            console.log('Informations de l’utilisateur récupérées', currentUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    onMount(async () => {
        await fetchUser();
    });
</script>

<div class="body">
    <div class="content">
        <NavChat/>
        {#if currentUser}
            
        <div class="right">
            <div class="nav">
                <a id="active" href="/chat/room"><p>Room</p></a>
                <a href="/chat/contact/friend"><p>Contact</p></a>
            </div>
            <div class="input">
                <input type="search" name="" id="" placeholder="Enter the room name">
            </div>
                <button class="add">
                    <a href="" >
                        <img src="/ajouter.png" alt="">
                        <span>Create room</span>
                    </a>
                </button>
            <div class="list">
                <a href="/chat/room/2" class="profile">
                    <img src="/utilisateur.png" alt="">
                    <div class="name">
                        <p>Chat name</p>
                        <p class="part">25 participants</p>
                    </div>
                </a>
                <a href="" class="profile">
                    <img src="/utilisateur.png" alt="">
                    <div class="name">
                        <p>Chat name</p>
                        <p class="part">25 participants</p>
                    </div>
                </a>
                <a href="" class="profile">
                    <img src="/utilisateur.png" alt="">
                    <div class="name">
                        <p>Chat name</p>
                        <p class="part">25 participants</p>
                    </div>
                </a>
            </div>

        </div>
        {:else}
        <div class="right">
            <div class="nav">
                <a id="active" href="/chat/room/"><p>Room</p></a>
                <a href="/chat/contact/friend/"><p>Contact</p></a>
            </div>
            <div class="input">
                <input type="search" name="" id="" placeholder="Enter the room name">
            </div>
                <button class="add">
                    <a href="" >
                        <img src="/ajouter.png" alt="">
                        <span>Create room</span>
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
    .left{
        width: 25%;
    }
    .right{
        width: 75%;
    }
    .right{
        border: 1px solid rgba(255, 255, 255, 0.165);
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
        border-radius: 20px 0px 0px 20px;
    }
    .add{
        border: 1px solid white;
        border-radius: 20px;
        background-color: transparent;
        padding: 0;
    }
    .add img{
        height: 30px;
        margin-right: 10px;
    }
    .add a{
        text-decoration: none;
        padding: 10px;
        color: white;
        align-items: center;
        display: flex;
    }
    .add:hover{
        background-color: rgba(255, 255, 255, 0.196);
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
        margin-top: 10px;
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
</style>