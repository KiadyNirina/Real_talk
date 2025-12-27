<script>
    import { onMount } from 'svelte';
    import Icon from "@iconify/svelte";
    import { getUserInfo } from '../api/user';
    import { logout } from '../api/auth';
    import { goto } from "$app/navigation";
    import { page } from '$app/stores';

    let currentUser = null;
    let alertLogout = false;
    
    export let activePath = "/home"; 

    const menuItems = [
        { href: "/home", icon: "solar:home-2-linear", text: "Accueil" },
        { href: "/chat/room", icon: "solar:chat-line-linear", text: "Chat", badge: "4" },
        { href: "/settings", icon: "solar:settings-linear", text: "Paramètres" }
    ];

    onMount(async () => {
        try {
            currentUser = await getUserInfo();
        } catch (error) {
            console.error('Erreur:', error);
        }
    });

    const toggleLogoutAlert = (show = true) => alertLogout = show;
    
    const confirmLogout = () => {
        logout();
        goto('/');
        alertLogout = false;
        console.log("Déconnexion confirmée");
    };
</script>

<!-- SIDEBAR DESKTOP (Cache sur mobile) -->
<nav class="fixed left-5 top-5 bottom-5 w-72 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] hidden lg:flex flex-col p-6 z-50 shadow-2xl">
    {#if currentUser}
        <!-- Profil -->
        <div class="flex items-center space-x-4 mb-10 p-2">
            <div class="relative w-12 h-12 flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name={currentUser.name}&background=2563eb&color=fff" alt="Avatar" class="w-full h-full rounded-2xl border border-white/20 object-cover">
                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-[#050505] rounded-full"></span>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Bienvenue</p>
                <p class="text-white font-semibold truncate w-full">{currentUser.name}</p>
            </div>
        </div>

        <!-- Menu -->
        <div class="flex-1 space-y-2">
            {#each menuItems as item}
                <a 
                    href={item.href} 
                    class="flex items-center space-x-4 p-4 rounded-2xl transition-all duration-300 group no-underline
                    {activePath === item.href ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-400 hover:bg-white/5 hover:text-white'}"
                >
                    <Icon icon={item.icon} width="24" />
                    <span class="font-medium">{item.text}</span>
                    {#if item.badge}
                        <span class="ml-auto bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{item.badge}</span>
                    {/if}
                </a>
            {/each}
        </div>

        <!-- Déconnexion -->
        <div class="mt-auto pt-6 border-t border-white/5">
            <button 
                on:click={() => toggleLogoutAlert(true)}
                class="w-full flex items-center space-x-4 p-4 rounded-2xl text-red-400/70 hover:bg-red-500/10 hover:text-red-400 transition-all duration-300 bg-transparent border-none cursor-pointer"
            >
                <Icon icon="solar:logout-linear" width="24" />
                <span class="font-medium">Déconnexion</span>
            </button>
        </div>
    {:else}
        <!-- Loading Skeleton -->
        <div class="animate-pulse space-y-8">
            <div class="flex items-center space-x-3"><div class="w-12 h-12 bg-white/10 rounded-2xl"></div><div class="h-4 w-24 bg-white/10 rounded"></div></div>
            <div class="space-y-4"><div class="h-12 w-full bg-white/5 rounded-xl"></div><div class="h-12 w-full bg-white/5 rounded-xl"></div></div>
        </div>
    {/if}
</nav>

<!-- BOTTOM NAV MOBILE (Cache sur desktop) -->
<div class="fixed bottom-0 left-0 right-0 h-20 bg-black/80 backdrop-blur-xl border-t border-white/10 flex lg:hidden items-center justify-around px-4 z-[60] rounded-t-[2rem]">
    {#each menuItems as item}
        <a 
            href={item.href} 
            class="flex flex-col items-center space-y-1 no-underline transition-colors
            {activePath === item.href ? 'text-blue-500' : 'text-gray-500'}"
        >
            <div class="relative">
                <Icon icon={item.icon} width="22" />
                {#if item.badge}<span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border border-black"></span>{/if}
            </div>
            <span class="text-[10px] font-medium">{item.text}</span>
        </a>
    {/each}
    <button on:click={() => toggleLogoutAlert(true)} class="flex flex-col items-center space-y-1 text-red-400/70 bg-transparent border-none">
        <Icon icon="solar:logout-linear" width="22" />
        <span class="text-[10px] font-medium">Sortir</span>
    </button>
</div>

<!-- MODAL DE CONFIRMATION -->
{#if alertLogout}
    <div class="fixed inset-0 bg-black/90 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
        <div class="bg-[#0a0a0a] border border-white/10 rounded-[2.5rem] p-8 max-w-sm w-full text-center shadow-2xl scale-in-center">
            <div class="w-20 h-20 bg-blue-600/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <Icon icon="solar:danger-triangle-bold" width="40" class="text-blue-500" />
            </div>
            <h2 class="text-white text-2xl font-bold mb-2">Déconnexion</h2>
            <p class="text-gray-400 text-sm mb-8">Voulez-vous vraiment quitter votre session actuelle ?</p>
            <div class="flex space-x-4">
                <button on:click={() => toggleLogoutAlert(false)} class="flex-1 bg-white/5 hover:bg-white/10 text-white font-bold py-4 rounded-2xl transition-all border-none cursor-pointer">Annuler</button>
                <button on:click={confirmLogout} class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-blue-600/20 border-none cursor-pointer">Confirmer</button>
            </div>
        </div>
    </div>
{/if}

<style>
    .scale-in-center {
        animation: scale-in-center 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
    }

    @keyframes scale-in-center {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>