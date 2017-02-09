<?php 
function chargerClass($class){
	require 'class/'.$class.'.class.php';
}
spl_autoload_register('chargerClass');
require 'global/header.php';
require 'global/connectionDb.php';
/*var_dump($_POST);*/
if (isset($_SESSION['email'])) {
	unset($_SESSION['id']);
	unset($_SESSION['nom']);
	unset($_SESSION['prenom']);
	unset($_SESSION['age']);
	unset($_SESSION['universite']);
	unset($_SESSION['classe']);
	unset($_SESSION['debutStage']);
	unset($_SESSION['finStage']);
	unset($_SESSION['statut']);
	unset($_SESSION['srcImgProfile']);
	unset($_SESSION['mdp']);
	unset($_SESSION['email']);
	unset($_SESSION['filiaire']);
	unset($_SESSION['noteStage']);
	unset($_GET['email']);
	header('location:index.php');
}
//-------------NOTIFICATION REUSSITE INSCRIPTION------------
if (isset($_GET['inscription'])) {
	echo "<strong style=\"color:white;\">FELICITATION !!! Votre inscription à été effectuée avec success. Veuillez à présent vous connecter ! </strong>";
}
//-------------CREATION D'UN OBJET TUTEUR------------
$manager = new Manager($db);

if (isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['typeConnexion'])) {
	$_POST['mdp'] = sha1($_POST['mdp']);
	if ($_POST['typeConnexion']=='stagiaire') {
		$stagiaire = new Stagiaire(array(
			'email'=>htmlspecialchars($_POST['email']),
			'mdp'=>htmlspecialchars($_POST['mdp'])
			)); 
		if (!$manager->exist($stagiaire)) {
			echo "<strong style=\"color:red;\">L'email que vous aviez entré est incorrect. Veuillez saisir un autre SVP !</strong>";
			unset($stagiaire);
		}
		elseif (!$manager->ValidPassStag($stagiaire)) {
			echo "<strong style=\"color:red;\">Le mot de passe entré est incorrect veuillez en saisir un autre !</strong>";
			unset($stagiaire);
		}
		else{
			$persoEmail = htmlspecialchars($_POST['email']);
			$persoPass = htmlspecialchars($_POST['mdp']);
			$url =  "location:/dataManager/espace-personnel-stagiaire.php?email=$persoEmail&mdp=$persoPass";
			header($url);
		}
	}

	if ($_POST['typeConnexion']=='tuteur') {
		$tuteur = new Tuteur(array(
			'email'=>htmlspecialchars($_POST['email']),
			'mdp'=>htmlspecialchars($_POST['mdp'])
			)); 
		if (!$manager->existTuteur($tuteur)) {
			echo "<strong style=\"color:red;\">L'email que vous aviez entré est incorrect. Veuillez saisir un autre SVP !</strong>";
			unset($tuteur);
		}
		elseif (!$manager->ValidPassTut($tuteur)) {
			echo "<strong style=\"color:red;\">Le mot de passe entré est incorrect veuillez en saisir un autre !</strong>";
			unset($tuteur);
		}
		else{
			$persoEmail = htmlspecialchars($_POST['email']);
			$persoPass = htmlspecialchars($_POST['mdp']);
			$url =  "location:/dataManager/espace-personnel-tuteur.php?email=$persoEmail&mdp=$persoPass";
			header($url);
		}
	}
	/*if ($tuteur->existTuteur()) {
		# code...
	}*/
}
?>

<title>Acceuil - Connexion</title>
<style>
body{
	background:#1BBC9B;
}
</style>
</head>
<body>

	<form action="" method="post" class="hvr-float-shadow center tiny-w100 large-w50 medium-w66  login-form  ">
		<p>
			<h1 class="w100 txtleft mls">Connectez vous ! </h1>
		</p>
		<p class="w100 txtcenter">
			<input required <?php if (isset($_POST['email'])) {
				echo 'value="'.$_POST['email'].'"';
			} ?> class="w90" type="email" name="email" class="" placeholder="mail@gmail.com"><i class="fa fa-user"></i>
		</p>

		<p class="w100 txtcenter">
			<input required class="w90" name="mdp" type="password" placeholder="Mot de passe"><i class="fa fa-lock"></i>
		</p>
		<p class="w100 txtcenter">
			<span>Se connecter en tant que : </span> 
			<select name="typeConnexion" id="">
				<option value="stagiaire">Stagiaire</option>
				<option value="tuteur" <?php  if (isset($_POST['typeConnexion'])&&$_POST['typeConnexion']=="tuteur") {
				echo "selected";
			} ?> >Tuteur</option>
			</select>
		</p>
		<p class="w100 txtcenter">
			<button class="xl connexion" name="connexion" value="connexion">Connexion</button>
		</p>
		<p class="w100 txtleft mls ">Pas encore inscrit ? <a href="inscription-stagiaire.php">Créer un nouveau compte</a> <span>( Réservé uniquement aux stagiaire )</span></p>

	</form>
</body>
<?php 	
require 'global/footer.php';
?>
