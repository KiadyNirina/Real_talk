<script>
    import NavHome from '../../lib/navlat/navHome.svelte';

</script>

<div class="body">
    <div class="content h-[95vh]">
        <NavHome/>
        <div class="right">
            <p>Bonjour</p>
        </div>
    </div>
</div>

<style>
    .body{
        color: white;
    }
    .content{
        padding: 5px;
        display: flex;
        font-size: 15px;
    }
    .right{
        width: 80%;
    }
    .right{
        border: 1px solid rgba(255, 255, 255, 0.057);
        margin: 5px;
        padding: 15px;
        border-radius: 35px;
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
            height: 100vh;
            padding: 10px;
            margin-top: 80px;
        }
    }
</style>