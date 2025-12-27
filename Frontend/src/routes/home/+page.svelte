<script>
    import Sidebar from "../../lib/Sidebar.svelte";
    import Icon from "@iconify/svelte";
    import { onMount } from 'svelte';
    import { getUserInfo } from '../../api/user';

    let userName = null;
    let currentUser = null;

    onMount(async () => {
        try {
            currentUser = await getUserInfo();
            userName = currentUser.name;
        } catch (error) {
            console.error('Erreur:', error);
        }
    });
</script>

<div class="min-h-screen text-white selection:bg-blue-500/30">
    
    <!-- Layout Principal -->
    <div class="flex flex-col lg:flex-row min-h-screen p-0 lg:p-5 gap-5">
        
        <Sidebar activePath="/home" />

        <main class="flex-1 lg:mt-0 lg:ml-80">
            
            <div class="h-full backdrop-blur-2xl border border-white/10 rounded-none lg:rounded-[2.5rem] p-6 lg:p-10 shadow-2xl relative overflow-hidden flex flex-col min-h-[90vh]">
                
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 blur-[100px] rounded-full -mr-32 -mt-32"></div>

                <header class="relative z-10 mb-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-white mb-2">
                                {#if userName}
                                    Bonjour, <span class="text-blue-500">{userName}</span> !
                                {/if}
                            </h1>
                            <p class="text-gray-400 font-medium text-sm lg:text-base">Content de vous revoir parmi nous.</p>
                        </div>
                        
                        <div class="hidden md:flex space-x-3">
                            <button class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all text-white group">
                                <Icon icon="solar:bell-linear" width="24" class="group-hover:text-blue-500 transition-colors" />
                            </button>
                        </div>
                    </div>
                </header>

                <div class="relative z-10 flex-1 flex flex-col items-center justify-center text-center p-8 border border-white/5 rounded-[2rem] bg-white/[0.05]">
                    <div class="mb-6 transform hover:scale-110 transition-transform duration-500">
                        <div class="w-24 h-24 bg-blue-600/10 rounded-full flex items-center justify-center mx-auto shadow-[0_0_50px_rgba(37,99,235,0.1)]">
                            <Icon icon="solar:chat-round-check-bold" width="48" class="text-blue-500" />
                        </div>
                    </div>
                    
                    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-4">Bienvenue sur votre Dashboard</h2>
                    <p class="text-gray-400 max-w-md mx-auto leading-relaxed">
                        C'est ici que l'aventure commence. Utilisez la barre de navigation pour explorer vos messages, 
                        gérer vos paramètres ou simplement rester connecté avec votre communauté.
                    </p>

                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="/chat/room" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-600/20 no-underline flex items-center">
                            <Icon icon="solar:plain-bold" class="mr-2" />
                            Accéder au Chat
                        </a>
                        <button class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl transition-all border border-white/10">
                            En savoir plus
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>

</div>