<script>
    import NavChat from "../../lib/navlat/navChat.svelte";
</script>

<div class="body">
    <div class="content">
        <NavChat/>
        <div class="right">
            <div class="profile">
                <img src="/utilisateur.png" alt="">
                <p>Chat name</p>
            </div>
            <div class="message">
                <div class="content-message">
                    <img src="/utilisateur.png" alt="">
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat, obcaecati?
                    </p>
                </div>

                <div class="content-message-send">
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat, obcaecati?
                    </p>
                </div>
                <div class="content-message-send">
                    <p>
                        Lorem ipsum dolor
                    </p>
                </div>
            </div>

            <div class="input">
                <textarea name="" id="" placeholder="Enter the message"></textarea>
                <button><img src="/message.png" alt=""></button>
            </div>

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
    .right{
        border: 1px solid rgba(255, 255, 255, 0.381);
        margin: 5px;
        padding: 15px;
        border-radius: 10px;
    }
    .profile{
        display: flex;
        align-items: center;
    }
    .profile img{
        border: 1px solid white;
        border-radius: 100%;
        padding: 2px;
        height: 40px;
        margin-right: 15px;
    }
    .message{
        border: 1px solid rgba(255, 255, 255, 0.139);
        border-radius: 10px;
        padding: 10px;
    }
    .content-message{
        display: flex;
        align-items: center;
        width: 60%;
    }
    .content-message img{
        height: 40px;
    }
    .content-message p{
        background-color: rgba(255, 255, 255, 0.404);
        padding: 15px;
        border-radius: 15px;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 20px;
        margin: 5px;
    }
    .content-message-send p{
        background-color: green;
        padding: 15px;
        border-radius: 15px;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 20px;
        margin: 5px;
    }
    .content-message-send{
        width: 60%;
        margin-left: auto;
    }
    .input{
        display: flex;
        align-items: center;
        margin-top: 5px;
    }
    .input button{
        background-color: transparent;
        border: none;
        border-radius: 15px;
    }
    .input img{
        height: auto;
    }
    .input button:hover{
        background-color: rgba(255, 255, 255, 0.13);
        cursor: pointer;
    }
    .input textarea{
        width: 100%;
        border-radius: 15px;
        background: transparent;
        padding: 10px;
        border: none;
        color: white;
    }
</style>