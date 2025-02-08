<script>
    import { onMount } from 'svelte';
    import { logout } from '../../api/auth';
    import { getUserInfo } from '../../api/user';
    import {goto} from "$app/navigation";

    let currentUser = null;

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
            console.log('Informations de l’utilisateur récupérées', currentUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    let alertLogout = false;

    const handleLogout = () => {
        alertLogout = true;
    }

    const logoutYes = () => {
        logout();
        goto('/');
    }

    const logoutNo = () => {
        alertLogout = false;
    }

    if (typeof window !== "undefined") {
        window.addEventListener('beforeunload', function (event) {
            logout();
        });
    }

    onMount(async () => {
        fetchUser();
    });
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
                <li><img src="/accueil.png" alt="">Home</li>
            </a>
            <a href="/chat/room">
                <li id="active"><img src="/message.png" alt="">Chat <span>4</span></li>
            </a>
            <a href="/settings">
                <li><img src="/parametre.png" alt="">Settings</li>
            </a>
            <button on:click={handleLogout}>
                <li><img src="/déconnexion.png" alt="">Logout</li>
            </button>
            {#if alertLogout}
                <div class="overlay"></div>
                <div class="alertLogout">
                    <p>Do you really want to log out?</p>
                    <div class="action">
                        <button id="yes" on:click={logoutYes}>Yes</button>
                        <button on:click={logoutNo}>No</button>
                    </div>
                </div>
            {/if}
        </div>

        <!-- Responsive -->
        <div class="list resp">
            <a href="/home">
                <li><img src="/accueil.png" alt=""></li>
            </a>
            <a href="/chat/room">
                <li id="active"><img src="/message.png" alt=""><span>4</span></li>
            </a>
            <a href="/settings">
                <li><img src="/parametre.png" alt=""></li>
            </a>
            <button on:click={handleLogout}>
                <li><img src="/déconnexion.png" alt=""></li>
            </button>
            {#if alertLogout}
                <div class="overlay"></div>
                <div class="alertLogout">
                    <p>Do you really want to log out?</p>
                    <div class="action">
                        <button id="yes" on:click={logoutYes}>Yes</button>
                        <button on:click={logoutNo}>No</button>
                    </div>
                </div>
            {/if}
        </div>
    {:else}
    <div class="profile">
        <img src="/utilisateur.png" alt="">
        <p>Loading...</p>
    </div>
    <hr>
    <div class="list">
        <a href="/home">
            <li><img src="/accueil.png" alt="">Home</li>
        </a>
        <a href="/chat/room">
            <li id="active"><img src="/message.png" alt="">Chat</li>
        </a>
        <a href="/settings">
            <li><img src="/parametre.png" alt="">Settings</li>
        </a>
        <a href="/">
            <li>Login</li>
        </a>
    </div>
    {/if}
</div>

<style>
    
    .left{
        width: 25%;
        height: 100%;
    }
    .left{
        border: 1px solid rgba(255, 255, 255, 0.057);
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
    hr{
        border: 1px solid rgba(255, 255, 255, 0.057);
    }
    .resp{
        display: none;
    }
    .list li{
        /* border: 1px solid rgba(255, 255, 255, 0.094); */
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
        color: white;
        background-color: green;
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
    .list span{
        margin-left: auto;
        color: white;
        background: rgb(255, 35, 35);
        padding: 5px;
        text-align: center;
        border-radius: 10px;
        font-size: 12px;
        font-weight: bold;
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
    @media screen and (max-width: 700px){
        .left {
            width: 90%;
            display: flex;
            align-items: center;
            border: none;
            position: fixed;
            height: auto;
            background-color: rgb(23, 23, 23);
            top: 0;
        }
        .profile {
            display: block;
        }
        .profile img{
            display: flex;
            margin-left: auto;
            margin-right: auto;
        }
        .profile p{
            font-size: 15px;
            margin: 0;
        }
        .list {
            display: none;
            margin-left: auto;
        }
        .resp {
            display: flex;
            align-items: center;
        }
        .resp li{
            /* border: 1px solid rgba(255, 255, 255, 0.094); */
            margin: 0;
            padding: 5px;
            margin-top: 0;
            border-radius: 20px;
            list-style: none;
            display: flex;
            align-items: center;
        }
        .list img{
            height: 20px;
            margin-right: 5px;
        }
        hr{
            display: none;
        }
        .list span{
            position: relative;
            color: white;
            background: rgb(255, 35, 35);
            padding: 5px;
            font-size: 12px;
            font-weight: lighter;
            left: -15px;
            top: -10px;
            font-family: cursive;
        }
        #active{
            border: none;
        }
        .alertLogout{
            right: 10%;
            left: 10%;
        }
    }
</style>