function UserData(data){
  this.__init.call(this, data);
};

UserData.prototype = {
  __init: function(data) {
    let info = JSON.parse(data);
    this.img = function() {
      return info.img;
    };
    this.id = function() {
      return info.id;
    };
    this.email = function() {
      return info.email;
    };
    this.name = function() {
      return info.name;
    };
    this.gender = function() {
      return info.gender;
    }; 
    this.__activeTab();

    var _arrEvent = [],
        _initObj  = {},
        _element = null;
    if(document.querySelectorAll('.eventsDescTXT').length !== 0){
      _arrEvent = [120, 50];
      _element = document.querySelectorAll('.eventsDescTXT');
    }
    if(document.querySelectorAll('.jobDescTxt').length !== 0){
      _arrEvent = [50, 15];
      _element = document.querySelectorAll('.jobDescTxt');
    }
    ({0:_initObj.scrollHeight, 1:_initObj.limitWord} = _arrEvent);
    
    setTimeout(()=>{
      this.__dottedTxt(_element, _initObj);
    }, 500)
  },
  __activeTab:()=>{
    if(document.querySelector('.leftAside')){
      Array.from(document.querySelectorAll('.leftAside > ul')[0].children).map((elem, index)=>{
        if(elem.classList.contains("activeThumb")){
          let img     = document.querySelector(`.${elem.className} img`),
              imgName = img.getAttribute('src').split("/").pop().split(".").shift();
          img.setAttribute('src', img.getAttribute('src').replace(imgName, imgName+'white'))
        }
      })
    }
  },
  __dottedTxt:(elem, _initObjConstructor)=>{
    const objElipses = {
        elemScrollHeight: _initObjConstructor.scrollHeight,
        wordCount:0,
        wordArr:[],
        element:null,
        limitWord:_initObjConstructor.limitWord,
        set setWord (words){
          this.wordArr = words[0];
          this.wordCount = words[0].length; 
          this.element = words[1];
          this.splitTxt; 
        },
        get splitTxt(){
          if(this.wordCount >= this.limitWord || this.element.scrollHeight > this.elemScrollHeight){
            this.element.innerText = ""
            for(let i =0; i < this.wordCount; i++){
              if(i <= this.limitWord && this.element.scrollHeight < this.elemScrollHeight){
                this.element.innerText += " " + this.wordArr[i]; 
              }
              else{
                this.element.innerText += '...';
                break;
              }
            }
          }
          return this.element
        }      
    }
    if(elem)
    Array.from(elem).forEach( (elm)=>{
      elm.style.opacity = "1";
      elm.innerText.split(' ');
      objElipses.setWord = [elm.innerText.split(' '), elm];
    })

  }
};







$(document).ready(function() {
  $.ajax({
      type: 'get',
      url:"donCat",
      // dataType: 'json',
      success: function (data) {
         $('#don-cat').append(data);
      }

  });
});