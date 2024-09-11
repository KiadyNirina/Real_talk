<script>
    import axios from 'axios';
    import { onMount } from 'svelte';
    import { user } from '../store';
    import { getUserInfo } from '../auth';

    let currentUser = null;

    onMount(async () => {
        try {
            currentUser = await getUserInfo();
            console.log('Informations de l’utilisateur récupérées', currentUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    });

    /*function logout() {
        // Supprimer le token JWT et vider les infos de l'utilisateur
        localStorage.removeItem('token');
        user.set(null);

        // Rediriger vers la page de connexion
        goto('/');
    }*/
</script>

<div class="left">
    {#if currentUser}
        <div class="profile">
            <img src="/utilisateur.png" alt="">
            <p>Welcome <b>{currentUser.name}</b></p>
        </div>
        <hr>
        <div class="list">
            <a href="/home">
                <li id="active"><img src="/accueil-active.png" alt="">Home</li>
            </a>
            <a href="/chat/room">
                <li><img src="/message.png" alt="">Chat <span>1500</span></li>
            </a>
            <a href="">
                <li><img src="/parametre.png" alt="">Settings</li>
            </a>
            <button>
                <li>Logout</li>
            </button>
        </div>
    {:else}
        <div class="profile">
            <img src="/utilisateur.png" alt="">
            <p>Loading...</p>
        </div>
        <hr>
        <div class="list">
            <a href="/home">
                <li id="active"><img src="/accueil-active.png" alt="">Home</li>
            </a>
            <a href="/chat/room">
                <li><img src="/message.png" alt="">Chat</li>
            </a>
            <a href="">
                <li><img src="/parametre.png" alt="">Settings</li>
            </a>
            <button>
                <li>Logout</li>
            </button>
        </div>
    {/if}
    
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
    
    #active{
        color: green;
        border: 1px solid green;
    }
    
    .list span{
        margin-left: auto;
        color: white;
        background: rgb(255, 35, 35);
        padding: 5px;
        text-align: center;
        border-radius: 100%;
        font-size: 17px;
        font-weight: bold;
    }
</style>