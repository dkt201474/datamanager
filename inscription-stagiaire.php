<?php 
function chargerClass($class){
	require 'class/'.$class.'.class.php';
}
spl_autoload_register('chargerClass');
require 'global/header.php';
require 'global/connectionDb.php';

//-------------CREATION D'UN OBJET TUTEUR------------
$tuteur = new Manager($db);

//-------------CONTROLE ATTRIBUTS EXISTANT ET NON VIDES------------
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['age']) && isset($_POST['universite']) &&
	isset($_POST['email']) && isset($_POST['filiaire']) && isset($_POST['classe']) && isset($_POST['mdp']) &&
	isset($_POST['mdp2']) && isset($_POST['debutStage']) && isset($_POST['finStage']) && isset($_POST['inscription']) && 
	!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['age']) && !empty($_POST['universite']) &&
	!empty($_POST['email']) && !empty($_POST['filiaire']) && !empty($_POST['classe']) && !empty($_POST['mdp']) &&
	!empty($_POST['mdp2']) && !empty($_POST['debutStage']) && !empty($_POST['finStage'])) {
	
	//CHIFFREMENT DE MOT DE PASSE
	$_POST['mdp']=sha1($_POST['mdp']);
	$_POST['mdp2']=sha1($_POST['mdp2']);

	$stagiaire = new Stagiaire(array(
		'nom'=>htmlspecialchars($_POST['nom']),'prenom'=>htmlspecialchars($_POST['prenom']),
		'age'=>(int) htmlspecialchars($_POST['age']),'universite'=>htmlspecialchars($_POST['universite']),
		'classe'=>(int) htmlspecialchars($_POST['classe']),'debutStage'=>htmlspecialchars($_POST['debutStage']),
		'finStage'=>htmlspecialchars($_POST['finStage']),'mdp'=>htmlspecialchars($_POST['mdp']),
		'mdp2'=>htmlspecialchars($_POST['mdp2']),'email'=>htmlspecialchars($_POST['email']),
		'filiaire'=>htmlspecialchars($_POST['filiaire'])
		)); 

//-------------CONTROLE MOT DE PASSE CORRESPONDANT------------
if ($stagiaire->mdp() != $_POST['mdp2']) {
	echo "Votre mot de passe doit être le même pour le champs de confirmation de mot de passe";
	unset($stagiaire);
}

//-------------CONTROLE UNICITE EMAIL------------
elseif ($tuteur->exist($stagiaire)) {
	echo "<strong style=\"color:red\">L'adresse email que vous aviez saisi existe déjà saisissez un autre ! </strong>";
	unset($stagiaire);
}
else{
	$tuteur->addStagiaire($stagiaire);
	header('location:index.php?inscription=reussi');
}
}
?>

<title>Inscription</title>
<style>
body{
	background:#eee;
}
</style>
</head>
<body>
	<div class="w100  large-w100 medium-w100 small-w100 tiny-w100 bgd-green-light color-w center inscription-title">
		<h1 class="w90 center">Créez un nouveau compte !</h1>
	</div>
	<form action="" method="post" class=" hvr-curl-top-right w100 txtcenter inscription-form">
		<div class="grid-3-small-1-tiny-1">
			<div class="flex-container">
				<input id="nom"  required type="text" name="nom" placeholder="Nom" 
				<?php if (isset($_POST['nom'])) {
					echo 'value="'.$_POST['nom'].'"';
				} ?>
				class="w90">
				<label for="nom" class="right"><i class="fa fa-user"></i></label>
			</div>

			<div class="flex-container">
				<input id="prenom"  required type="text" name="prenom" placeholder="Prenom"
				<?php if (isset($_POST['prenom'])) {
					echo 'value="'.$_POST['prenom'].'"';
				} ?> class="w90">
				<label for="prenom" class="right"><i class="fa fa-user"></i></label>
			</div>

			<div class="flex-container">
				<span class="w20">Age</span>
				<select name="age" id="" class="w70">
					<?php 
					for ($i=15; $i < 70 ; $i++) { 
						?>

						<option value="<?php echo $i ?>"> <?php echo $i.' '; ?> ans</option>
						<?php 
					} ?>
				</select>
			</div>
		</div>

		<div class="grid-3-small-1-tiny-1">
			<div class=" flex-container">
				<input id="universite" required <?php if (isset($_POST['universite'])) {
					echo 'value="'.$_POST['universite'].'"';
				} ?>  type="text" name="universite" placeholder="Université" class="w90">
				<label for="universite" class="right"><i class="fa fa-university"></i></label>
			</div>

			<div class=" flex-container">
				<input id="mail" required <?php if (isset($_POST['email'])) {
					echo 'value="'.$_POST['email'].'"';
				} ?>  name="email" type="email" placeholder="mail@gmail.com" class="w90">
				<label for="mail" class="right"><i class="fa fa-envelope"></i></label>
			</div>
			<div class=" flex-container">
				<input id="filiaire" <?php if (isset($_POST['filiaire'])) {
					echo 'value="'.$_POST['filiaire'].'"';
				} ?>  name="filiaire" type="text" placeholder="Filiaire" class="w90">
				<label for="filiaire" class="right"><i class="fa fa-list-alt"></i></label>
			</div>

		</div>

		<div class="grid-3-small-1-tiny-1">
			<div class=" flex-container">
				<span class="w20">Classe</span>
				<select name="classe" id="" class="w70">
					<option value="1">année 1</option>
					<option value="2">année 2</option>
					<option value="3">année 3</option>
					<option value="4">année 4</option>
					<option value="5">année 5</option>
				</select>
			</div>

			<div class=" flex-container">
				<input id="mdp" required  name="mdp" type="password" placeholder="Mot de passe" class="w90">
				<label for="mdp" class="right"><i class="fa fa-lock"></i></label>
			</div>
			<div class=" flex-container">
				<input id="mdp2"  required name="mdp2" type="password" placeholder="Ressaisir mot de passe" class="w90">
				<label for="mdp2" class="right"><i class="fa fa-lock"></i></label>
			</div>

		</div>

		<div class="w100 left flex-container ">
			<span class="w20 mts ">Période de stage : </span> <input type="text"  required <?php if (isset($_POST['debutStage'])) {
					echo 'value="'.$_POST['debutStage'].'"';
				} ?>  name="debutStage" data-beatpicker="true" 
			data-beatpicker-position="['*','*']" data-beatpicker-range="true" class="periodStage">
		</div>

		<div class="right w25 large-w25 medium-w60 small-w75 tiny-w100 mtm">
			<button class="xl btn-inscription" name="inscription" value="inscription">Créer le compte</button>
		</div>
		<p class="left w20"><span>Tous les champs sont obligatoires</span></p>
	</form>


</body>
<?php 	
require 'global/footer.php';

?>