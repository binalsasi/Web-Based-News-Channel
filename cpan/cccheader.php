
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>School Of Engineering</title>
<link rel="icon" href="images/logo48.ico" type="image/x-icon">
<link rel="shortcut icon" href="images/logo48.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="./pages/w3.css">
<link rel="stylesheet" href="layout/styles/layout.css" type="text/css" />
<style>

</style>
<script type="text/javascript" src="layout/scripts/jquery.min.js"></script>
<script type="text/javascript" src="layout/scripts/jquery.slidepanel.setup.js"></script>
<script type="text/javascript" src="layout/scripts/jquery.cycle.min.js"></script>
<script type="text/javascript">


$(function() {
    $('#featured_slide').after('<div id="fsn"><ul id="fs_pagination">').cycle({
        timeout: 5000,
        fx: 'fade',
        pager: '#fs_pagination',
        pause: 1,
        pauseOnPagerHover: 0
    });
});

$(function() {
  $('#example_t').vTicker();
});

/*! vTicker 1.15
 http://richhollis.github.com/vticker/ | http://richhollis.github.com/vticker/license/ | based on Jubgits vTicker http://www.jugbit.com/jquery-vticker-vertical-news-ticker/ */
(function(d){var n={speed:700,pause:2000,showItems:1,mousePause:!0,height:0,animate:!0,margin:0,padding:0,startPaused:!1},c={moveUp:function(a,b){c.animate(a,b,"up")},moveDown:function(a,b){c.animate(a,b,"down")},animate:function(a,b,e){var c=a.itemHeight,f=a.options,k=a.element,g=k.children("ul"),l="up"===e?"li:first":"li:last";k.trigger("vticker.beforeTick");var m=g.children(l).clone(!0);0<f.height&&(c=g.children("li:first").height());c+=f.margin+2*f.padding;"down"===e&&g.css("top","-"+c+"px").prepend(m);
if(b&&b.animate){if(a.animating)return;a.animating=!0;g.animate("up"===e?{top:"-="+c+"px"}:{top:0},f.speed,function(){d(g).children(l).remove();d(g).css("top","0px");a.animating=!1;k.trigger("vticker.afterTick")})}else g.children(l).remove(),g.css("top","0px"),k.trigger("vticker.afterTick");"up"===e&&m.appendTo(g)},nextUsePause:function(){var a=d(this).data("state"),b=a.options;a.isPaused||2>a.itemCount||f.next.call(this,{animate:b.animate})},startInterval:function(){var a=d(this).data("state"),b=
this;a.intervalId=setInterval(function(){c.nextUsePause.call(b)},a.options.pause)},stopInterval:function(){var a=d(this).data("state");a&&(a.intervalId&&clearInterval(a.intervalId),a.intervalId=void 0)},restartInterval:function(){c.stopInterval.call(this);c.startInterval.call(this)}},f={init:function(a){f.stop.call(this);var b=jQuery.extend({},n);a=d.extend(b,a);var b=d(this),e={itemCount:b.children("ul").children("li").length,itemHeight:0,itemMargin:0,element:b,animating:!1,options:a,isPaused:a.startPaused?
!0:!1,pausedByCode:!1};d(this).data("state",e);b.css({overflow:"hidden",position:"relative"}).children("ul").css({position:"absolute",margin:0,padding:0}).children("li").css({margin:a.margin,padding:a.padding});isNaN(a.height)||0===a.height?(b.children("ul").children("li").each(function(){var a=d(this);a.height()>e.itemHeight&&(e.itemHeight=a.height())}),b.children("ul").children("li").each(function(){d(this).height(e.itemHeight)}),b.height((e.itemHeight+(a.margin+2*a.padding))*a.showItems+a.margin)):
b.height(a.height);var h=this;a.startPaused||c.startInterval.call(h);a.mousePause&&b.bind("mouseenter",function(){!0!==e.isPaused&&(e.pausedByCode=!0,c.stopInterval.call(h),f.pause.call(h,!0))}).bind("mouseleave",function(){if(!0!==e.isPaused||e.pausedByCode)e.pausedByCode=!1,f.pause.call(h,!1),c.startInterval.call(h)})},pause:function(a){var b=d(this).data("state");if(b){if(2>b.itemCount)return!1;b.isPaused=a;b=b.element;a?(d(this).addClass("paused"),b.trigger("vticker.pause")):(d(this).removeClass("paused"),
b.trigger("vticker.resume"))}},next:function(a){var b=d(this).data("state");if(b){if(b.animating||2>b.itemCount)return!1;c.restartInterval.call(this);c.moveUp(b,a)}},prev:function(a){var b=d(this).data("state");if(b){if(b.animating||2>b.itemCount)return!1;c.restartInterval.call(this);c.moveDown(b,a)}},stop:function(){d(this).data("state")&&c.stopInterval.call(this)},remove:function(){var a=d(this).data("state");a&&(c.stopInterval.call(this),a=a.element,a.unbind(),a.remove())}};d.fn.vTicker=function(a){if(f[a])return f[a].apply(this,
Array.prototype.slice.call(arguments,1));if("object"!==typeof a&&a)d.error("Method "+a+" does not exist on jQuery.vTicker");else return f.init.apply(this,arguments)}})(jQuery);


