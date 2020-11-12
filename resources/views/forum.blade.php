@include('layouts/header')
 <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="wayPath">
                        <p class="mb-0 ">
                            <a href="{{url('')}}">Գլխավոր</a>
                            <span>Ֆորում</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="topPanelForum d-flex justify-content-between">
                        <div class="d-flex flex-column flex-sm-row justify-content-between twoWrap">
                            <div class="themes">
                                <p>Թեմաներ</p>
                                <b>0</b>
                            </div>
                            <div class="participants">
                                <p>Մասնակիցներ</p>
                                <b>0</b>
                            </div>
                        </div>
                        <div class="addForum">
                          @if($userId)
                            <button id="addForumClick" data-ripple data-target="#newForumModal" data-toggle="modal" id="mymodal" class="button ask">Ավելացնել</button>
                            @else
                            <button id="addForumClick" data-toggle="modal" data-target="#signInModalLong" class="button ask">Ավելացնել</button>

                            @endif
                            <div class="addForumForm">
                                <h3>Ավելացնել</h3>
                                <span class="closeFormForum lnr lnr-cross"></span>
                                <form action="" name="addForum" class="addTheme">
                                    <div class="labelTop">
                                        <label>Վերնագիր</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>
                                    <div class="labelTop">
                                        <label>Նկարագրություն</label>
                                        <textarea name="" id=""  rows="6" class="form-control" required></textarea>
                                    </div>
                                    <div class="formBottom">
                                        <input type="submit" class="addButton" value="Ավելացնել" >
                                        <button class="closeButton">Փակել</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="newForumModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h4 class="modal-title" id="myModalLabel">Ավելացնել</h4>
                   </div>
                   <div class="modal-body">
                       <div class="form-group">
                           <label for="title">Վերնագիր</label>
                           <input type="text" name="" autocomplete="off" class="form-control" id="title">
                       </div>
                       <div class="form-group">
                           <label for="desc">Նկարագրություն</label>
                           <textarea class="form-control" rows="5" id="desc"></textarea>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-post addButton">Ավելացնել</button>
                       <button type="button" class="btn btn-default closeButton" data-dismiss="modal">Փակել</button>
                   </div>
               </div>
           </div>
       </div>
                <div class="col-12">

                   <div class="content_all">
                      <div class="forum-success alert alert-success d-none alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>✓</strong> Ձեր թեման հաջողությամբ ավելացվել է։
                    </div>
                    <div class="forum-failure alert alert-danger d-none">
                        <strong>✗</strong> Ձեր թեման պետք է ունենա Վերնագիր և Նկարագրություն։ Նկարագրությունը պետք է լինի 10 տառից ավելի երկար։<a data-toggle="modal" class="alert-link" href="#newForumModal">Ուղղել</a>։
                    </div>
                        <div class="tableForum mt-4">
                       <div class="tableTh trchild">
                           <p>Թեմաներ</p>
                           <p>Գրառումներ</p>
                           <p>Դիտումներ</p>
                           <p>Գրառող</p>
                       </div>




                    <div class="tableTbody content">
            </div>
              <div class="row">
                    <div id="pagination" class="pagination-sm col-12 pageinationGallery d-flex justify-content-center"></div>
                </div>
             </div>


           </div>

                </div>

            </div>
        </div>

    </main>


@include('layouts/footer')
<script src="{{URL('js/forum.js')}}"></script>
