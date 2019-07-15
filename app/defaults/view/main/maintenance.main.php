<style>
html,body{ height: 100%; }
body {
	background:#0000aa;
	background: linear-gradient(135deg, rgba(38,138,214,1) 0%,rgba(31,62,155,1) 100%);
	color:#ffffff;
	font-family: 'Share Tech Mono', monospace;
	font-size:20px;
	text-align:center;
	margin:10vw;
	overflow: hidden;
	text-shadow:2px 2px 0 rgba(0,0,0,.16)
}

.blink {
	animation:pulse 1.2s .1s infinite;
	transition:all 0.1s
}

@keyframes pulse{
	0%{ opacity:0.1 }
}

h2 {
	background:#fff;
	color:#246fc1;
	padding:2px 8px;
	width:fit-content;
	margin:20px auto;
	box-shadow:2px 2px 0 rgba(0,0,0,.16)
}

p {
	margin:30px 100px;
	text-align:left;
}

a,a:hover {
	color:inherit;
	text-decoration:none
}

.menu {
	margin-top:50px;
}
</style>
<body>
	<h2><?= $title; ?></h2>
	<b><?= $message; ?></b>
	<div class="menu"><a href="javascript:history.back()">< Go Back</a> <b class=blink>|</b></div>
</body>
