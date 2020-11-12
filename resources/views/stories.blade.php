@section('pageTitle', $pageTitle)
@include('layouts/header')
<main id="aboutWrap">
        <div class="container">
            <div class="row">
                <div class="col-12 ">
                    <div class="wayPath">
                        <p class="mb-0 ">
                            <a href="/">Գլխավոր</a>
                            <span>Պատմություններ</span>
                        </p>
                    </div> 
                  
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row ">
             
                
                @foreach($data as $item)
                    <div class="commonStoriesBlock flex-item">
                        <img src="{{URL('images/Stories', json_decode($item->images)[0])}}" class="img-fluid" alt="" width="100%">
                        <div class="dateSee">
                            <p>
                                <img src="{{URL('images/Users', $item->user_img)}}" class="user-image ">
                                {{$item->user_name}}
                            </p>
                            <p>
                         
                             @php
                                $date  = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at);
                                $month = \App\Enum\DateHelper::get($date->format('n'))->monthName()->long;
                            @endphp
                                <span class="lnr lnr-calendar-full"></span>
                                {{$date->format('d')}} {{$month}} {{$date->format('Y')}}   
                            </p>
                            <p>
                                <span class="lnr lnr-bubble"></span>
                                <a href="javascript:void(0)" class="storiesComment" data-comment="showComment1">{{$item->comments_counts}} Մեկնաբանություն</a>        
                            </p>

                        </div>
                        <div class="data-animate">
                            <h4 class="storiesItemName"><a href="{{URL('stories/details', $item->id)}}">   {{$item->title}}</a></h4>
                            <p class="descriptionStories">
                                {{ str_limit($item->description, $limit = 150, $end = '...') }}
                            </p>
                        </div>
                        <!-- <div class="commentsWrap hideAndShowComment" id="showComment1">
                            <h4>Մեկնաբանություն</h4>
                            <div class="allCommentsWrap">
                                <div class="writeUser">
                                    <div class="userSection">
                                        <p >
                                            <img src="" style="max-width:50px;border-radius:50%" alt="">
                                            <span>Tigran</span>
                                        </p>   
                                        <span class="d-block">Երք, Ապր 10 2018, 21:45</span>
                                    </div> 
                                    <div class="userComment">
                                        <p>   ծանոթանալով այս կայքին հասկացա որ դուք կարող եք անել անհնարինը` օժանդակելով կարիքավորներին ու հաշմանդամներին:</p>
                                        <div class="subCommentsUser">
                                            <img src="./img/avatar.png" style="max-width:50px;border-radius:50%" alt="">
                                            <div class="subUserComments">
                                                <b>First Name</b>
                                                <p>Մենք կփորձենք դառնալ կապող օղակ բոլոր կարիքավորների, կամավորների և բարեգործների միջև:</p>
                                                <div class="bottomLikes">
                                                    <p class="likePut">
                                                        <span class="lnr lnr-thumbs-up"></span>
                                                        1
                                                    </p>
                                                    <p class="dislikePut">
                                                        <span class="lnr lnr-thumbs-down"></span>
                                                        1
                                                    </p>
                                                    <p>
                                                        <span class="lnr lnr-history"></span>
                                                        Չոր, Ապր 11 2018 @ 17:48
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <form action="" class="addSubCommentForm">
                                            <textarea name=""  rows="3" placeholder="comment" class="form-control"></textarea>
                                            <input type="submit" value="Add Comment" class="addSubComment">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    @endforeach
                    </div>
                    <?php
                        // config
                        $link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
// dd($pagination)
                        $page_count = ceil($pagination['total'] / $pagination['per_page']);
                        // if()
                    ?>
                    @if($pagination['per_page'] < $pagination['total'])
                    <div class="pageinationStories d-flex justify-content-center">
                        @if($pagination['current_page'] > 1)
                        <a href="?page={{$pagination['current_page']-1}}" ><span class="lnr lnr-arrow-left"></span></a>
                        @else
                        <a href="javascript:void(0)" class="disabledPage"><span class="lnr lnr-arrow-left"></span></a>
                        @endif
                        @for($i=0; $i <= floor($pagination['total'] / $pagination['per_page']) && $i < $link_limit; $i++)
                            @if($i + 1 == $pagination['current_page'])
                            <a href="?page={{$i+1}}" class="activePage">{{$pagination['current_page']}}</a>
                            @else
                            <a href="?page={{$i+1}}" class="activePage">{{$i+1}}</a>
                            @endif
                        @endfor
                        @if($pagination['total'] > $pagination['per_page'] * $pagination['current_page'] )
                        <a href="?page={{$pagination['current_page']+1}}"><span class="lnr lnr-arrow-right"></span></a>
                        @else
                        <a href="javascript:void(0)" class="disabledPage" ><span class="lnr lnr-arrow-right"></span></a>
                        @endif
                    </div>
                    @endif
                
            </div>
        </div>
    </main>

    @include('layouts/footer')