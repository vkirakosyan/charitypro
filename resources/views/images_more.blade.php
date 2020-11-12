@include('layouts/header')

<main id="aboutWrap">
        <div class="container">
            <div class="row">
                <div class="col-12 ">
                    <div class="wayPath gallMore">
                        <p class="mb-0 ">
                            <a href="{{url('')}}">Գլխավոր</a>
                            <a href="{{url('gallery')}}">Ֆոտոշարք</a>
                            <span>Բոլորը</span>
                        </p>
                    </div>
                </div>
            </div>
           {{$title}}
            <div class="row galleryContent pt-4">
              
                @foreach($images as $image)
                <div class="col-12 col-md-6 col-lg-3">
                    <a href='{{url("images/Images/$image")}}' data-fancybox="images" data-caption="My caption">
                        <img src='{{url("images/Images/$image")}}' class="img-fluid">
                        <i class="lnr lnr-magnifier"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @if($count>1)
            <div class="col-12 pageinationGallery d-flex justify-content-center">
                <a @if(isset($_GET['page']) && $_GET['page'] != 1) href="?page={{$_GET['page']-1}}" @else class='disabledPage' @endif ><span class="lnr lnr-arrow-left"></span></a>
            @for($i=0;$i < $count;$i++)
                  @if(isset($_GET['page']))
                      @if(($i+1 == $_GET['page']))
                        <a class='activePage' href="?page={{$i+1}}">{{$i+1}}</a>
                          @else
                            <a href="?page={{$i+1}}">{{$i+1}}</a>
                        @endif

                        @else
                                @if(($i == 0))
                                    <a class='activePage' href="?page={{$i+1}}">{{$i+1}}</a>
                                @else
                                    <a href="?page={{$i+1}}">{{$i+1}}</a>
                        @endif


                   @endif
                @endfor
                <a @if(isset($_GET['page']) && ($_GET['page'] != $count)) href="?page={{$_GET['page']+1}}"
                   @elseif(!isset($_GET['page']))
                   href="?page=2"

                   @else class='disabledPage' @endif ><span class="lnr lnr-arrow-right"></span></a>

            </div>
             @endif
        </div>
    </main>
    @include('layouts/footer')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.js"></script>
<script type="text/javascript">
    $("[data-fancybox]").fancybox({
        // Options will go here
    });
</script>