<script>
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

    async function logout() {
        const token = localStorage.getItem('auth_token');

        // Envoyer une requête de déconnexion à Laravel
        const response = await fetch('http://localhost:8000/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.ok) {
            // Supprimer le token du stockage local
            localStorage.removeItem('auth_token');
            goto('/');
        } else {
            console.error('Failed to logout');
        }
    }
</script>

<div class="body">
    <div class="content">
        <div class="left">
            {#if user}
            <div class="profile">
                <img src="/utilisateur.png" alt="">
                <p>Welcome <b>{user.name}</b></p>
            </div>
            {:else}
                <p>Loading...</p>
                <button><a href="/">Login</a></button>
            {/if}
            <hr>
            <div class="list">
                <a href="">
                    <li>Chat</li>
                </a>
                <a href="">
                    <li>Settings</li>
                </a>
                <button on:click={logout} onclick="return confirm('Vous êtes sûr de vouloir vous déconnecter?')">
                    <li>Logout</li>
                </button>
            </div>
        </div>
        <div class="right">
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
                <button>Send</button>
            </div>

        </div>
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
    .right, .left{
        border: 1px solid rgba(255, 255, 255, 0.381);
        margin: 5px;
        padding: 15px;
        border-radius: 10px;
    }
    .profile{
        display: flex;
        align-items: center;
    }
    .profile b{
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }
    .profile img{
        border: 1px solid white;
        border-radius: 100%;
        padding: 2px;
        height: 40px;
        margin-right: 15px;
    }
    .list li{
        border: 1px solid rgba(255, 255, 255, 0.094);
        margin: 0;
        padding: 15px;
        margin-top: 5px;
        border-radius: 5px;
        list-style: none;
    }
    .list li:hover{
        background-color: rgba(255, 255, 255, 0.097);
    }
    .list a{
        text-decoration: none;
        color: white;
        width: 100%;
    }
    .list button{
        width: 100%;
        background-color: transparent;
        color: white;
        border: none;
        margin: 0;
        border-radius: 5px;
        text-align: left;
        font-size: 17px;
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
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 20px;
        margin: 5px;
    }
    .content-message-send p{
        background-color: green;
        padding: 15px;
        border-radius: 15px;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 20px;
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
    .input textarea{
        width: 100%;
        border-radius: 15px;
        background: transparent;
        padding: 10px;
        border: 1px solid rgba(255, 255, 255, 0.139);
        color: white;
    }
</style>