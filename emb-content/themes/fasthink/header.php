<!DOCTYPE html>
<html lang="<?php echo $content->lang ?: $runtime->lang ?: $config->site_language ?: 'en' ?>">
<head>
<meta content='text/html;charset=UTF-8' http-equiv='Content-Type'/>
<meta content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0' name='viewport'/>
<meta content='general' name='rating'/>
<title><?php echo htmlspecialchars($content->document_title ?: $content->title ?: $config->site_name ?: 'Embuh Blog') ?></title>
<?php $app->printHeaderMeta() ?>
<?php if($config->site_favicon): ?>
<link rel="shortcut icon" href="<?php echo $config->site_favicon ?>">
<?php endif ?>
<link href='//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' rel='stylesheet'/>
<link href='//fonts.googleapis.com/css?family=Open+Sans:light,lightitalic,regular,regularitalic,600,600italic,bold,bolditalic,800,800italic' rel='stylesheet' type='text/css'/>
<script src='//code.jquery.com/jquery-2.0.3.min.js'></script>
<style id='page-skin-1' type='text/css'><!--
/*
Name     :  Fasthink Blogspot Version (Premium)
Designer :  Kentooz
Published:  www.templatoid.com
Release  :  Januari 2015
*/
body{
background: #eee;
font-family: "Open Sans",sans-serif;
font-size: 14px;
font-style: normal;
color: #222;
}
a:link, a:visited {color: #0087ff;text-decoration:none;}
a:hover{color:#0087ff;}
a img { border-width:0; }
.left{float:left}
.right{float:right}
#wrapper{
background: #fff;
margin: 20px auto 40px auto;
width: 100%;
max-width: 760px;
border: 1px solid #ddd;
}
#branding {width:945px; }
P.title {
width:600px;
font-size:200%;
margin:10px 0 0;
padding:0
}
P.title a{
width: 600px;
font-weight: bold;
padding: 0;
color: #333;
}
/* -- MAIN SET -- */
#main-wrapper {
width: 569px;
float: left;
padding: 0;
word-wrap: break-word;
overflow: hidden;
}
#main {padding: 0 15px;}
.main .Blog { border-bottom-width: 0;}
.clear {clear: both;}
.post-thumbnail {
width: 120px;
/* height: 90px; */
float:left;
margin:0px 10px 0px 0px;
}
.main .widget {
padding-left:25px;
margin:0 0 0.2em;
padding:0 0 0.2em;
}
.date-header {display: none !important;}
.post {
margin: 0 0 .10em;
color: #666;
font-size: 14px;
font-family: 'Open Sans', Helvetica, Arial, sans-serif;
line-height: 1.625;
}
.post h1 {
font-size: 26px;
font-weight: 600;
line-height: 1.3;
margin-bottom: 4px;
margin-top: 0;
color: #2b2b2b;
cursor: pointer;
}
.post h2 {font-size: 14px;font-weight: 600;}
.post h2 a:link,.post h2 a:visited{}
.post h2 a:hover{}
.post-body {
margin: 0;
line-height: 22px;
text-align:justify
}
.post-body img, .post-body video, .post-body object {
max-width: 500px;
width: 85%;
height: auto !important;
}
.post-body blockquote { line-height:1.3em; }
.post-body a{
color:#65BDDF;
text-decoration:none
}
.post-body h2{
font-size: 120%;
}
.post blockquote{
background: white url(//4.bp.blogspot.com/-7N1CDVZhMsI/Uh56NUR6jgI/AAAAAAAAAEA/jVOWvhc1UQE/s1600/blockquote.jpg)bottom repeat-x;
margin: 20px;
padding: 10px;
border: 1px solid #CFCFCF;
border-top: 2px solid #235AD9;
line-height: 2em;
}
.section {
margin: 0;
}
/* Tombol Share by templatoid.com */
#share-button-templatoid {
margin: 0 0 8px;
padding: 0;
overflow: hidden;
font-size: 14px;
}
#share-button-templatoid p {
font-size: 100%;
float: left;
background: #1abc9c;
display: block;
padding: 6px 15px;
margin: 2px;
color: #fff;
border-radius: 5px;
}
#share-button-templatoid a {
position: relative;
float: left;
display: block;
color: #fafafa;
padding: 6px 15px;
margin: 2px;
border-radius: 5px;
-webkit-transition: all .5s ease-in-out;
-moz-transition: all .5s ease-in-out;
-ms-transition: all .5s ease-in-out;
-o-transition: all .5s ease-in-out;
transition: all .5s ease-in-out;
}
#share-button-templatoid a:hover {
background: #ddd !important;
color: #0087ff;
-webkit-transition: all .5s ease-in-out;
-moz-transition: all .5s ease-in-out;
-ms-transition: all .5s ease-in-out;
-o-transition: all .5s ease-in-out;
transition: all .5s ease-in-out;
}
.post table{width:100%;text-align:left}
.post tr{width:100%}
.post td{background: #FAFAFA; border:1px solid #fff; padding:5px 10px;}
.post td.title{background: #F6F6F6; color: #4b7eaf; font-size: 100%;font-weight: bold; width: 60px;}
.post td.detail{background: #FAFAFA; padding: 5px 10px}
.post td.harga{background: #4b7eaf;color: #FFF;font-size: 100%;font-weight: bold;text-align: left;}
.post td.listharga{color: #4b7eaf; font-weight: bold;text-align: left;}
.post td.img3{text-align: center; width: 33%;font-size: 10px;}
.post blockquote{border-left:2px solid #E3E3E3;margin:1em;padding:0 5px}
.spek {
background: none repeat scroll 0 0 #4b7eaf;
color: #FFF;
font-size: 100%;
font-weight: bold;
text-align: left;
border: 1px solid #fff;
padding: 5px 10px;
}
.post-timestamp {
margin-left: 0;
}
/* -- SIDEBAR SET -- */
#sidebar-wrapper {
width: 191px;
float: right;
word-wrap: break-word;
overflow: hidden;
margin-top: 10px;
}
#sidebar-wrapper h2, #sidebar-wrapper h3, #sidebar-wrapper h4 {
color: #2b2b2b;
font-weight: 600;
margin: 0 0 5px 0;
font-size: 20px;
line-height: 1.3;
padding: 0px 0px 10px;
word-wrap: break-word;
border-bottom: 3px solid #ddd;
}
#sidebar-wrapapper .widget ul{
list-style: none;
margin:10px;
padding:10px
}
#sidebarwrap .widget ul li{
line-height:1.5em;
text-align:left;
list-style: none;
margin:0;
padding:2px 0
}
#sidebar-wrapapper ul{
list-style: none;
margin:10px;
padding:10px
}
#sidebarwrap ul li{
line-height:1em;
text-align:left;
list-style-type:none;
margin:0;
padding:2px 0
}
.widget-content {margin: 0;overflow: hidden;}
.sidebar {
line-height: 1.5em;
padding: 0 15px;}
.sidebar ul{
padding: 0;
margin: 0;
}
.BlogArchive #ArchiveList ul li {text-indent: 0 !important;}
.sidebar ul li {
list-style-type: none;
position: relative;
display: block;
padding: 5px 0px;
border-bottom: 1px solid #ddd;
}
.sidebar ul li a{}
.sidebar ul li a:hover{}
.sidebar .widget{margin-bottom: 20px;}
.PopularPosts .item-title a {font-weight: bold;font-size: 12px;}
.PopularPosts .item-snippet {
font-size: 90%;
color: #555;
max-height: 40px;
overflow: hidden;
}
.PopularPosts .widget-content ul li {list-style-type: none;}
/* label */
.label-size-1,.label-size-2,.label-size-3,.label-size-4,.label-size-5 {
font-size:100%;
filter:alpha(100);
opacity:10
}
.cloud-label-widget-content{text-align:left}
.label-size{
display:block;
float:left;
border:1px solid #ccc;
margin:2px 1px;
}
.label-size a,.label-size span{
display:inline-block;
padding:5px 8px;
}
.label-size:hover{background:#eee;}
.label-count{
white-space:nowrap;
padding-right:6px;
margin-left:-3px;
}
.label-size{line-height:1.2
}
/* -- BREADCRUMBS SET -- */
.breadcrumbs {
padding: 8px 10px;
color: #aaa;
font-size: 12px;
background: #f8f8f8;
border: 1px solid #ddd;
margin: 10px 0;
}
.breadcrumbs a {
color: #aaa;
border-bottom: 1px dotted #aaa;
}
.breadcrumbs a:hover {
text-decoration: none;
color: #0087ff;
}
/* -- Header -- */
#header-wrapper {
width: 100%;
margin: 0px auto 0px auto;
padding-top: 15px;
padding-bottom: 15px;
}
.title, .title a {
color: #00ACED;
font-size: 30px;
line-height: 1.4em;
margin: 0;
text-transform: uppercase;
text-align: center;
font-weight: 500;
font-style: italic;
display: block;
}
.description {
margin:0;
text-align: center;
color: #00ACED;
}
/* -- POST INFO -- */
.post-info {
display:block;
color:#666;
line-height:1.6em;
font-size:13px;
overflow:hidden;
margin:5px 0;
padding: 0px 0px 10px;
margin-bottom: 15px;
border-bottom: 1px solid #ddd;
font-style: italic;
}
.post-info a {color:#aaa;}
.post-info-icon {float: left; margin-right: 30px;color: #aaa;font-size: 13px;}
/*-----Navigation ----*/
#menu{
height: 50px;
font-size: 95%;
padding: 0 15px;
border-top: 1px solid #ddd;
border-bottom: 1px solid #ddd;
color: #0087ff !important;
}
#menu li a{
color: #0087ff;
display: block;
line-height: 50px;
padding: 0 15px;
text-decoration: none;
font-size: 14px !important;
}
#menu li:hover{background: #ddd;}
#menu li:hover > a {color: #777;}
#menu ul, #menu li{
margin:0 auto;
padding:0 0;
list-style:none;
}
#menu ul{height:50px;}
#menu li {
float: left;
display: inline;
position: relative;
font: 0.9em "Open Sans",sans-serif;
}
#menu li ul {
background: #fff;
margin: 0 0;
width: 160px;
height: auto;
position: absolute;
top: 50px;
left: 0px;
z-index: -1;
display: none;
border-radius: 0 0 4px 4px;
box-shadow: 0 1px 2px rgba(0,0,0,0.2);
}
#menu li:hover > ul {z-index: 10;display: block;}
#menu li li {display:block;float:none;}
#menu li li:hover {background: #eee;}
#menu li li > a {
display: block;
padding: 0 10px;
margin: 0 0;
line-height: 20px;
text-decoration: none;
color: #333;
padding: 3px 20px;
}
#menu li li a:hover {color: #333;}
.caret {
display: inline-block;
width: 0;
height: 0;
margin-left: 2px;
vertical-align: middle;
border-top: 4px solid;
border-right: 4px solid transparent;
border-left: 4px solid transparent;
}
#menu li ul ul {left:100%;top:0px;}
#menu input{
display:none;
margin:7px;
padding:0;
width: 45px;
height: 35px;
opacity:0;
cursor:pointer;
}
#menu label{
font: 24px "Open Sans",sans-serif;
display: none;
width: 45px;
height: 35px;
line-height: 35px;
text-align: center;
border: 1px solid #ddd;
margin: 7px;
border-radius: 4px;
color: #555;
}
#menu ul.menus li{
display: block;
width: 100%;
font:normal 0.8em "Open Sans",sans-serif;
text-transform: none;
text-shadow: none;
}
#menu ul.menus{
background: #fff;
height: auto;
overflow: hidden;
position: absolute;
z-index: 99;
display: none;
}
#menu ul.menus a{color: #0087ff;line-height: 35px;}
#menu li:hover ul.menus{display:block}
#menu ul.menus a:hover{background: #ddd;color: #333;}
#menu li, #menu li:hover, #menu ul, #menu li ul, #menu li li, #menu li li:hover, #menu ul.menus a:hover, #menu li:hover > ul, #menu li a {
-webkit-transition: all .5s ease-in-out;
-moz-transition: all .5s ease-in-out;
-ms-transition: all .5s ease-in-out;
-o-transition: all .5s ease-in-out;
transition: all .5s ease-in-out;
}
/* -- Ads 728 -- */
.ads728-wrap, .search-f {
max-width: 728px;
overflow: hidden;
padding: 0;
width:100%;
margin: 15px auto 0;
}
.search-f {
margin: 8px auto 0;
}
.ads728, .search-f {
max-width: 728px;
width:100%;
}
.ads728 img{
max-width: 728px;
width:100%;
}
/* -- FOOTER SET -- */
.post-footer {line-height: 1.6em;}
.post-footer a {color: #0087ff;}
#footer-wrap {
margin: 0 auto;
width: 100%;
}
#copyright {
position: relative;
background: #f8f8f8;
border-top: 1px solid #ddd;
padding: 15px 15px;
margin-top: 17px;
}
#copyright a {color:#0087ff;}
#footer-socio {}
#footer-socio span{
font-size: 12px;
padding: 0px 5px;
line-height: 20px;
height: 20px;
width: 12px;
color: #666;
display: inline-block;
-webkit-transition: all .5s ease-in-out;
-moz-transition: all .5s ease-in-out;
-ms-transition: all .5s ease-in-out;
-o-transition: all .5s ease-in-out;
transition: all .5s ease-in-out;
}
.socio-twitter:before {content: "\f099";font-family: FontAwesome;}
.socio-facebook:before {content: "\f09a";font-family: FontAwesome;}
.socio-gplus:before {content: "\f0d5";font-family: FontAwesome;}
.socio-rss:before {content: "\f09e";font-family: FontAwesome;}
#footer-socio span:hover{
background: #666;
color: #fff;
-webkit-transition: all .5s ease-in-out;
-moz-transition: all .5s ease-in-out;
-ms-transition: all .5s ease-in-out;
-o-transition: all .5s ease-in-out;
transition: all .5s ease-in-out;
}
.footer-menu{text-align: center;color: #0087ff;margin-bottom: 12px;}
.footer-menu ul{
margin: 0;
padding: 0;
position: relative;
}
.footer-menu ul > li:after {
color: #009eda;
content: "/";
}
.footer-menu ul > li:last-child:after {
content: "";
}
.footer-menu li{
display: inline;
margin: 0px;
padding: 10px 0;
text-align: center;
position: relative;
}
.footer-menu a
{
line-height: 9px;
margin: 0px;
padding: 0px 10px 0px 5px;
text-decoration: none !important;
}
.feed-links{display:none;}
/* -- Back to top -- */
#totop {bottom: 0;display: none;position: fixed;right: 15px;bottom:10px;z-index: 999;}
#totop a {color: #666;display: block;font-weight: bold;line-height: 1em;padding: 10px;text-align: center;text-shadow: 0 1px rgba(255,255,255,0.8);background:#fff;-webkit-box-shadow: 0 1px 10px rgba(0,0,0,0.05);-moz-box-shadow: 0 1px 10px rgba(0,0,0,0.05);box-shadow: 0 1px 10px rgba(0,0,0,0.05);}
#totop-icon:before {content: "\f102";font-family: FontAwesome;}
.post-snippet{margin-top: 3px;}
.quickedit{display: none;}
.item-thumbnail{float: left;margin-right: 10px;}
/* -- RESPONSIVE -- */
@media screen and (max-width: 1024px) {
.post h1{font-size: 130%;}
}
@media screen and (max-width: 992px) {
}
@media screen and (min-width:801px){
}
@media screen and (max-width:800px){
#wrapper {width: 90%;}
img,video,object {max-width: 100%;}
#sidebar-wrapper{width:35%;}
#main-wrapper{width:65%;}
.post-thumbnail {width: 100px;}
.PopularPosts .item-snippet {font-size: 100%;}
#header-wrapper {text-align:center;}
}
@media only screen and (max-width:768px){
#sidebar-wrapper{width:100%;}
#main-wrapper{width:100%;}
.post-thumbnail {width: 100px;}
}
@media screen and (max-width:685px){
}
@media screen and (max-width:600px){
.post-info {display:none;}
.post-thumbnail {width: 120px;/*height: 90px;*/}
.status-msg-border{width:97%}
.post h2{font-size:100%;}
}
@media screen and (max-width:480px){
.comments .comments-content .user{line-height:2.8em;}
.post h2{font-size:100%;}
.post h1{font-size: 120%;}
body, .body-fauxcolumn-outer {font-size: 80%;}
}
@media screen and (max-width:380px){
.comments {display:none}
}
@media screen and (max-width:320px){
.terkait ul {padding: 0;list-style-type: none;}
.post blockquote {margin:5px;}
}
@media screen and (max-width:240px){
body, .body-fauxcolumn-outer {font-size: 70%;}
}
/* -- SEARCH -- */
#search-box{position:relative;width:98%;border: 1px solid #ddd; margin:auto;}
#search-form{height:33px;-moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px;background-color:#fff;overflow:hidden}
#search-text{font-size:14px;color:#ddd;border-width:0;background:transparent}
#search-box input[type="text"]{width:100%;padding: 9px 12px;color:#666;outline:none}
#search-button{position:absolute;top:0;right:0;height:32px;width:20px;margin-top:10px;font-size:14px;color:#fff;text-align:center;line-height:0;border-width:0;background:url(//4.bp.blogspot.com/-9cg5-K_jtuc/Uh5opT3mJvI/AAAAAAAAADc/5FnyCMV0L-8/s1600/search.png) no-repeat;cursor:pointer}
#search-text:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)}
/* -- SET FOR STATUS MESSAGE -- */
.status-msg-body{padding:10px 0 ; display:none}
.status-msg-wrap{display:none; font-size:14px; margin-left:1px;  width:100%; color:#666;  }
.status-msg-wrap a{color:orange !important;  }
.status-msg-bg{display:none; background:#ccc; position:relative; width:99%; padding:6px; z-index:1;-moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; border:2px #999 solid }
.status-msg-border{display:none; border:0; position:relative; width:99%}
/*-----Responsive Drop Down Menu ----*/
@media screen and (max-width: 800px){
#menu{position:relative}
#menu ul{width: 100%;background:#fff;position:absolute;top:100%;left:0;z-index:10;height:auto;display:none;}
#menu li:hover > ul {width: 100%;}
#menu li a {line-height: 35px;}
#menu li ul {top: 0px;position: relative;box-shadow: none;}
#menu ul.menus{width:100%;position:static;padding-left:20px}
#menu li{display:block;float:none;width:auto; font:normal 0.8em Arial;}
#menu input,#menu label{position:absolute;right:10px;display:block}
#menu input{z-index:4}
#menu input:checked + label{color: #555;background: #eee}
#menu input:hover + label {background: #eee}
#menu input:checked ~ ul{display:block;border-bottom: 1px solid #ddd;border-top: 1px solid #ddd;}
}
/* Misc/Utilities */
.pull-right{float: right;}p.disclaimer{margin-top: 0;font-size:0.8em;}h3.disclaimer{margin-top: 12px;margin-bottom: 0;font-size: 1em;}.yt-frame{width:100%!important;}@media screen and (max-width:960px){.yt-frame{max-height:90%}}@media screen and (max-width:768px){.yt-frame{max-height:75%}}@media screen and (max-width:600px){.yt-frame{max-height:60%}}@media screen and (max-width:480px){.yt-frame{height:auto!important;max-height:auto!important}};
-->
</style>
<style type='text/css'>
.post h2 {margin:0;}
.post{border-bottom: 1px solid #ddd;padding: 0;margin-top: 10px;}
.post-body {padding: 0 0 10px 0;text-align: left;}
.post-body img {margin: 0 5px 0 0;border: 1px solid rgb(207, 207, 207);padding: 2px;}
.breadcrumbs{margin: 10px;}
/* -- BLOGPAGER SET -- */
#blog-pager{
	clear:both;
	margin:10px 5px;
	padding: 7px;
	width: 98%;
	text-align: right;
}
.blog-pager {background: none;}
.displaypageNum a,.showpage a,.pagecurrent{
	font-size: 14px;
	padding: 8px 12px;
	color: #428bca;
	border-radius: 5px;
	-webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -ms-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
}
.displaypageNum a:hover,.showpage a:hover, .pagecurrent{
	background:#eee;
	text-decoration:none;
	color: #428bca;
	-webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -ms-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
}
#blog-pager .pagecurrent{
	color: #fff;
	background:#0087ff;
}
.showpageOf{display:none!important}
#blog-pager .pages{border:none;}
/* -- BLOGPAGER SET -- */
#blog-pager-newer-link{float:left;}
#blog-pager-older-link{float:right;}
#blog-pager{
	float:left;
    width: 100%;
    text-align: center;
    line-height: 2em;
    margin:10px 0px;
}
#blog-pager .active, #blog-pager a:link, #blog-pager a:visited{
    background: #0087ff;
    border-radius: 5px;
    color: #fff;
    font-size: 12px !important;
    padding: 10px 15px;
	-webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -ms-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
}
#blog-pager .active {
    border: #0087ff 1px solid;
	background: #fff;
	color: #0087ff;
}
#blog-pager a:hover{
	background: #ddd;
	color: #428bca;
	-webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -ms-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
}
.feed-links {clear: both; line-height: 2.5em;display:none;}
/*.post-thumbnail{display: none !important;}*/
#comments{line-height:1.4em;position:relative;}  #comments h3{font-size:16px;font-family:&#39;Open Sans&#39;,Helvetica,Arial,Sans-Serif;text-transform:uppercase;font-weight:normal;color:#333;width: 100%;position: relative;border-bottom: 2px solid #00ACED;padding: 10px 0;}.comment_avatar_wrap{width:42px;height:42px;border:1px solid #999;text-align:center;margin-bottom:20px}#comments .avatar-image-container{float:left;margin:0 10px 0 0;width:42px;height:42px;max-width:42px;max-height:42px;padding:0;margin-bottom:10px}#comments .avatar-image-container img{width:42px;height:42px;max-width:42px;max-height:42px;background:url(//2.bp.blogspot.com/-fjaZBtfvzac/UN1mw2tUamI/AAAAAAAADkc/XdKqt8hWZ6w/s1600/anon.jpg) no-repeat}.comment_name a{font-family:&#39;Open Sans&#39;,Helvetica,Arial,Sans-Serif;padding-bottom:10px;font-size:14px;text-decoration:none}.comment_admin .comment_name {font-family:&#39;Open Sans&#39;,Helvetica,Arial,Sans-Serif;padding-bottom:10px;font-size:14px;text-decoration:none}.comment_admin .comment_date {font-weight:normal;font-size:11px}.comment_name{font-family:&#39;Open Sans&#39;,Helvetica,Arial,Sans-Serif;padding-bottom:10px;font-size:14px;font-weight:normal;position:relative}.comment_service{margin-top:5px}.comment_date{color:#999;float:right;font-size:11px;font-weight:normal}.comment_date a{color:#a9a9a9;float:right;font-size:11px;font-weight:normal}.comment_date a:hover{color:#a9a9a9;text-decoration:none}.comment_body{margin-top:-65px;margin-left:65px;background:#fff;border-radius: 4px;padding:15px;color:#333}div.comment_inner.comment_admin{background:#ddd}.comment_body p{line-height:1.5em;margin:5px 0 0 0;color:#333;font-size:13px;word-wrap:break-word;padding-bottom:10px}.comment_inner{padding:15px;margin:5px 0 5px 0;background-color:#ccc}.comment_child .comment_wrap{padding-left:5%}.comment_reply{display:inline-block;margin-top:8px;margin-left:-5px;padding:1px 12px;color:#fff !important;text-align:center;text-decoration:none;background:#00ACED;font:11px/18px sans-serif;transition:background-color 1s ease-out 0s}.comment_reply:hover{text-decoration:none !important;;background:#ddd; color: #333}.unneeded-paging-control{display:none}#comment-editor{width:100% !important;background:#e1e3e6 url(&#39;//4.bp.blogspot.com/-TIf6ayZW9R4/UkxBo2beCQI/AAAAAAAAFsA/XR2DBWD3YG4/s1600/kangis-loader.gif&#39;) no-repeat 50% 30%;margin-bottom:20px;position:relative}.comment-form{max-width:100% !important}.comment_form a{text-decoration:none;text-transform:uppercase;font-weight:bold;font-family:Arial,Helvetica,Garuda,sans-serif;font-size:15px}.comment_form a:hover{text-decoration:underline}.comment_reply_form{padding:0 0 0 70px}.comment_reply_form .comment-form{width:99%}img.comment_emo{margin:0;padding:0;vertical-align:middle}.comment_emo_list{    display:none;    clear:both;    width:100%}.comment_emo_list .item{float:left;text-align:center;margin:10px 5px 0 0;height:40px;width:55px;color:#999}.comment_emo_list span{display:block;font-weight:normal;font-size:11px;letter-spacing:1px}.comment_youtube{max-width:100%!important;width:400px;height:225px;display:block;margin:auto}.comment_img{max-width:100%!important}.comment_header{width:50px}#respond{overflow:hidden;padding-left:10px;clear:both}.comment_avatar img{width:42px;height:42px;background:url(//2.bp.blogspot.com/-fjaZBtfvzac/UN1mw2tUamI/AAAAAAAADkc/XdKqt8hWZ6w/s1600/anon.jpg) no-repeat}.comment-delete img{float:right;margin-left:15px;margin-top:3px}.comment_author_flag{display:none}.comment_admin .comment_author_flag{display:inline;font-size:13px;font-weight:normal;padding:2px 6px;right:-23px;margin-top:-23px;color:#fff;text-transform:uppercase;position:absolute;width:36px;height:36px}iframe{border:none;overflow:hidden}.deleted-comment{background:#db6161 url(//4.bp.blogspot.com/-Yj5ewidrX5Q/UkrG9s3fS5I/AAAAAAAAFrQ/rhhaMJwHDoc/s1600/tempat-sampah.png) no-repeat 2% 50%;color:#efd4d4;line-height:22px;border:1px solid #c44d4d;padding:12px 15px 12px 45px;margin:5px 0;display:block}.comment-form p{padding:15px 15px 14px 15px;margin:5px 0 5px 0;color:#fff;font-size:13px;line-height:20px;position:relative}div.comment_avatar img[src=&#39;//img1.blogblog.com/img/openid16-rounded.gif&#39;]{content:url(//2.bp.blogspot.com/-8NurYzHIoWQ/Ujc_oLurXII/AAAAAAAAFek/XhAm-oLwg7Q/s45-c/gravatar.png)} div:target .comment_inner{background:#ddd;transition:all 15s ease-out}div:target .comment_child .comment_wrap .comment_inner{background:#404c5c}iframe{border:none;overflow:hidden}.center{ text-align:center}img.cm-prev{ max-width:400px; margin:10px auto; page-break-after:always; display:block; text-align:center !important}#commentss h3{font-size:18px;font-family:&#39;Open Sans&#39;,Helvetica,Arial,Sans-Serif;text-transform:uppercase;font-weight:normal;left:0;top:-53px;background:#3498db;color:#fff;width:85.2%;padding:14px 20px 14px 75px;position:absolute}#commentss h3:before{ content:&quot;\f0e6&quot;; font-family:fontAwesome; font-size:18px; font-style:normal; background-color:none;background:rgba(0,0,0,0.1); color:#fff;  top:0; left:0; padding:14px 20px; position:absolute} #commentss{line-height:1.4em;margin:60px 0 0 0;position:relative;background:#eee;padding:25px 20px 0 20px}
#related-posts{float:left;width:auto;}
#related-posts a{border-right: 1px dotted #f8f8f8;}
#related-posts a:hover{background: #eee;box-shadow: 0px -3px 0px #181818 inset;-webkit-transition: all .5s ease-in-out;-moz-transition: all .5s ease-in-out;-ms-transition: all .5s ease-in-out;-o-transition: all .5s ease-in-out;transition: all .5s ease-in-out;}
#related-posts h2{margin-bottom: 10px;padding-bottom: 10px;font-size: 16px;border-bottom: 1px solid #ddd;font-weight: 600;line-height: 1.3;font-family: &quot;Open Sans&quot;, helvetica;font-style: normal;color: #2b2b2b;}
#related-posts .related_img {width:159px;height:95px;}
#related-title {color:#0087ff;text-align:center;font-size:14px;width:159px; height: 75px;}
</style>
<?php if($app->route != 'post'): ?><style>.post-info{display:none;}</style><?php endif ?>

