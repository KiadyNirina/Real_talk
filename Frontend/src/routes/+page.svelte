<script>
    import {goto} from "$app/navigation";
    import { login } from "../api/auth";

    let successMessage = "";
    let showPassword = false;
    let clicked = false;
    
    let formData = {
        email : '',
        password : '',
    }

    let error;

    const handleLogin = async() => {
        try {
            clicked = true;
            const response = await login(formData);
            if(response){
                goto('/home');
            } else {
                clicked = false;
                error = "Invalid email or password";
            }
        } catch (err) {
            clicked = false;
            error = err.message || "Connexion error";
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
                <label for="email">Email:</label>
                <input type="email" id="email" class={error ? "input-error" : ""} placeholder="Email" bind:value={formData.email}>
                {#if error && !formData.email}
                    <p class="error-message">Email is required.</p>
                {/if}
                <label for="password">Password:</label>
                {#if showPassword}
                    <input type="text" id="password" class={error ? "input-error" : ""} placeholder="Password" bind:value={formData.password}>
                {:else}
                    <input type="password" id="password" class={error ? "input-error" : ""} placeholder="Password" bind:value={formData.password}>
                {/if}
                {#if error && !formData.password}
                    <p class="error-message">Password is required.</p>
                {/if}
                <div class="check">
                    <input type="checkbox" id="checkbox" bind:checked={showPassword}>
                    <span>Show password</span>
                </div>
                <p id="forget"><a href="">Forget password?</a></p>
                {#if clicked}
                    <button disabled>Loading...</button> 
                {:else}
                    <button>Login</button> 
                {/if}
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
        align-items: center;
        justify-content: center;
        margin-left: auto;
        margin-right: auto;
        font-family: 'poppins';
        font-size: 13px;
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
    .content form{
        width: 70%;
        margin-left: auto;
        margin-right: auto;
    }
    .content form label {
        color: rgb(200, 200, 200);
    }
    .content input, .content button{
        width: 100%;
        height: 40px;
        background-color: rgb(23, 23, 23);
        margin-bottom: 5px;
        border: none;
        border-radius: 5px;
        color: white;
        padding: 0;
        text-align: center;
    }
    .content .input-error {
        border: 1px solid rgb(255, 37, 37);
    }
    .content form .error-message {
        color: rgb(255, 37, 37);
        text-align: left;
        font-size: 11px;
        margin-top: -0px;
        margin-bottom: 5px;
    }
    .content button{
        font-size: 15px;
        margin-top: 20px;
    }
    .content button:disabled{
        color: gray;
    }
    .content button:disabled:hover{
        cursor: not-allowed;
        background-color: rgb(23, 23, 23);
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