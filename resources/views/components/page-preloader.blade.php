<!-- Pre-loader start -->
<style>
    /**  ===================== Theme-preloader css start ==========================  **/
    .theme-loader {
        height: 100%;
        width: 100%;
        background-color: #fff;
        position: fixed;
        z-index: 999999;
    }

    @-webkit-keyframes ball-scale {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }

        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
            opacity: 0;
        }
    }

    @keyframes ball-scale {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }

        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
            opacity: 0;
        }
    }

    .ball-scale {
        left: 0;
        margin: 0 auto;
        position: absolute;
        right: 0;
        text-align: center;
        top: 45%;
        width: 100%;
    }

    .ball-scale > div {
        -webkit-animation: ball-scale 1s 0s ease-in-out infinite;
        animation: ball-scale 1s 0s ease-in-out infinite;
        background-color: #148f77;
        border-radius: 100px;
        display: inline-block;
        height: 60px;
        width: 60px;
    }

    /**====== Theme-preloader css end ======**/
</style>
<div class="theme-loader" style="">
    <div class="ball-scale">
        <div></div>
    </div>
</div>
<!-- Pre-loader end -->
