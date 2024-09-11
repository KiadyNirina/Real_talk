<script>
    import axios from "axios";
    import {goto} from "$app/navigation";
    import { user, token } from "../lib/store";
    import { login } from "../lib/auth";

    let successMessage = "";
    let showPassword = false;
    
    let formData = {
        email : '',
        password : '',
    }

    let error;

    const handleLogin = async() => {
            const response = await login(formData);
            if(response){
                goto('/home');
            } else {
                error
            }
    }
    
</script>

<div class="body">
    <div class="content">
        <div class="">
            <h1>Login</h1>
            {#if error}
                <p id="error">{error}</p>
            {/if}
            {#if successMessage}
                <p style="color: green;">{successMessage}</p>
            {/if}

            <form on:submit|preventDefault={handleLogin}>
                <input type="email" name="" id="" placeholder="Email" bind:value={formData.email}>
                {#if showPassword}
                    <input type="text" placeholder="Password" bind:value={formData.password}>
                {:else}
                    <input type="password" placeholder="Password" bind:value={formData.password}>
                {/if}
                <div class="check">
                    <input type="checkbox" name="" id="checkbox" bind:checked={showPassword}>
                    <span>Show password</span>
                </div>
                <p id="forget"><a href="">Forget password?</a></p>
                <button>Login</button> 
                <p>or</p>
                <p>Have not an account yet? <a href="/signup">Sign Up</a></p>
            </form>
        </div> 
    </div>
</div>

<style>
    /*.body{
        border: 1px solid white;
        padding: 15px;
    }*/
    .content{
        border: 1px solid rgba(255, 255, 255, 0.13);
        border-radius: 15px;
        padding: 10px;
        margin-top: 10%;
        max-width: 500px;
        background-color: rgba(255, 255, 255, 0.101);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        margin-right: auto;
    }
    .content h1{
        color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: center;
    }
    #forget{
        text-align: right;
        margin: 0;
    }
    .content p{
        text-align: center;
        color: white;
    }
    .content a{
        color: rgb(111, 111, 255);
        text-decoration: none;
    }
    .content input, .content button{
        width: 100%;
        height: 40px;
        background-color: rgb(23, 23, 23);
        margin-top: 5px;
        border: none;
        border-radius: 5px;
        color: white;
        padding: 0;
        text-align: center;
    }
    .content button{
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 15px;
        margin-top: 20px;
    }
    .content button:hover{
        cursor: pointer;
        background-color: green;
    }
    .check{
        display: flex;
        align-items: center;
        color: rgb(200, 200, 200);
    }
    .check input{
        position: relative;
        width: 15px;
        height: 15px;
        cursor: pointer;
        margin-right: 10px;
    }
    .check input:checked{
        accent-color: green;
    }
    #error{
        color: rgb(250, 57, 57);
    }
</style>