<script>
    import axios from 'axios';

    let showPassword = false;

    let name = "";
    let email = "";
    let password = "";
    let password_confirmation = "";
    let error = "";
    let successMessage = "";

    let clicked = false;

     // Fonction pour l'inscription
    async function register() {
        clicked = true;
        try {
            await axios.post('http://localhost:8000/api/register', {
                name,
                email,
                password,
                password_confirmation: password_confirmation,
            });
    
            successMessage = "Registration successful! You can now log in.";
            error = "";
            clicked = false;

            /*const response = await fetch('http://localhost:8000/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    name, 
                    email, 
                    password, 
                }),
            });

            const data = await response.json();

            if (response.ok) {
                error = "";
                successMessage = "Registration successful! You can now log in.";
            } else {
                error = "kely sisa";
            }*/

            
        } catch (err) {
            clicked = false;
            console.error('Registration error:', err);
            error = err.message;
            successMessage = "";
        }
    };

</script>

<div class="body">
    <div class="content">
        <div class="">
            <h1>SignUp</h1>
            {#if error}
                <p id="error">{error}</p>
            {/if}
            {#if successMessage}
                <p style="color: green;">{successMessage}</p>
            {/if}
            
            <form on:submit|preventDefault={register}>
                <input type="text" placeholder="Username" bind:value={name}>
                <input type="email" name="" id="" placeholder="Email" bind:value={email}>
                {#if showPassword}
                    <input type="text" placeholder="Password" bind:value={password}>
                {:else}
                    <input type="password" placeholder="Password" bind:value={password}>
                {/if}
                <input type="password" placeholder="Confirm password" bind:value={password_confirmation}>
                <div class="check">
                    <input type="checkbox" name="" id="checkbox" bind:checked={showPassword}>
                    <span>Show password</span>
                </div>
                {#if !clicked}
                    <button type="submit">Signup</button> 
                {:else}
                    <button type="submit">Loading...</button> 
                {/if}
                <p>or</p>
                <p>Already have an account? <a href="/">Sign In</a></p>
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
        margin-top: 7%;
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
        margin-top: 8px;
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