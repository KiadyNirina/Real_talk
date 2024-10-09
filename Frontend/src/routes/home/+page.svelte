<script>
    import NavHome from '../../lib/navlat/navHome.svelte';

</script>

<div class="body">
    <div class="content">
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
        padding: 15px;
        display: flex;
        font-size: 20px;
    }
    .left{
        width: 25%;
    }
    .right{
        width: 75%;
    }
    .right, .left{
        border: 1px solid rgba(255, 255, 255, 0.165);
        margin: 5px;
        padding: 15px;
        border-radius: 35px;
    }
    .profile{
        display: flex;
        align-items: center;
    }
    .profile b{
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }
    .profile img{
        border: 1px solid white;
        border-radius: 100%;
        padding: 2px;
        height: 40px;
        margin-right: 15px;
    }
    .list li{
        border: 1px solid rgba(255, 255, 255, 0.094);
        margin: 0;
        padding: 15px;
        margin-top: 5px;
        border-radius: 5px;
        list-style: none;
    }
    .list li:hover{
        background-color: rgba(255, 255, 255, 0.097);
    }
    .list a{
        text-decoration: none;
        color: white;
        width: 100%;
    }
    .list button{
        width: 100%;
        background-color: transparent;
        color: white;
        border: none;
        margin: 0;
        border-radius: 5px;
        text-align: left;
        font-size: 17px;
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
        }
    }
</style>