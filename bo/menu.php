<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Document sans nom</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">

function chargement() {
	preloadImages();
}

function preloadImages() {
     var i = 0;
     
     imageObj = new Image();
     
     images = new Array("images/crayon.png", "images/engrenage.png", "images/user.png", "images/mots.png");
     
     for (i=0; i<=3; i++) {
          imageObj.src=images[i];
     }
}

</script>
</head>

<body class="menu" onload="chargement();">
<div class="menu">
	<a href="ajouter_article.php" target="mainFrame"><div id="menu_ajouter_article" onmouseout="document.getElementById('image_crayon').src='images/crayon-hidden.png'" onmouseover="document.getElementById('image_crayon').src='images/crayon.png'"><img src="images/crayon-hidden.png" width="30" height="30" border="0" id="image_crayon" /><span>Ajouter un article</span></div></a>
    <a href="tableau_global.php" target="mainFrame" onmouseout="document.getElementById('image_engrenage').src='images/engrenage-hidden.png'" onmouseover="document.getElementById('image_engrenage').src='images/engrenage.png'"><div id="menu_tableau_global"><img src="images/engrenage-hidden.png" width="30" height="30" border="0" id="image_engrenage" /><span>Gestion des commentaires</span></div></a>
    <a href="liste_auteurs.php" target="mainFrame" onmouseout="document.getElementById('image_user').src='images/user-hidden.png'" onmouseover="document.getElementById('image_user').src='images/user.png'"><div id="menu_liste_auteurs"><img src="images/user-hidden.png" width="30" height="30" border="0" id="image_user" /><span>Utilisateurs list&eacute;s</span></div></a>
    <a href="liste_mots.php" target="mainFrame" onmouseout="document.getElementById('image_mots').src='images/mots-hidden.png'" onmouseover="document.getElementById('image_mots').src='images/mots.png'"><div id="menu_liste_mots"><img src="images/mots-hidden.png" width="30" height="30" border="0" id="image_mots" /><span>Mots list&eacute;s</span></div></a>
</div>
</body>
</html>
