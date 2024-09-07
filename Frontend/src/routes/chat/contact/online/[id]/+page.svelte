<script>
    import NavChat from "../../../../lib/navlat/navChat.svelte";
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";

    let user = null;

    onMount(async () => {
        try {
            const token = localStorage.getItem('auth_token');

            const response = await fetch('http://localhost:8000/api/user', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.ok) {
                const data = await response.json();
                user = data;
            } else {
                console.error("Failed to fetch user data");
            }
        } catch (error) {
            console.error("Error:", error);
        }
    });
</script>

<div class="body">
    <div class="content">
        <NavChat/>
        {#if user}
        <div class="right">
            <div class="col1">
                <h1>Participants in this chat</h1>
                <div class="list">
                    <a href="/chat/2" class="profile">
                        <img src="/utilisateur.png" alt="">
                        <div class="name">
                            <p>John Doe</p>
                            <p class="part">
                                Friend
                                <img src="/accepter.png" alt="">
                            </p>
                        </div>
                    </a>
                    <a href="/chat/2" class="profile">
                        <img src="/utilisateur.png" alt="">
                        <div class="name">
                            <p>John Doe</p>
                            <p class="part">
                                Friend
                                <img src="/refuser.png" alt="">
                            </p>
                        </div>
                    </a>
                    <a href="/chat/2" class="profile">
                        <img src="/utilisateur.png" alt="">
                        <div class="name">
                            <p>John Doe</p>
                            <p class="part">
                                Friend
                                <img src="/en_cours.png" alt="">
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col2">
                <div class="profile">
                    <img src="/utilisateur.png" alt="">
                    <p>Chat name</p>
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
                <div class="input">
                    <textarea name="" id="" placeholder="Enter the message"></textarea>
                    <button><img src="/message-sender.png" alt=""></button>
                </div>
            </div>

        </div>
        {:else}
        <div class="right">
            <div class="col1">
                <h1>Participants in this chat</h1>
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
</style>