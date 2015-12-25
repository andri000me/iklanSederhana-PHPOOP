<!DOCTYPE html>
<html>
<head>
	<title>&#187;&#187;Iklan UNPAM</title>
	<style>
	a {
	color:#003366;
	text-decoration:none;
}

a.current {
	color:#999999;
}

a:hover {
	text-decoration: underline;
}

body {
	font-family: verdana, tahoma, helvetica, arial, sans-serif;
	font-size: 100%;
	background-color:#FFFFFF;
	margin: 0em;
}

ul {
	font-size:90%;
	color:#666666;
	line-height: 1.5em;
	list-style: none;
}

h1 {
	color:#FF9933;
	font-weight:bold;
	padding-left: 0.5em;
	padding-top: 0.2em;
}

h2 {
	color:#FF9933;
	font-size:70%;
	font-weight:bold;
	border-bottom-style:solid; border-bottom-width:1px; border-bottom-color:#E5E5E5;
}

h3 {
	color:#FF9933;
	font-size:70%;
	font-weight:bold;
}

h3 a{
	color:#FF9933;
}

p {
	text-align:justify;
	color:#777777;
	font-size: 80%;
	line-height: 1.5em;
}

.orange {
	color: #FF9933;
}

.blue {
	color: #003366;
}

.green {
	color: #DFFFBE;
}
.putih{color: white; font-weight: bold; font-size: 14px}

.gray {
	font-weight:normal;
	font-size:70%;
	color: #F0F0F0;
}

/* ----- Superior Bar -----  */
div.supBar {
	vertical-align: middle;
}

div.search {
	width: 35em;
	float: right;
	clear: right;
	color:#003366;
	font-size: 70%;
	margin: 0em 1em 0em 1em;
}

.searchbox {
	color:#666666;
	background-color:#F8F8F8;
	border-style:solid; border-width:1px; border-color:#F0F0F0;
}

/* ----- Menu -----*/
div.menu {
	width: auto;
	vertical-align: middle;
	font-size:80%;
	background-color:#003366;
	padding: 1em 25em 1em 1em;
	border-style:solid; border-width:1px; border-color:#E5E5E5;
	text-align: center;
	color:#FFFFFF;
}

div.menu a{
	color:#FFFFFF;
}
/*a:visited{color: #7cb500}*/
a:hover{font-weight: bold; text-decoration: none; font-size: 15px}

/* ----- right Info - News -----*/

div.newsContainer{
	float: left;
	clear: left;
	width: 12em;
	margin: 0.5em 0.5em 0em 0.5em;
}

div.news1{
	width: 14em;
	background-color:#F8F8F8;
	border-style:solid; border-width:1px; border-color:#F0F0F0;
	margin-bottom: 0.5em;
	padding: 0.5em 0.5em 0.5em 0.5em;	
}

div.news2{
	width: 12em;
	background-color:#DFFFBE;
	border-style:solid; border-width:1px; border-color:#F0F0F0;
	margin-bottom: 0.5em;
	padding: 0.5em 0.5em 0.5em 0.5em;
}

/* ----- main Container ----- */
div.mainContainer{
	margin: 0em 0em 2em 16em;
}

div.rightInfo{
	float: right;
	clear: right;
	width: 12em;
	background-color:#FFFFFF;
	border-style:solid; border-width:1px; border-color:#FFF3CE;
	
	margin: 0.5em 0.5em 0.5em 0em;
	padding: 0.5em 0.5em 0.5em 0.5em;
}

div.rightInfo h2{
	color:#FFFFFF;
	background-color:#FF9933;
	border-style:solid; border-width:1px; border-color:#FF9933;
}

div.mainInfo{
	width: auto;
	border-style:solid; border-width:1px; border-color:#F0F0F0;
	
	margin: 0.5em 14.5em 0.5em 0.5em;
	padding: 0.5em 0.5em 0.5em 0.5em;
}

div.mainInfo h3 a{
	color:#003366;
}

div.image {
	float:left;
	width: 80px;
	height: 80px;
	margin-right:0.5em;
	margin-bottom:0.5em;
	background-color:#F0F0F0;
	border-style:dotted; border-width:1px; border-color:#F0F0F0;
}

div.image h3{
	color:#CCCCCC;
}

/* ----- sub Compainer ----- */
div.subContainer{
	clear:both;
	width: auto;
	font-size:80%;
	background-color:#F8F8F8;
	border-style:solid; border-width:1px; border-color:#E5E5E5;
	margin: 0em 0em 0em 0em;
	padding: 0em 0.5em 1em 0.5em;
}

div.copyright {
	color: #999999;
	text-align:right;
	margin-left:20em;
	margin-right:0.5em;
	font-size:80%;
}

div.copyright a{
	color:#999999;
}

div.subLinks{
	float:left;
	clear:left;
	color:#777777;
	margin-left:2em;
}

div.subLinks a{
	color:#777777;
}

	</style>
</head>
<body>
	<div class="search">
		{CARI}
	</div>

	<div class="supBar">
		<h1>Ikl<span class="blue">an UN</span>PAM <span class="gray">| Tugas Besar</span></h1>		
	</div>

	<div class="menu">
		{MENU}
	</div>

	<div class="newsContainer">
		<div class="news1">{SISI1}</div>
	</div>

	<div class="mainContainer">

	<div class="rightInfo">
		
		<h2>{SEKARANG}</h2>			
		{SISI2}		
		 	
	</div><!-- End rightInfo-->
	
	<div class="mainInfo">
		
		<h2>{JUDUL} </h2>
		{UTAMA}			
	
	</div><!-- End mainInfo-->	
		
</div> <!-- End mainContainer-->


<div class="subContainer"><br />
  <div class="subLinks">
		<a href="index.php"> Company</a> |
		<a href="index.php"> Products</a> |
		<a href="index.php"> Services</a> |
		<a href="index.php"> Partners</a> |
		<a href="index.php"> Clients</a> |
		<a href="index.php"> Contact</a> 
	</div><!-- End subLinks-->
	
	<div class="copyright">
		Copyright &copy; <a href="http://inshomania.blogspot.com">ELANG SURYA</a>
	</div>
	<!-- End copyright-->
	
</div> <!-- End subContainer-->


</body>
</html>