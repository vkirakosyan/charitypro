@include('layouts/header')

<main id="aboutWrap">
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="wayPath gallMore">
                    <p class="mb-0 ">
                        <a href="{{url('')}}">Գլխավոր</a>
                        <span>Հոլովակներ</span>
                    </p>
                </div> 
               
            </div>
        </div>
        <div class="row videoContent pt-4">
        @foreach($youtube_links as $link)
            <div class="col-12 col-md-6 col-lg-3">
                <a href='javascript:void(0)' class="iframeShowLarge" data-id='{{$link}}'>
                    <img src="https://img.youtube.com/vi/{{$link}}/mqdefault.jpg" data-videoindex="0" class="youtube_img" >
                    <img src="./img/play-button.png">
                </a>
            </div>
        @endforeach
        </div>
    </div>
    <span class="lnr lnr-chevron-up jumpTo"></span>
        <div class="fancyVideo">
                <div class="topPanelFancy">
                    <div class="middleButtons">
                        <span class="lnr lnr-chevron-left Video" data-video="prev"></span>
                        <div class="pageCount">
                            <span class="activeCount"></span>
                            <span class="allCount"></span>
                        </div>
                        <span class="lnr lnr-chevron-right Video" data-video="next"></span>
                    </div>
                    <span class="lnr lnr-cross closeFancy"></span>
                </div>
                <div class="fancyIframe">
                    <iframe src="" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    <img src="./img/loaderYoutube.gif" class="preloaderVideo"> 
                </div>
            </div>
            </main>
            <script src="js/video.js"></script>
@include('layouts/footer')