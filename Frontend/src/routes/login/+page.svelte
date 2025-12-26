<script>
    import {goto} from "$app/navigation";
    import { login } from "../../api/auth";

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
                error = "Email ou mot de passe incorrect.";
            }
        } catch (err) {
            clicked = false;
            error = "Erreur de connexion. Veuillez réessayer.";
        }
    }
    
</script>

<!-- Background avec dégradé radial bleu profond -->
<div class="min-h-screen w-full flex items-center justify-center selection:bg-blue-500/30 overflow-hidden relative">
    
    <div class="relative z-10 w-full max-w-md p-4">
        <!-- Carte principale avec effet Glassmorphism -->
        <div class="bg-black/20 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-8 md:p-10">
            
            <!-- En-tête -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-13 h-13 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8-0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Connexion</h1>
                <p class="text-gray-400 mt-2">Heureux de vous revoir</p>
            </div>

            <form class="space-y-6" on:submit|preventDefault={handleLogin}>
                
                {#if error}
                    <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-3 rounded-xl text-sm text-center">
                        {error}
                    </div>
                {/if}

                <!-- Champ Email -->
                <div class="space-y-2">
                    <label for="email" class="text-xs font-semibold text-gray-400 tracking-wider ml-1">Adresse Email</label>
                    <div class="relative group">
                        <input 
                            type="email" 
                            id="email" 
                            bind:value={formData.email}
                            placeholder="nom@exemple.com"
                            class="w-full bg-white/5 border {error && !formData.email ? 'border-red-500/50' : 'border-white/10'} group-hover:border-blue-500/50 focus:border-blue-500 transition-all duration-300 rounded-xl px-4 py-3.5 text-white outline-none placeholder:text-gray-600"
                        />
                    </div>
                </div>

                <!-- Champ Mot de passe -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="text-xs font-semibold text-gray-400 tracking-wider">Mot de passe</label>
                        <a href="/" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">Oublié ?</a>
                    </div>
                    <div class="relative group">
                        <input 
                            type={showPassword ? "text" : "password"} 
                            id="password" 
                            bind:value={formData.password}
                            placeholder="••••••••"
                            class="w-full bg-white/5 border {error && !formData.password ? 'border-red-500/50' : 'border-white/10'} group-hover:border-blue-500/50 focus:border-blue-500 transition-all duration-300 rounded-xl px-4 py-3.5 text-white outline-none placeholder:text-gray-600"
                        />
                    </div>
                </div>

                <!-- Options secondaires -->
                <div class="flex items-center space-x-2 px-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" bind:checked={showPassword} class="sr-only peer">
                        <div class="w-9 h-5 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-xs text-gray-400">Afficher le mot de passe</span>
                    </label>
                </div>

                <!-- Bouton de soumission -->
                <button 
                    disabled={clicked}
                    class="w-full relative group overflow-hidden bg-white text-black font-bold py-4 rounded-xl transition-all duration-300 hover:bg-blue-500 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed shadow-xl shadow-white/5 hover:shadow-blue-500/20"
                >
                    {#if clicked}
                        <span class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3 text-current" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Traitement...
                        </span>
                    {:else}
                        Se connecter
                    {/if}
                </button>
            </form>

            <!-- Pied de page -->
            <div class="mt-8 pt-8 border-t border-white/5 text-center">
                <p class="text-gray-500 text-sm">
                    Pas encore de compte ? 
                    <a href="/signup" class="text-white font-semibold hover:text-blue-400 underline-offset-4 hover:underline transition-all">Créer un compte</a>
                </p>
            </div>
        </div>
    </div>
</div>