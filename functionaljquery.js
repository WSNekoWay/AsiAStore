$(document).off('focusin.modal');
var allDivImage = $(".regBox")
var counterImg = 0;
$(allDivImage[1]).hide();
$(allDivImage[2]).hide();
setInterval(function() {
    
    
    $(allDivImage[counterImg]).hide();
    if(counterImg == 2){
        $(allDivImage[0]).fadeIn(700);
    }else{
        $(allDivImage[counterImg + 1]).fadeIn(700);

    }
    
    if(counterImg == allDivImage.length - 1){
        counterImg = 0
    }else{
        counterImg++;
    }
}, 5000)


$(".profileImg").change(function(){
    $('#preview').attr('src', URL.createObjectURL(event.target.files[0]));
})

function updaterange(val) {
  document.getElementById('outputrange').value=(val/10); 
}
$(function(){        
  $('#click')
    .prop('checked', localStorage.input === 'true')
    .on('click', function() {
       localStorage.input = this.checked;
        darkFunction()
    })
    .trigger('change');
});

function darkFunction() {
    
    
    
    if($(".carousel-control-next-icon").css("filter") == "invert(1)"){
        $(".carousel-control-next-icon").css("filter", "invert(0)");
        $(".carousel-control-prev-icon").css("filter", "invert(0)");
       
    }else{
        $(".carousel-control-next-icon").css("filter", "invert(1)");
        $(".carousel-control-prev-icon").css("filter", "invert(1)");
        
    }
    
    var element = document.body;
    element.classList.toggle("dark-mode");
    
    
    var theme;
    if(element.classList.contains("dark-mode")){
      console.log("Dark Mode");
      theme="Dark";
     
    }else{
      console.log("Light mode");
      theme="Light";
      
    }
    localStorage.setItem("PageTheme", JSON.stringify(theme));
    
 }
 let GetTheme = JSON.parse(localStorage.getItem("PageTheme"));
 console.log(GetTheme);
 if(GetTheme==="Dark"){
   document.body.classList = "dark-mode";
   if($(".carousel-control-next-icon").css("filter") == "invert(1)"){
    $(".carousel-control-next-icon").css("filter", "invert(0)");
    $(".carousel-control-prev-icon").css("filter", "invert(0)");
    
}
 }


 

 const scrollElements = document.querySelectorAll(".js-scroll");
const throttleCount = document.getElementById('throttle-count');
const scrollCount = document.getElementById('scroll-count');

var throttleTimer;

const throttle = (callback, time) => {
  if (throttleTimer) return;

  throttleTimer = true;
  setTimeout(() => {
    callback();
    throttleTimer = false;
  }, time);
}

const elementInView = (el, dividend = 1) => {
  const elementTop = el.getBoundingClientRect().top;

  return (
    elementTop <=
    (window.innerHeight || document.documentElement.clientHeight) / dividend
  );
};

const elementOutofView = (el) => {
  const elementTop = el.getBoundingClientRect().top;

  return (
    elementTop > (window.innerHeight || document.documentElement.clientHeight)
  );
};

const displayScrollElement = (element) => {
  element.classList.add("scrolled");
};

const hideScrollElement = (element) => {
  element.classList.remove("scrolled");
};

const handleScrollAnimation = () => {
  scrollElements.forEach((el) => {
    if (elementInView(el, 1.25)) {
      displayScrollElement(el);
    } else if (elementOutofView(el)) {
      hideScrollElement(el)
    }
  })
}
var timer=0;
var count=0;
var scroll = 0;

$("#registerBtn").click(function() {
    
  $('html, body').animate({
      scrollTop: $("#registerForm").offset().top
  }, 500);
});
$(window).on('resize', function(event){
  var windowWidth = $(window).width();
  console.log("ayangwansen");
  if(windowWidth < 980){
      
      $("#registerBtn").css('margin-top', "10px")
      $("#ketRegister").css("display", "none");
      $(".registerTitle").prependTo(".registerContainer");
  }else{
      $("#registerBtn").css('margin-top', "0")
      $("#ketRegister").css("display", "block");
      if($('.registerTitle','.registerContainer').length == 1){
          $(".registerTitle").prependTo("#registerForm");
      }
      
  }
})
$(document).ready(function(){
  $('#data').after('<div id="nav"></div>');
  var rowsShown = 5;
  var rowsTotal = $('#data tbody tr').length;
  var numPages = rowsTotal/rowsShown;
  for(i = 0;i < numPages;i++) {
      var pageNum = i + 1;
      $('#nav').append('<a href="#" rel="'+i+'">'+pageNum+'</a> ');
  }
  $('#data tbody tr').hide();
  $('#data tbody tr').slice(0, rowsShown).show();
  $('#nav a:first').addClass('active');
  $('#nav a').bind('click', function(){

      $('#nav a').removeClass('active');
      $(this).addClass('active');
      var currPage = $(this).attr('rel');
      var startItem = currPage * rowsShown;
      var endItem = startItem + rowsShown;
      $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
      css('display','table-row').animate({opacity:1}, 300);
  });
});
