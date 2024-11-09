<script>
    import NavSettings from "../../../lib/navlat/navSettings.svelte";
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
        <NavSettings/>
        <div class="right">
            {#if currentUser}
            <div class="img">
                <img src="/utilisateur.png" alt="">
                <p>{currentUser.name}</p>
                <input type="file" name="" id="">
            </div>
            <div class="list">
                <label for="">Name :
                    <input type="text" placeholder="Enter your name" bind:value={currentUser.name}>
                </label>
                <label for="">Email :
                    <input type="email" name="" id="" placeholder="Enter your email" bind:value={currentUser.email}>
                </label>
            </div>
            {/if}
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
    .right{
        width: 75%;
    }
    .right{
        border: 1px solid rgba(255, 255, 255, 0.057);
        margin: 5px;
        padding: 15px;
        border-radius: 35px;
        display: flex;
    }
    .img{
        width: 30%;
        padding: 10px;
    }
    .img img{
        width: 100%;
    }
    .img p{
        text-align: center;
        font-weight: bold;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 20px;
        margin-top: 5px;
    }
    .img input{
        background-color: rgba(255, 255, 255, 0.097);
        padding: 10px;
        border-radius: 15px;
    }
    .list{
        background-color: rgba(255, 255, 255, 0.038);
        padding: 20px;
        width: 100%;
        border-radius: 15px;
    }
    .list input{
        width: 100%;
        height: 30px;
        border-radius: 10px;
        border: none;
        background-color: rgba(255, 255, 255, 0.13);
        color: white;
        font-family: 'Courier New', Courier, monospace;
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
    }
</style>