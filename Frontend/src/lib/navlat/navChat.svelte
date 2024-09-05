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
        confirm('Vous êtes sûr de vouloir vous déconnecter?');

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
            <li id="active"><img src="/message-active.png" alt="">Chat</li>
        </a>
        <a href="">
            <li><img src="/parametre.png" alt="">Settings</li>
        </a>
        <button on:click={logout}>
            <li>Logout</li>
        </button>
    </div>
</div>

<style>
    
    .left{
        width: 25%;
    }
    .left{
        border: 1px solid rgba(255, 255, 255, 0.165);
        margin: 5px;
        padding: 15px;
        border-radius: 35px;
    }
    .profile{
        display: flex;
        align-items: center;
    }
    .profile b{
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }
    .profile img{
        border: 1px solid rgba(255, 255, 255, 0.485);
        border-radius: 100%;
        height: 40px;
        margin-right: 15px;
    }
    .list li{
        border: 1px solid rgba(255, 255, 255, 0.094);
        margin: 0;
        padding: 15px;
        margin-top: 5px;
        border-radius: 20px;
        list-style: none;
        display: flex;
        align-items: center;
    }
    .list img{
        height: 25px;
        margin-right: 10px;
    }
    .list li:hover{
        background-color: rgba(255, 255, 255, 0.097);
    }
    #active{
        color: green;
        border: 1px solid green;
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
</style>