<?php echo $content->header ?>

</head>
<body class='loading'>
<div>
<div itemscope='' itemtype='http://data-vocabulary.org/Review'>
<div id='wrapper'>
<div id='header-wrapper'>
<div class='section' id='header'><div class='widget Header' data-version='1' id='Header1'>
<div id='header-inner'>
<div class='titlewrapper'>
<?php if($app->route == 'home'): ?>
	<h1 class="title"><a href="<?php echo $app->homepath ?>"><?php echo htmlspecialchars($config->site_name ?: 'Embuh Blog') ?></a></h1>
<?php else: ?>
	<h2 class="title"><a href="<?php echo $app->homepath ?>"><?php echo htmlspecialchars($config->site_name ?: 'Embuh Blog') ?></a></h2>
<?php endif ?>
</div>
<div class='descriptionwrapper'>
<p class='description'><span><?php echo htmlspecialchars($config->site_tagline) ?></span></p>
</div>
</div>
</div></div>
</div>
<nav id='menu'>
<?php include 'menu.php' ?>

</nav>
<div class='clear'></div>

<div class='ads728-wrap' id='ads728-wrap'>
<div class='ads728 no-items section' id='ads728'><?php echo $content->ads_728 ?></div>
<div class='clear'></div>
</div>
<div id='content-wrapper'>
<div id='main-wrapper'>
<div class='main section' id='main'><div class='widget Blog' data-version='1' id='Blog1'>

<?php foreach($widget->breadcrumb ?: [] as $name => $wdgt): ?>
	<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
<?php foreach($wdgt['content'] as $i => $item): ?>
<?php if(is_array($item)): ?>
		<span class="breadlabel" typeof="v:Breadcrumb"><a href="<?php echo $item['href'] ?>" rel="v:url" property="v:title"><?php echo htmlspecialchars($item['text']) ?></a> &rsaquo; </span>
<?php else: ?>
		<span class="breadlabel"><?php echo htmlspecialchars($item) ?></span>
<?php endif ?>
<?php endforeach ?>
</div>
<?php endforeach ?>
