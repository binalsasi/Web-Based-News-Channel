<!DOCTYPE html>
<html>
<head>
<title><?php echo $pageTitle; ?></title>
<style>
body{
  font-family: "Helvetica", Helvetica, sans-serif;
  background-color: #fff;
}
#top_bar{
  padding:10px;
  position:absolute;
  top:0;
  left:0;
  right:0;
  background-color: #a1887f;
}
#back_button_div{
  float:left;
}
#logo_div{
  float:left;
  margin-top: 8px;
  margin-left: 10px;
  margin-right:10px;
  font-size: 1.3em;
  font-weight: bold;
}
#curPath_div{
  float:left;
  margin-top: 10px;
  margin-left: 10px;
  margin-right:10px;
}
#notify_button{
  float:left;
  margin-top: 8px;
  margin-left: 20%;
  margin-right:10px;
}
#login_detail_div{
  float:left;
}
#loggedinas_text{
  float:left;
  margin-top: 10px;
  margin-left: 10px;
  margin-right:10px;
}
#login_details{
  float:left;
}
#logout_button_div{
  float:left;
  margin-top: 8px;
  margin-left: 200px;
  margin-right:10px;
}
</style>
</head>
<body>
<div id='top_bar'>
<?php if(isset($backPath)){ ?>
	<div id='back_button_div'>
	</div>
<?php } ?>
<div id='logo_div'>
	Soe News Interfaces
</div>
<?php if(isset($curPath)){ ?>
<div id='curPath_div'>
<?php echo "$curPath"; ?>
</div>
<?php } ?>
<div id='notify_button_div'>
<button id='notify_button' onclick="alert('notified');">notify</button>
</div>
<div id='login_detail_div'>
<div id='loggedinas_text'>logged in as : </div>
<div id='login_details'>
<div id='uname'><?php echo $uname; ?></div>
<div id='level'><?php echo "&lt; $level &gt;"; ?></div>
</div>
<div id='logout_button_div'>
<button id='logout_button' onclick="alert('logged out');">Log Out</button>
</div>
</div>
</div>
