<script>
    import { goto } from "$app/navigation";
</script>

<div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
    <h1 class="text-4xl font-bold text-white mb-4">Real_Talk</h1>
    <p class="text-gray-400 mb-8">Votre plateforme de communication ultime.</p>
    <button 
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-300"
        on:click={() => goto('/login')}
    >
        Se connecter
    </button>
</div>