</script>



</head>
<body>
<div class="wrapper col0" style="">
  <div id="topbar" style="">
    <div id="slidepanel"style="">
     
      <div class="topbox">
        <h2>Login</h2>
        <form action="./pages/division/checklogin.php" method="post">
          <fieldset>
            <legend>Staff Login Form</legend>
            <label for="teachername">Username:
              <input type="text" name="teachername" id="teachername" value="" />
            </label>
            <label for="teacherpass">Password:
              <input type="password" name="teacherpass" id="teacherpass" value="" />
            </label>
            <label for="teacherremember">
              <input class="checkbox" type="checkbox" name="teacherremember" id="teacherremember" checked="checked" />
              Remember me &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="forgot.php">Forgot password ?</a></label>
            <p>
              <input type="submit" name="teacherlogin" id="teacherlogin" value="Login" />
              &nbsp;
              <input type="reset" name="teacherreset" id="teacherreset" value="Reset" />
            </p>
          </fieldset>
        </form>
      </div>
   <!--   <div class="topbox last">
        <h2>Students Login</h2>
        <form action="#" method="post">
          <fieldset>
            <legend>Studdent Login Form</legend>
            <label for="pupilname">Username:
              <input type="text" name="pupilname" id="pupilname" value="" />
            </label>
            <label for="pupilpass">Password:
              <input type="password" name="pupilpass" id="pupilpass" value="" />
            </label>
            <label for="pupilremember">
              <input class="checkbox" type="checkbox" name="pupilremember" id="pupilremember" checked="checked" />
              Remember me</label>
            <p>
              <input type="submit" name="pupillogin" id="pupillogin" value="Login" />
              &nbsp;
              <input type="reset" name="pupilreset" id="pupilreset" value="Reset" />
            </p>
          </fieldset>
        </form>
      </div>-->
      <br class="clear" />
    </div>
    <div id="loginpanel"  >
      <ul>
        <li class="left">Log In Here &raquo;</li>
        <li class="right" id="toggle"><a id="slideit" href="#slidepanel">Administration</a><a id="closeit" style="display: none;" href="#slidepanel">Close Panel</a></li>
      </ul>
	  <form  method="post" style="float:right;padding-top:3px;" id="sitesearch" action="./search/search.php" accept-charset="windows-1252;">
        <fieldset>
          <legend>Site Search</legend>
          <input type="text" value="Search Our Website&hellip;" onfocus="this.value=(this.value=='Search Our Website&hellip;')? '' : this.value ;" />
          </fieldset>
        
      </form> 
    
  
    </div>
    <br class="clear" />
  </div>
  
  
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col1">
 <div id="logoimg">
