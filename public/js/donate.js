/*function Donate(){
    this.__functions.call(this);
}
Donate.prototype = {
    __functions:function(){
        this.__addDonate();  
        this.__showModal(document.querySelectorAll('.moreDonate'))
        this.__swiperInit();
    },
    __swiperInit:()=>{
        var largeSlider = new Swiper('.charitySlider', {
            paginationClickable: true,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            spaceBetween: 15,
            autoHeight: true,
            

        });
        var thumbSlider = new Swiper('.charitySliderThumb', {
            paginationClickable: true,
            spaceBetween: 10,
            slidesPerView: 3,
            autoHeight: true,
            breakpoints: {
                991: {
                    slidesPerView: 2
                },
                767: {
                    slidesPerView: 3
                }
            }
        });
        document.querySelectorAll('.swipe-slide-to').forEach(element => {
            element
            .addEventListener('click', (e)=> {
                var index = e.target.getAttribute('data-slide-to');
                largeSlider.slideTo(index, 500);
            });
        })
    },
    __addDonate:()=>{
        document.querySelector('.addWorkButton').addEventListener("click", (e)=>{
            document.querySelector('.addWorkWrap').style.display = (document.querySelector('.addWorkWrap').style.display) ==  "block"?"none":"block"
        })
    },
    __showModal:function (arrElem){
        var that = this
        arrElem.forEach(element=>{
            element.
            addEventListener("click", (e)=>{
                document.addEventListener('click', (el)=>{
                    if(!el.target.closest(".commonInfoDonate") || el.target.closest(".closeModalDonate")){
                        document.body.classList.remove("afterForm");
                      //  console.log(document.querySelector('.commonInfoDonate'));
                        document.querySelector('.commonInfoDonate').setAttribute('style', 'opacity:0; visibility:hidden; top:0px;');
                    }
                })
                setTimeout(()=>{
                   // console.log('this',e);
                    var images = that.__createModals(e.target);
                    //console.log(images)
                    document.querySelector('.big-slide').innerHTML='';
                    for (var i = 0; i <images.length ; i++) {
                        var elem = document.createElement('div');
                        elem.className = "swiper-slide";
                        var elem1 = document.createElement('div');
                        elem1.className = "large_slide_item";
                        var img = document.createElement('img');
                        img.className = "img-fluid";
                        img.src="/images/Donations/"+images[i];
                        //console.log(img)
                        //img.setAttribute('data-slide-to',"2");
                        elem1.appendChild(img);
                        //console.log(elem1)
                        elem.appendChild(elem1);
                       // console.log(elem)
                      //  console.log(document.querySelector('.big-slide'))
                        document.querySelector('.big-slide').appendChild(elem);
                     //   console.log(elem)
                      //  document.querySelector('.small-img').appendChild(elem);
                    }
                    document.querySelector('.commonInfoDonate')
                  //  console.log(images)
                    document.querySelector('.commonInfoDonate').setAttribute('style', 'opacity:1; visibility:visible; top:60px;');
                    document.body.classList.add("afterForm");
                }, 0)
            })
        })
    },
     __createModals:(element)=>{
       // console.log(element)
            var images = element.closest('a').getAttribute('images');
            return JSON.parse(images);

    }


    
}
var DonateClass = new Donate();
var Swiper= new Swiper('.swiper-container') */
function image_zoom(){
    $('.imgBox').imgZoom({
        boxWidth: 400,
        boxHeight: 400,
        marginLeft: 5,
        origin: 'data-origin'
    });
}
function Donate(){
    this.__functions.call(this);
}
Donate.prototype = {
    __functions:function(){
        this.__addDonate();    
           
        this.__showModal(document.querySelectorAll('.moreDonate'))
    },
    __swiperInit:()=>{
        var largeSlider = new Swiper('.charitySlider', {
            paginationClickable: true,
            nextButton: '.next_large',
            prevButton: '.prev_large',
            spaceBetween: 15,
            autoHeight: true,
            

        });
        var thumbSlider = new Swiper('.charitySliderThumb', {
            paginationClickable: true,
            spaceBetween: 10,
            slidesPerView: 3,
            autoHeight: true,
            breakpoints: {
                991: {
                    slidesPerView: 2
                },
                767: {
                    slidesPerView: 3
                }
            }
        });
        document.querySelectorAll('.swipe-slide-to').forEach(element => {
            element
            .addEventListener('click', (e)=> {
                var index = e.target.getAttribute('data-slide-to');
                largeSlider.slideTo(index, 500);
            });
        })
    },
    __addDonate:()=>{
        var userId=$("#user-id").val();
        if(userId != 0){
        document.querySelector('.addWorkButton').addEventListener("click", (e)=>{
            document.querySelector('.addWorkWrap').style.display = (document.querySelector('.addWorkWrap').style.display) ==  "block"?"none":"block"
         
        })
        console.log('sfd')
    }
    },
    __showModal:function (arrElem){
        var that = this
        arrElem.forEach(element=>{
            element.
            addEventListener("click", (e)=>{
                
                setTimeout(()=>{
                    document.querySelector('.big-slide').innerHTML='';
                    document.querySelector('.small-slide').innerHTML =''; 
                    var images = that.__createModals(e.target);
                    for (var i = 0; i <images.length ; i++) {
                        /*var elem = document.createElement('div');
                        elem.className = "swiper-slide";
                        var elem1 = document.createElement('div');
                        elem1.className = "large_slide_item";
                        var img = document.createElement('img');
                        img.className = "img-fluid";
                        img.src="/images/Donations/"+images[i];
                        elem1.appendChild(img);
                        elem.appendChild(elem1);*/
                        document.querySelector('.big-slide').innerHTML += `
                            <div class="swiper-slide ">
                                <div class="large_slide_item imgBox">
                                    <img class="img-fluid" data-origin="/images/Donations/${images[i]}" src="/images/Donations/${images[i]}"> 
                                </div>
                            </div>
                        `;
                        document.querySelector('.small-slide').innerHTML += `
                            <div class="swiper-slide ">
                                <div class="thumb_slide_item">
                                    <img class="swipe-slide-to img-fluid" src="/images/Donations/${images[i]}" data-slide-to=${i}> 
                                </div>
                            </div>
                        `;
                    }
                    DonateClass.__swiperInit(); 
                    document.querySelector('.commonInfoDonate').setAttribute('style', 'opacity:1; visibility:visible; top:60px;');
                    document.body.classList.add("afterForm");
                    
                    

                }, 10)
                setTimeout(()=>{
                    if(window.innerWidth >= 991){
                    image_zoom()
                }
                }, 200)
            })
        })
    },
     __createModals:(element)=>{
       // console.log(element)
            var images = element.closest('a').getAttribute('images');
            return JSON.parse(images);

    }
}
var DonateClass = new Donate();
document.addEventListener('click', (el)=>{
    if(!el.target.closest(".commonInfoDonate") || el.target.closest(".closeModalDonate")){
        document.body.classList.remove("afterForm");
      //  console.log(document.querySelector('.commonInfoDonate'));
        document.querySelector('.commonInfoDonate').setAttribute('style', 'opacity:0; visibility:hidden; top:0px;');
        document.querySelector('.big-slide').style.transform="translate3d(0px, 0px, 0px)";
    }
})