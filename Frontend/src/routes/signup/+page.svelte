<script>
    import axios from 'axios';
    
    let showPassword = false;
    let name = "";
    let email = "";
    let password = "";
    let password_confirmation = "";
    
    let errors = {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
    };
    
    let successMessage = "";
    let errorMessage = "";
    let clicked = false;
    
    function validateField(field) {
        switch (field) {
            case 'name':
                return name.trim() === "" ? "Name is required." : "";
            case 'email':
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return !emailPattern.test(email) ? "Invalid email address." : "";
            case 'password':
                const minLength = 6;
                const securePattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
                if (password.length < minLength) {
                    return "Password must be at least 6 characters long.";
                }
                if (!securePattern.test(password)) {
                    return "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
                }
                return "";
            case 'password_confirmation':
                return password !== password_confirmation ? "Passwords do not match." : "";
            default:
                return "";
        }
    }
    
    function handleInput(field) {
        errors[field] = validateField(field);
    }
    
    // Fonction pour l'inscription
    async function register() {
        clicked = true;
        errors = {
            name: validateField('name'),
            email: validateField('email'),
            password: validateField('password'),
            password_confirmation: validateField('password_confirmation')
        };
    
        if (Object.values(errors).some(error => error) || !name || !email || !password || !password_confirmation) {
            clicked = false;
            return;
        }
    
        try {
            await axios.post('http://localhost:8000/api/register', {
                name,
                email,
                password,
                password_confirmation,
            });
    
            successMessage = "Registration successful! You can now log in.";
            errorMessage = "";
            errors = { name: "", email: "", password: "", password_confirmation: "" };
            clicked = false;
    
        } catch (err) {
            console.error('Registration error:', err);
            errorMessage = "Registration failed, try again!"
            successMessage = "";
            clicked = false;
        }
    }
</script>

<div class="body">
    <div class="content">
        <div class="">
            <h1>SignUp</h1>
            {#if successMessage}
                <p style="color: green;">{successMessage}</p>
            {/if}
            {#if errorMessage}
                <p style="color: red;">{errorMessage}</p>
            {/if}

            <form on:submit|preventDefault={register}>
                <label for="username">Username :</label>
                <input type="text" id="username" class={errors.name ? "input-error" : ""} placeholder="Username" bind:value={name} on:input={() => handleInput('name')}>
                {#if errors.name}
                    <p class="error-message">{errors.name}</p>
                {/if}
    
                <label for="email">Email :</label>
                <input type="email" id="email" class={errors.email ? "input-error" : ""} placeholder="Email" bind:value={email} on:input={() => handleInput('email')}>
                {#if errors.email}
                    <p class="error-message">{errors.email}</p>
                {/if}
    
                <label for="password">Password :</label>
                {#if showPassword}
                    <input type="text" id="password" class={errors.password ? "input-error" : ""} placeholder="Password" bind:value={password} on:input={() => handleInput('password')}>
                {:else}
                    <input type="password" id="password" class={errors.password ? "input-error" : ""} placeholder="Password" bind:value={password} on:input={() => handleInput('password')}>
                {/if}
                {#if errors.password}
                    <p class="error-message">{errors.password}</p>
                {/if}
    
                <label for="password_confirmation">Confirm password :</label>
                <input type="password" id="password_confirmation" class={errors.password_confirmation ? "input-error" : ""} placeholder="Confirm password" bind:value={password_confirmation} on:input={() => handleInput('password_confirmation')}>
                {#if errors.password_confirmation}
                    <p class="error-message">{errors.password_confirmation}</p>
                {/if}
    
                <div class="check">
                    <input type="checkbox" id="checkbox" bind:checked={showPassword}>
                    <span>Show password</span>
                </div>
    
                <button type="submit" disabled={Object.values(errors).some(error => error) || !name || !email || !password || !password_confirmation || clicked}>
                    {clicked ? 'Loading...' : 'Sign Up'}
                </button>
    
                <p>or</p>
                <p>Already have an account? <a href="/">Sign In</a></p>
            </form>
        </div>
    </div>
</div>
    
<style>
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
        font-family: 'poppins';
        font-size: 13px;
    }
    .content h1{
        color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: center;
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
        margin-right: auto;
        margin-left: auto;
    }
    .content form label {
        color: rgb(200, 200, 200);
    }
    .content form input, .content button{
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
    .content form .error-message {
        color: rgb(255, 37, 37);
        font-size: 11px;
        margin-top: -5px;
        margin-bottom: 5px;
        text-align: left;
    }
    .content form .input-error {
        border: 1px solid rgb(255, 37, 37);
    }
    .content form button{
        font-size: 15px;
        margin-top: 20px;
    }
    .content form button:hover{
        cursor: pointer;
        background-color: green;
    }
    .content form button:disabled{
        color: gray;
    }
    .content form button:disabled:hover{
        cursor: not-allowed;
        background-color: rgb(23, 23, 23);
    }
    .content .check{
        display: flex;
        align-items: center;
        color: rgb(200, 200, 200);
    }
    .content form .check input{
        position: relative;
        width: 15px;
        height: 15px;
        cursor: pointer;
        margin-right: 10px;
    }
    .content form .check input:checked{
        accent-color: green;
    }
</style>