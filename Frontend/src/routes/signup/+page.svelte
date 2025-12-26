<script>
    import axios from 'axios';
    
    let showPassword = false;
    let name = "";
    let email = "";
    let password = "";
    let password_confirmation = "";

    let errorName = "";
    let errorEmail = "";
    let errorPassword = "";
    let errorConfirm = "";
    
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
                const securePattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d@$!#%*?&]{6,}$/;
                if (password.length < minLength) {
                    return "Password must be at least 6 characters long.";
                }
                if (!securePattern.test(password)) {
                    return "Password must contain at least one letter, one lowercase letter, one number, and one special character.";
                }
                return "";
            case 'password_confirmation':
                return password !== password_confirmation ? "Passwords do not match." : "";
            default:
                return "";
        }
    }
    
    function handleInput(field) {
        if (field === 'name') errorName = validateField('name');
        if (field === 'email') errorEmail = validateField('email');
        if (field === 'password') errorPassword = validateField('password');
        if (field === 'password_confirmation') errorConfirm = validateField('password_confirmation');
    }
    
    // Fonction pour l'inscription
    async function register() {
        errorName = validateField('name');
        errorEmail = validateField('email');
        errorPassword = validateField('password');
        errorConfirm = validateField('password_confirmation');

        if (errorName || errorEmail || errorPassword || errorConfirm || !name || !email) {
            return;
        }

        clicked = true;
        errorMessage = "";
    
        try {
            await axios.post('http://localhost:8000/api/register', {
                name,
                email,
                password,
                password_confirmation,
            });
    
            successMessage = "Registration successful! You can now log in.";
            errorMessage = "";
            errorName = "";
            errorEmail = "";
            errorPassword = "";
            errorConfirm = "";
            clicked = false;
    
        } catch (err) {
            console.error('Registration error:', err);
            errorMessage = "Registration failed, try again!"
            successMessage = "";
            clicked = false;
        }
    }

    $: isFormInvalid = !!(errorName || errorEmail || errorPassword || errorConfirm) || !name || !email || !password;
</script>

<div class="min-h-screen w-full flex items-center justify-center selection:bg-blue-500/30 overflow-x-hidden relative">

    <div class="relative z-10 w-full max-w-lg p-4">
        <div class="bg-black/20 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-8 md:p-10">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 text-blue-600 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-13 h-13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Créer un compte</h1>
                <p class="text-gray-400 mt-2 text-sm">Rejoignez notre communauté exclusive</p>
            </div>

            <form class="space-y-5" on:submit|preventDefault={register}>
                
                {#if successMessage}
                    <div class="bg-blue-500/10 border border-blue-500/50 text-blue-400 p-3 rounded-xl text-sm text-center">
                        {successMessage}
                    </div>
                {/if}

                {#if errorMessage}
                    <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-3 rounded-xl text-sm text-center animate-pulse">
                        {errorMessage}
                    </div>
                {/if}

                <!-- Username -->
                <div class="space-y-1.5">
                    <label for="username" class="text-xs font-semibold text-gray-400 tracking-widest ml-1">Nom d'utilisateur</label>
                    <input 
                        type="text" 
                        id="username" 
                        bind:value={name}
                        on:input={() => handleInput('name')}
                        class="w-full bg-white/5 border {errorName ? 'border-red-500/50' : 'border-white/10'} focus:border-blue-500 transition-all rounded-xl px-4 py-3 text-white outline-none placeholder:text-gray-600"
                        placeholder="Ex: JohnDoe"
                    />
                    {#if errorName}<p class="text-[11px] text-red-400 ml-1 italic">{errorName}</p>{/if}
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-semibold text-gray-400 tracking-widest ml-1">Adresse Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        bind:value={email}
                        on:input={() => handleInput('email')}
                        class="w-full bg-white/5 border {errorEmail ? 'border-red-500/50' : 'border-white/10'} focus:border-blue-500 transition-all rounded-xl px-4 py-3 text-white outline-none placeholder:text-gray-600"
                        placeholder="john@example.com"
                    />
                    {#if errorEmail}<p class="text-[11px] text-red-400 ml-1 italic">{errorEmail}</p>{/if}
                </div>

                <!-- Passwords Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="password" class="text-xs font-semibold text-gray-400 tracking-widest ml-1">Mot de passe</label>
                        <input 
                            type={showPassword ? "text" : "password"} 
                            id="password" 
                            bind:value={password}
                            on:input={() => handleInput('password')}
                            class="w-full bg-white/5 border {errorPassword ? 'border-red-500/50' : 'border-white/10'} focus:border-blue-500 transition-all rounded-xl px-4 py-3 text-white outline-none placeholder:text-gray-600"
                            placeholder="••••••••"
                        />
                    </div>
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="text-xs font-semibold text-gray-400 tracking-widest ml-1">Confirmation</label>
                        <input 
                            type={showPassword ? "text" : "password"} 
                            id="password_confirmation" 
                            bind:value={password_confirmation}
                            on:input={() => handleInput('password_confirmation')}
                            class="w-full bg-white/5 border {errorConfirm ? 'border-red-500/50' : 'border-white/10'} focus:border-blue-500 transition-all rounded-xl px-4 py-3 text-white outline-none placeholder:text-gray-600"
                            placeholder="••••••••"
                        />
                    </div>
                </div>
                {#if errorPassword || errorConfirm}
                    <p class="text-[11px] text-red-400 ml-1 italic leading-tight">
                        {errorPassword || errorConfirm}
                    </p>
                {/if}

                <!-- Show password toggle -->
                <div class="flex items-center px-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" bind:checked={showPassword} class="sr-only peer">
                        <div class="w-9 h-5 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-xs text-gray-400 tracking-tighter">Afficher les mots de passe</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    disabled={isFormInvalid || clicked}
                    class="w-full relative bg-white text-black font-bold py-3.5 rounded-xl transition-all duration-300 hover:bg-blue-600 hover:text-white disabled:opacity-30 disabled:grayscale shadow-xl hover:shadow-blue-500/20 mt-4"
                >
                    {#if clicked}
                        <span class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-2 text-current" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Inscription...
                        </span>
                    {:else}
                        Créer mon compte
                    {/if}
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-white/5 text-center">
                <p class="text-gray-500 text-sm">
                    Déjà membre ? 
                    <a href="/login" class="text-white font-semibold hover:text-blue-400 transition-colors">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>