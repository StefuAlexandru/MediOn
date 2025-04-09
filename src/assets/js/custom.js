(function($) {

	$(document).ready(function() {
	  $('body').addClass('js');
	  var $menu = $('#menu'),
	    $menulink = $('.menu-link');
	  
	$menulink.click(function() {
	  $menulink.toggleClass('active');
	  $menu.toggleClass('active');
	  return false;
	});});


	videoPopup();


	$('.owl-carousel').owlCarousel({
	    loop:true,
	    margin:30,
	    nav:true,
	    autoplay:true,
		autoplayTimeout:5000,
		autoplayHoverPause:true,
	    responsive:{
	        0:{
	            items:1
	        },
	        550:{
	            items:2
	        },
	        750:{
	            items:3
	        },
	        1000:{
	            items:4
	        },
	        1200:{
	            items:5
	        }
	    }
	})


	$(".Modern-Slider").slick({
	    autoplay:true,
	    autoplaySpeed:10000,
	    speed:600,
	    slidesToShow:1,
	    slidesToScroll:1,
	    pauseOnHover:false,
	    dots:true,
	    pauseOnDotsHover:true,
	    cssEase:'fade',
	   // fade:true,
	    draggable:false,
	    prevArrow:'<button class="PrevArrow"></button>',
	    nextArrow:'<button class="NextArrow"></button>', 
	});


	$("div.features-post").hover(
	    function() {
	        $(this).find("div.content-hide").slideToggle("medium");
	    },
	    function() {
	        $(this).find("div.content-hide").slideToggle("medium");
	    }
	 );


	$( "#tabs" ).tabs();


	const daysElement = document.getElementById("days");
	const hoursElement = document.getElementById("hours");
	const minutesElement = document.getElementById("minutes");
	const secondsElement = document.getElementById("seconds");

  
  	function getTimeRemaining(endtime) {
    
		try {
		const subscriptionDatetime = new Date(endtime);
		if (isNaN(subscriptionDatetime.getTime())) {
			throw new Error("Invalid endtime format");
		}
		const currentTime = new Date();
		const timeDiff = subscriptionDatetime - currentTime;

		const adjustedTimeDiff = Math.max(timeDiff, 0);

		const days = Math.floor(adjustedTimeDiff / (1000 * 60 * 60 * 24));
		const hours = Math.floor((adjustedTimeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		const minutes = Math.floor((adjustedTimeDiff % (1000 * 60 * 60)) / (1000 * 60));
		const seconds = Math.floor((adjustedTimeDiff % (1000 * 60)) / 1000);

		return {
			total: adjustedTimeDiff,
			days: days,
			hours: hours,
			minutes: minutes,
			seconds: seconds
		};
		} catch (error) {
			console.error("Error getting time difference:", error.message);
			return { total: 0, days: 0, hours: 0, minutes: 0, seconds: 0 };
		}
  	}

  	function updateCountdown() {
    

		const countdownElement = document.querySelector('.counter');
		const endtime = countdownElement.dataset.endtime;
		
		const t = getTimeRemaining(endtime);

		
		daysElement.innerHTML = t.days.toString().padStart(2, '0');
		hoursElement.innerHTML = t.hours.toString().padStart(2, '0');
		minutesElement.innerHTML = t.minutes.toString().padStart(2, '0');
		secondsElement.innerHTML = t.seconds.toString().padStart(2, '0');

		
		if (t.total <= 0) {
			clearInterval(updateInterval);
		}
  	}

  	updateCountdown(); 

  	const updateInterval = setInterval(updateCountdown, 1000);

})(jQuery);

