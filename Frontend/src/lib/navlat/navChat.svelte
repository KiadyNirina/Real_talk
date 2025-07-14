<script>
    import { onMount } from 'svelte';
    import { getUserInfo } from '../../api/user';
    import { logout } from '../../api/auth';
    import {goto} from "$app/navigation";
    import Icon from "@iconify/svelte";

    let currentUser = null;
    let alertLogout = false;

    if (typeof window !== "undefined") {
        window.addEventListener('beforeunload', logout);
    }

    onMount(async () => {
        try {
            currentUser = await getUserInfo();
            console.log('Informations utilisateur:', currentUser);
        } catch (error) {
            console.error('Erreur:', error);
        }
    });

    const toggleLogoutAlert = (show = true) => alertLogout = show;
    const confirmLogout = () => { logout(); goto('/'); };
</script>

<div class="left">
    {#if currentUser}
        <!-- Profil utilisateur -->
        <div class="profile p-[15px]">
            <img src="/utilisateur.png" alt="">
            <p>Welcome <b>{currentUser.name}</b></p>
        </div>

        <!-- Menu principal -->
        <div class="list">
            {#each [
                { href: "/home", icon: "majesticons:home", text: "Home" },
                { href: "/chat/room", icon: "majesticons:chat", text: "Chat", badge: "4" },
                { href: "/settings", icon: "ion:settings", text: "Settings" }
            ] as item}
                <a href={item.href}>
                    <li class:active={item.href === "/chat/room"}>
                        <Icon icon={item.icon} class="mr-[10px]" height="25px"/>
                        {item.text}
                        {#if item.badge}<span>{item.badge}</span>{/if}
                    </li>
                </a>
            {/each}

            <button on:click={() => toggleLogoutAlert()}>
                <li><Icon icon="heroicons-outline:logout" class="mr-[10px]" height="25px"/>Logout</li>
            </button>

            <!-- Popup de confirmation -->
            {#if alertLogout}
                <div class="overlay"></div>
                <div class="alertLogout">
                    <p>Do you really want to log out?</p>
                    <div class="action">
                        <button id="yes" on:click={confirmLogout}>Yes</button>
                        <button on:click={() => toggleLogoutAlert(false)}>No</button>
                    </div>
                </div>
            {/if}
        </div>

        <!-- Version responsive -->
        <div class="list resp">
            {#each [
                { href: "/home", icon: "majesticons:home" },
                { href: "/chat/room", icon: "majesticons:chat", badge: "4" },
                { href: "/settings", icon: "ion:settings" },
                { action: () => toggleLogoutAlert(), icon: "heroicons-outline:logout" }
            ] as item}
                {#if item.href}
                    <a href={item.href}>
                        <li class:active={item.href === "/chat/room"}>
                            <Icon icon={item.icon} class="mr-[10px]" height="20px"/>
                            {#if item.badge}<span>{item.badge}</span>{/if}
                        </li>
                    </a>
                {:else}
                    <button on:click={item.action}>
                        <li><Icon icon={item.icon} class="mr-[10px]" height="25px"/></li>
                    </button>
                {/if}
            {/each}
        </div>
    {:else}
        <!-- État de chargement -->
        <div class="profile p-[15px]">
            <img src="/utilisateur.png" alt="">
            <p>Loading...</p>
        </div>
        <div class="list">
            {#each [
                { href: "/home", icon: "majesticons:home", text: "Home" },
                { href: "/chat/room", icon: "majesticons:chat", text: "Chat", badge: "4" },
                { href: "/settings", icon: "ion:settings", text: "Settings" },
                { href: "/", icon: "heroicons-outline:login", text: "Login" }
            ] as item}
                <a href={item.href}>
                    <li class:active={item.href === "/chat/room"}>
                        <Icon icon={item.icon} class="mr-[10px]" height="25px"/>
                        {item.text}
                        {#if item.badge}<span>{item.badge}</span>{/if}
                    </li>
                </a>
            {/each}
        </div>
    {/if}
</div>

<style>
    
    .left{
        width: 20%;
        border: 1px solid rgba(255, 255, 255, 0.057);
        margin: 5px;
        padding: 10px;
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
    .list li:hover{
        background-color: rgba(255, 255, 255, 0.097);
    }
    .active{
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
        background-color: rgba(0, 0, 0, 0.671);
        z-index: 5;
    }
    @media screen and (max-width: 700px){
        .left {
            width: 100%;
            display: flex;
            align-items: center;
            border: none;
            position: fixed;
            height: auto;
            background-color: rgb(23, 23, 23);
            top: 0;
            margin: 0;
            padding: 0;
            border-radius: 0px 0px 35px 35px;
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
            display: none;
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
        .active{
            border: none;
        }
        .alertLogout{
            right: 10%;
            left: 10%;
        }
    }
</style>