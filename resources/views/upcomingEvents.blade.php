@section('pageTitle', $pageTitle)
@include('layouts/header')

 <main>
      <div class="container">
       <div class="row">
                <div class="col-12">
                    <div class="wayPath gallMore">
                        <p class="mb-0 ">
                            <a href="{{url('')}}">Գլխավոր</a>
                            <span>Իրադարձություններ</span>
                        </p>
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3 filter-col">
                     <div class="workSection">
                    <h5 class="filter_title">Փնտրել Իրադարձություն</h5>
                   
                    <div class="form-group workForm">
                            <input type="text" value="{{$title}}" class="filter-input title event-filter workInput" placeholder="Իրադարձություն">
                       
                 
                    
                            <select class="filter-input cities event-filter filterCategories">
                                @foreach($cities as $city)
                                    <option value="{{$city->id}}" @if($city_id == $city->id) selected @endif>{{$city->name}}</option>
                                @endforeach
                            </select>
                 <label class="filter_label" for="checkin-select" >Սկիզբ</label>
                                        
                     <input id="checkin-select" value="{{str_replace('-', '/', $date_from)}}" class=" filter-input event-filter datepicker" placeholder="Ամիս / Օր / Տարի" type="text">                    
                 <label class="filter_label" for="checkout-selec">Ավարտ</label>
                                        
                     <input id="checkout-select" value="{{str_replace('-', '/', $date_to)}}" class="filter-input event-filter datepicker " placeholder="Ամիս / Օր / Տարի" type="text">

                   
                    <button class="filter_search btn-event-search searchWork">Որոնել</button>
                </div>
                @if(@$userId)
            <button class="addWorkButton add_event_title accordion">Ավելացնել իրադարձություն</button>
                @else
               <button class="addWorkButton" data-toggle="modal" data-target="#signInModalLong">Ավելացնել իրադարձություն</button>
                @endif
                  <div>  
                    <div class="add-event addWorkWrap">
                        <div class="form-group addWorkForm">
                           
                                <input type="text" class="filter-input new-event" placeholder="Իրադարձություն" name="title">
                            
                       
                                <textarea name="description" id="description" class="filter-input add_event_textarea new-event event-description" placeholder="Նկարագրություն" rows="4" required ></textarea>
                                <input id="checkin-select2" 
                                class="datepicker filter-input event-filter new-event event-time" placeholder="Ամիս / Օր / Տարի" type="text">
                              <input type="text" name="details_location" id="details_location" class="filter-input new-event event-details" placeholder="Հասցե" required />
                                <select name="city_id" class="filter-input cities new-event filterCitys">
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}">{{$city->name}}</option>
                                    @endforeach
                                </select>
                                <label for="file-upload" class="custom-file-upload file-input">
                                </label>
                         <label class="file-label">
                                  <input class="file-input gallery-photo-add new-event event-img filter-input" name="img"  type="file" id="file-upload">
                                  <span class="file-cta">
                                      <span class="file-icon">
                                          <span class="lnr lnr-upload"></span>
                                      </span>
                                      <span class="file-label">
                                      Ներբեռնել նկար...
                                      </span>
                                  </span>
                              </label>
                        {{ csrf_field() }}
                        <button class="add_event_search btn-create-event addWork">Ավելացնել</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="col-12 col-md-8 col-lg-9 events-content ">
      
        @if(isset($data))
            @foreach($data as $item)
                <div class="row event-row eventBlock" data-id="{{$item->id}}">
                    <span class="eventDay">
                            @php
                                $date  = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->time);
                                $month = \App\Enum\DateHelper::get($date->format('n'))->monthName()->short;

                            @endphp
                            {{$date->format('d')}} {{$month}} {{$date->format('Y')}}</span>

                                <div class="img eventDescription">
                                    <img class="img-fluid-mobile" src="{{URL::to('images/Events/', $item->img)}}">
                                
                           
                                <div class=" rightEventWrap">
                                    <h4>
                                      {{$item->title}}
                                    </h4>
                                
                                <div class="event-address locationEvent">
                                    <p><!-- <i class="fa fa-map-marker show-location" data-address="{{$item->details_location}}"></i> -->  <span class="lnr lnr-map-marker" data-address="{{$item->details_location}}"></span> {{$item->details_location}}</p>
                                </div>
                            
                              <p class="eventShort dottedText">{{str_limit($item->description, $limit = 300, $end = '...')}}</p> 
                                <a class="more-event viewEvent" href="{{URL::to('upcoming_events/details', $item->id)}}">Ավելին</a>
                            </div>
                        </div>
                       
                    </div>
                
            @endforeach
            </div>
            </div>
        </div>

            <div class="pagination-content hidden row">
                    <div id="pagination" class="pagination-sm col-12 pageinationGallery d-flex justify-content-center"></div>

            </div>
        @endif
        @if(isset($c_item))
       
             <div class="d-block">
                        <a href="{{$prevUrl}}" class="comeBack"> <span class="lnr lnr-arrow-left"></span> Հետ</a>
                    </div>
                    <div class="eventsMore current current-item " data-item-id="{{$c_item->id}}">
                          <img src="{{URL::to('images/Events', $c_item->img)}}" class="img-fluid">
                          <div class="commaneventWrap">
                              
                                <div class=" eventsMoreDescription">
                                <h4 class="">{{$c_item->title}}</h4>
                            
                                    <div class="event-address locationEvent">
                                    <!-- <i class="fa fa-map-marker show-location" data-address="{{$c_item->details_location}}"> --> <span class="lnr lnr-map-marker"></span>  {{$c_item->details_location}}
                                    </div>
                                    <div class="event-date eventClock">
                                    <span class="lnr lnr-history"></span> {{$c_item->time}}
                                    </div>
                                
                                    <p class="event-desc eventsMoreShort">{!!$c_item->description!!}</p>
                                </div>
                                <div class="interestedBlock">
                                    <div class="iamGoing circle circle-add-go" data-user-goes="{{$c_item->user_goes}}">
                                        <span class="count"  data-plus="+" >{{$c_item->count_goes}}</span>
                                        <span class="lnr lnr-thumbs-up"></span>Գնալու եմ
                                    </div>
                                    <div class="iamInterested circle-add-interested" data-user-interested="{{$c_item->user_interested}}">
                                            <span class="count" data-plus="+" >{{$c_item->count_interested}}</span>
                                            <span class="lnr lnr-star"></span>Հետաքրքիր է
                                        </div>
                                        <div class="seen">
                                            <span  data-plus="+">{{$c_item->count_views}}</span>
                                            <span class="lnr lnr-eye"></span>Դիտում
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        
                            <div class="contactSubject">
                                <h4>Կոնտակտային Տվյալներ</h4>
                                <div class="contactSec">
                                    <p><span class="lnr lnr-envelope"></span> {{$c_item->user_email}}</p>
                                    <p><span class="lnr lnr-user"></span>  {{$c_item->user_name}}</p>
                                </div>
                            </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
           
        @endif
        </div>
    </div>
</div>
<div class="modal fade" id="locationModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="map"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Փակել</button>
            </div>
        </div>
    </div>
</div>
</main>

@include('layouts/footer')
<script src="{{URL('js/upcomingEvents.js')}}"></script>
<script type="text/javascript">
@if(isset($pagination))
    UpcomingEventsClass.initPagination('{!! json_encode($pagination) !!}');
@endif
</script>
