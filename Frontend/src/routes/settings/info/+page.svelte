<script>
    import NavSettings from "../../../lib/navlat/navSettings.svelte";
    import { getUserInfo, updateUser } from "../../../api/user";
    import { onMount } from "svelte";

    let currentUser = null;
    let updated;

    let formData = {
        name: '',
        email: '',
    }

    const fetchUser = async () => {
        try {
            currentUser = await getUserInfo();
            console.log('Informations de l’utilisateur récupérées', currentUser);
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }

    const updateUserInfo = async () => {
        try {
            updated = await updateUser(formData);
            console.log('User info updated with success!', updated);
        } catch (error) {
            console.error('Error updating user info:', error);
        }
    }

    onMount(async () => {
        await fetchUser();
        formData = { ...currentUser };
    });
</script>

<div class="body">
    <div class="content">
        <NavSettings/>
        <div class="right">
            <form action="">
            {#if currentUser}
            
                <div class="img">
                    <img src="/utilisateur.png" alt="">
                    <p>{currentUser.name}</p>
                    <input type="file" name="" id="">
                </div>
                <div class="list">
                    <label for="">Name :
                        <input type="text" placeholder="Enter your name" bind:value={formData.name}>
                    </label>
                    <label for="">Email :
                        <input type="email" name="" id="" placeholder="Enter your email" bind:value={formData.email}>
                    </label>
                    <button on:click={updateUserInfo}>Modifier</button>
                </div>
            
            {/if}
            </form>
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
    .right form{
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
        /*font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;*/
        font-family: "poppins";
        font-size: 15px;
    }
    .list input{
        width: 100%;
        height: 40px;
        text-align: center;
        border-radius: 10px;
        border: none;
        background-color: rgba(255, 255, 255, 0.074);
        color: white;
        font-family: 'Courier New', Courier, monospace;
        margin-bottom: 10px;
    }
    .list button{
        width: 100%;
        height: 40px;
        text-align: center;  
        border-radius: 10px;
        border: none;
        background-color: green; 
        color: white;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        margin-top: 20px;
        font-weight: bold;
    }
    .list button:hover{
        cursor: pointer;
        background-color: rgb(0, 106, 0);
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
            padding: 0px;
            margin-top: 100px;
        }
        .right form{
            padding: 5px;
            border-radius: 20px;
        }
        .img{
            width: auto;
            margin-left: auto;
            margin-right: auto;
        }
        .img img{
            height: 200px;
            width: auto;
            display: flex;
            margin-left: auto;
            margin-right: auto;
        }
        .img input {
            display: flex;
            margin-left: auto;
            margin-right: auto;
        }
        .list {
            padding: 10px;
        }
    }
    @media screen and (max-width: 1330px) {
        .right form{
            display: block;
        }
        .list{
            width: auto;
        }
    }
</style>