<a href="http://www.cusat.ac.in" target="_blank"><img src="./images/logo.jpg" class="imghp"></a>
</div>
  <div id="header" >

    <div id="logo">
      <h1 ><a href="index.php">School Of Engineering</a></h1>
      <p><a href="http://www.cusat.ac.in" target="_blank">Cochin University of Science and Technology</a></p>
    </div>
	
	
	
	
    <div id="topnav"  >
	
      <ul >
        <li class="active"><a href="index.php">Home</a></li>
		<li><a href="#">Divisions</a>
		
		<ul style="width:308px;">
		
		<li><a href="pages/division/div_ce.php" >Civil Engineering</a></li>
        <li><a href="pages/division/div_cs.php">Computer Engineering</a></li>
        <li><a href="pages/division/div_me.php">Mechanical Engineering</a></li>
        <li><a href="pages/division/div_ec.php">Electronics Engineering</a></li>
        <li><a href="pages/division/div_ee.php">Electrical Engineering</a></li>
		<li><a href="pages/division/div_it.php">Information Technology</a></li>
        <li><a href="pages/division/div_hm.php">Applied Sciences and Humanities</a></li>
        <li><a href="pages/division/div_fs.php">Safety and Fire Engineering</a></li>
<li class="last"><a href="pages/division/div_ch.php">Chemical Engineering</a></li>
    
		</ul>
		
		</li>
		<li><a href="#">Courses</a>
		<ul style="width:310px;">
		<li><a href="firstdegree.php">B-Tech Degree Regular Programs</a></li>
		<li><a href="course_blet.php">B-Tech Degree Lateral Entry Programs</a></li>
		<li><a href="mtecprogram.php">M-Tech Degree Programs</a></li>
		<li><a href="phdadmission.php">PHD Admissions</a></li>
		<li><a href="http://iraa.cusat.ac.in/" target="_blank">IRAA Admissions</a></li>
		</ul>
		</li>
		<li><a href="#">Placements</a>
		<ul style="width:220px">
		
		<li><a href="pages/placements/placemnt.php">Placement Cell</a></li>
		<li><a href="pages/placements/online.php">On-line Registration</a></li>
		<!--<li><a href="#">Contact Placement Cell</a></li> -->
		<li ><a href="pages/placements/employers.php" >Employers</a></li>		
		</ul>
		</li>
        <li><a href="#">Co-Curricular</a>
		<ul style="width:150px">
		<li><a href="pages/newgal.php">Gallery</a></li>
		<li><a href="pages/vipanchika.php">Arts</a></li>
        <li><a href="pages/sports.php">Sports</a></li>
		<li><a href="pages/nss.php">NSS</a></li>
		<li><a href="pages/tech.php">Tech Fest</a></li>
<li><a href="http://soe.cusat.ac.in/natureclub/" target="_blank">Nature club</a></li>
		</ul>
		</li>
        
        <li ><a href="./pages/teqip/teqip.php">TEQIP</a></li>
  
  <!--<li ><a href="#">Library</a>
  <ul style="width:140px">
      <li><a href="soelib.php">SOE Library</a></li>
	  <li><a href="http://library.cusat.ac.in/new/index.php" target="_blank">Univ. Library</a></li>
	  <li><a href="http://dyuthi.cusat.ac.in/" target="_blank">Dyuthi</a></li>
	  </ul>
	  </li> -->
  
		<li><a href="#">More Info</a>
			<ul style="width:150px">
	<li><a href="http://soe.cusat.ac.in/moodle/" target="_blank">E-Learning</a></li>
				<li><a href="soelib.php">SOE Library</a></li>
	  <li><a href="http://library.cusat.ac.in/new/index.php" target="_blank">Univ. Library</a></li>
	  <li><a href="http://dyuthi.cusat.ac.in/" target="_blank">Dyuthi</a></li>
				<li ><a href="soe_rsrch.php">Research</a></li>
				<li><a href="alum.php">Alumni</a></li>
				<li ><a href="pta.php">PTA</a></li>
				<li><a href="contact.php">Contact Us</a></li>
				<li class="last"><a href="./route.php">Reach Us</a></li>
		
			</ul>
		</li>
    </div>
    <br class="clear" />
  </div>
  
</div>
