@include('layouts/header')
<div class="black02 container our_service">
  <div class="row">
    @include('offeredServices')
    <div class="col-12 col-sm-12 col-md-12 col-lg-10">
        <div class="row backgraund_content">
            <div class="serv-post-page col-12"></div>
        </div>
        <div class="all-content"></div>
        @include('sliderZoom')
    </div>
    @include('advertisements')
  </div>
</div>
<script type="text/javascript">
    var service_id = {{$serviceId}};
</script>
@include('layouts/footer')