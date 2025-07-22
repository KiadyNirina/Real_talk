<script>
    import NavSettings from "../../lib/navlat/navSettings.svelte";

</script>

<div class="body">
    <div class="content h-[95vh]">
        <NavSettings/>
        <div class="right">
            <div class="list">
                <a href="/settings/info" class="profile">
                    <img src="/utilisateur.png" alt="">
                    <p>Personnal Informations</p>
                </a>
                <a href="" class="profile">
                    <img src="/parametre.png" alt="">
                    <p>Security</p>
                </a>
            </div>
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
    .profile{
        display: flex;
        align-items: center;
        border-radius: 15px;
        padding: 5px;
        text-decoration: none;
        color: white;
    }
    .profile:hover{
        background-color: rgba(255, 255, 255, 0.097);
    }
    .profile img{
        border-radius: 100%;
        height: 30px;
        margin-right: 15px;
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
            height: 100%;
            padding: 10px;
            margin-top: 100px;
        }
    }
</style>