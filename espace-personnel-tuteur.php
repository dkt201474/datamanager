	<?php 
	session_start();

	function chargerClass($class){
		require 'class/'.$class.'.class.php';
	}
	spl_autoload_register('chargerClass');
	require 'global/header.php';
	require 'global/connectionDb.php';

//-------------CREATION D'UN MANAGER------------
	$manager = new Manager($db);
	if (!isset($_GET['email']) || !isset($_GET['mdp'])) {
		header('location:/dataManager/');
	}

//-------------CREATION D'UN TUTEUR------------
	elseif (isset($_GET['email']) && isset($_GET['mdp'])){
		$tuteur = $manager->getTuteur(htmlspecialchars($_GET['email']));

//-----------UPLOAD D'UNE IMAGE--------------
		if (!empty($_POST['Editer'])) {
			$manager->uploadImg('srcImgProfile');
			$nomFichier = $manager->uploadImg('srcImgProfile');
			$manager->addImgSrcTut($nomFichier,$_SESSION['email']);

			/*$tuteur->setSrcImgProfile($nomFichier);*/
			$_SESSION['srcImgProfile'] = 'img/profiles/'.$tuteur->srcImgProfile();
		}

//-----------AJOUT D'UNE TACHE--------------
		if (isset($_POST['sujet']) && isset($_POST['objet'])&& isset($_POST['tache-description']) && 
			isset($_POST['email'])&& isset($_POST['date_envoi'])&& isset($_POST['heure_envoi'])) {
			if ($manager->exist($_POST['sujet'])) {
				$manager->addTaf($_POST['objet'],$_POST['heure_envoi'],$_POST['date_envoi'],$_POST['email'],$_POST['sujet'],$_POST['tache-description']);
			}
			else{
				$msgError = "L'email du stagiaire entré ne correspond pas !";
			}
		}
//------------SELECTIONNER TAF-----------------
		$taf = $manager->getTafTut($_GET['email']);
		/*var_dump($taf);*/
// INITIALISATION DES SESSIONS
		if (isset($tuteur)) {
		$_SESSION['id']=$tuteur->id();
		$_SESSION['nom']=$tuteur->nom();
		$_SESSION['prenom']=$tuteur->prenom();
		$_SESSION['age']=$tuteur->age();
		$_SESSION['profession']=$tuteur->profession();
		$_SESSION['srcImgProfile']='img/profiles/'.$tuteur->srcImgProfile();
		$_SESSION['mdp']=$tuteur->mdp();
		$_SESSION['email']=$tuteur->email();
	}

//AFFICHAGE DE SUGGESTIONS
		$suggestions = $manager->getSuggestion();
	

// -------------------------- RECHERCHE DE STAGIAIRES --------------------------
	if (isset($_POST['search-stag'])) {
		$result = $manager->exist($_POST['search-stag']);
		if ($result) {
			$searchContent = $manager->getStagiaire($_POST['search-stag']);
			//var_dump($searchContent);
		}
		else{
			$noMatch = "Votre recherche n'a pas abouti ! Entrer un email existant";
		}
		//var_dump($result);
	}


	//var_dump($tuteur);
	//var_dump($_POST);
	/*var_dump($_FILES);*/

// -------------------------- RECHERCHE DE MEMOIRE --------------------------
	if (isset($_POST['search-memoire']) && isset($_POST['auteur'])) {
		$resultMemoire = $manager->getMemoire($_POST['auteur']);
		if (!empty($resultMemoire)) {
			//var_dump($resultMemoire[0]['theme']);
		}
		else{
			$noMatchMemoire = "L'email entrée ne correspond pas !";
		}
	}
// -------------------------- RECHERCHE DE PERMISSION --------------------------
		$permissions = $manager->getAllPermissions();
		//var_dump($permissions);
}

// -------------------------- UPLOAD PERMISSIONS --------------------------
	if (isset($_POST['acceptPermission'])) {
		$_POST['acceptPermission'] = (int) $_POST['acceptPermission'];
		$manager->ValidPermission($_POST['acceptPermission']);
	}


//-------------CONTROLE ATTRIBUTS EXISTANT ET NON VIDES------------
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['age'])  &&
	isset($_POST['email'])  && isset($_POST['mdp']) &&
	isset($_POST['mdp2']) && isset($_POST['inscription']) && 
	!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['age'])  &&
	!empty($_POST['email']) && !empty($_POST['filiaire']) && !empty($_POST['mdp']) &&
	!empty($_POST['mdp2']) ) {
	
	//CHIFFREMENT DE MOT DE PASSE
	$_POST['mdp']=sha1($_POST['mdp']);
	$_POST['mdp2']=sha1($_POST['mdp2']);

	$tuteur = new Tuteur(array(
		'nom'=>htmlspecialchars($_POST['nom']),'prenom'=>htmlspecialchars($_POST['prenom']),
		'age'=>(int) htmlspecialchars($_POST['age']),
		'profession'=> htmlspecialchars($_POST['filiaire']),
		'mdp'=>htmlspecialchars($_POST['mdp']),
		'mdp2'=>htmlspecialchars($_POST['mdp2']),'email'=>htmlspecialchars($_POST['email']),
		'filiaire'=>htmlspecialchars($_POST['filiaire'])
		)); 

//-------------CONTROLE MOT DE PASSE CORRESPONDANT------------
if ($tuteur->mdp() != $_POST['mdp2']) {
	echo "Votre mot de passe doit être le même pour le champs de confirmation de mot de passe";
	unset($tuteur);
}

//-------------CONTROLE UNICITE EMAIL------------
elseif ($manager->exist($tuteur)) {
	echo "<strong style=\"color:red\">L'adresse email que vous aviez saisi existe déjà saisissez un autre ! </strong>";
	unset($tuteur);
}
else{
	var_dump($tuteur);
	$manager->addTuteur($tuteur);
	header('location:index.php?inscription=reussi');
}


}






	?>
	<title>Espace Tuteur</title>
	<style>
		body{
			background:#eee;
		}
	</style>
</head>
<body>
	<div class="espace-perso-stag">
		<div class="top-menu flex-container plm prm pts pbs bgd-green-light">
			<div class="right large-w40 medium-w60 small-w100 tiny-w100">
				<ul class="unstyled flex-container">
					<li class="li-l1 mrm mesSuggession">
						<a href="#suggession" >
							<i class="large-i fa fa-dropbox" title="Boite de suggestion"></i><span class="infos"><?php echo $manager->countSuggestion(); ?></span>
						</a>

					</li>
					<li class="li-l1 mrm inboxLink">
						<a href="" class=""><i class="large-i fa fa-envelope-o" title="Messages reçu"></i>
							<span class="infos"><?php echo $manager->countMessage($_SESSION['email']); ?></span></a>

						</li>
						<li class="li-l1 flex-container">
							<a href="">
								<img src=<?php if (!empty($_SESSION['srcImgProfile']) && $_SESSION['srcImgProfile'] != 'img/profiles/') {
									if (isset($nomFichier)) {
										echo '"'.'img/profiles/'.$nomFichier.'"';
									}

									echo '"'.$_SESSION['srcImgProfile'].'"';
								}
								else{
									echo 'img/profiles/profil.png';
								} ?> alt="Photo de profil"> <?php echo $_SESSION['prenom'].' '.$_SESSION['nom'];?>
								<span class="age ">( <?php echo $_SESSION['age'];?> <i class="fa fa-heart"></i> )</span>
								<i class="small-i fa fa-chevron-down"></i>
							</a>
							<ul class="unstyled">
								<li class="">Mon profil</li>
								<li class="profilLink"><a href=""><i class="fa fa-user color-gray-light"></i> Mon profil</a></li>
								<li class=""><a href="index.php?deconnexion=reussi"><i class="fa fa-power-off"></i> Déconnexion</a></li>
							</ul> 
						</li>
					</ul>
				</div>
			</div>

			<div class="side-left bgd-green-light side-left-large side-left-medium side-left-small side-left-tiny">
				<ul class="unstyled">
					<li class=" taches">
						<a class="mrs">Tâches <i class="fa fa-tasks fr"></i></a>
					</li>
					<li class="tuteurs">
						<a>Stagiaires<i class="fa fa-user fr"></i></a>
					</li>
					<li class="memoire">
						<a >Mémoire<i class="fa fa-book fr"></i></a>
					</li>
					<li class="permissions">
						<a >Permissions<i class="fa fa-star fr"></i></a>
					</li>
					<li class="ficheDePresenceLink">
						<a >Tuteur<i class="fa fa-plus fr"></i></a>
					</li>
				</ul>
			</div>

			<div class="side-right ">
				<div class="" id="taches">
					<h2 class="">Tâches des stagiaires</h2>
				<?php if (isset($msgError)) {
							echo '<strong style="color:red">'.$msgError.'</strong>';
						} ?>
					<div class="grid-2-small-1-tiny-1">
						<?php 
						if (!empty($taf)) {
						foreach ($taf as $unTaf) { ?>
						<article class="flex-container art1">
							<strong class="tache-title w60 fl bgd-green-light"><?php echo $unTaf['titre']; ?></strong>
							<a class="w30 fr txtcenter bgd-gray-light" style=<?php $unTaf['etat']=(int) $unTaf['etat'];
							if ($unTaf['etat']==0) {
								echo '"background-color:#ccc"';
							} else{echo '"background-color:#24B724"';}?>><?php $unTaf['etat']=(int) $unTaf['etat'];
							if ($unTaf['etat']==0) {
								echo "Non résolu";
							} else{echo "Résolu";}?></a>
							<div class="clear  tache-inner">
								<p>Du : <strong><?php echo $unTaf['dateDebut']; ?> à <?php echo $unTaf['heureDebut']; ?></strong> <br>
									au : <strong><?php if (isset($unTaf['dateFin'])) {
										echo $unTaf['dateFin'];
									} ?> à <?php if (isset($unTaf['heureFin'])) {
										echo $unTaf['heureFin'];
									} ?></strong><br>
									confié à <strong class="mls" style="color:orange"><?php echo $unTaf['sujet']; ?></strong><br>
									par <strong class="mls" style="color:orange"><?php echo $unTaf['auteur']; ?></strong></p>
									<h4>Description <a href=""><i class="fa fa-plus small-i"></i></a></h4>
									<div class="w100 tache-content more-content"><?php echo $unTaf['description']; ?></div>
								</div>
							</article>
							<?php } }?>



						</div>

						<h2>Confier une tâche</h2>
						<form action="" method="post">
							<input type="email" name="sujet" required class="w90 mbs left flex-container sujet" placeholder="Email du stagiaire">
							<input type="text" name="objet" required class="w90 left flex-container titre" placeholder="Titre...">
							<textarea name="tache-description" class="w90 " required class="flex-container left tache-description" id="tache-description" placeholder="Description..." cols="30" rows="10"></textarea>
							<input type="hidden" name="email" value=<?php echo '"'.$_SESSION['email'].'"'; ?>>
							<input type="hidden" name="date_envoi" value=<?php echo '"'.date('d/m/Y').'"'; ?>>
							<input type="hidden" name="heure_envoi" value=<?php echo '"'.date('G:i:s').'"'; ?>>
							<button class="mbm plm prm pts pbs  tache-btn annule-ml">Envoyer<i class="mls fa fa-send "></i></button>
						</form>

					</div>
					<div class="" id="tuteurs">
						<h2>Rechercher un stagiaire</h2>
						<form action="" method="post" class="flex-container">
							<input type="search" name="search-stag" required placeholder="Entrer l'email du stagiaire" class="w90 input-search fl"><button class="fr"><i class=" fa fa-search  large-i"></i></button>
						</form>
						<?php if (isset($_POST['search-stag']) && isset($noMatch)) {
							echo '<p class="alert-success">Aucune correspondance ! </p>';
						} elseif (isset($_POST['search-stag']) && !empty($searchContent)) {
							echo '<p class="alert-success">Une correspondance ! </p>';
						} ?>
							<?php if (!empty($searchContent)) { ?>
						
						<div class="search-result mtl">
							<div class="flex-container">
								<div class="side-left-l mrl w100p">
								<h3>Nom :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php  echo $searchContent->nom();?></span> </h3>
								<h3>Prenom :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->prenom();?></span> </h3>
								<h3>Age :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->age(); ?></span> </h3>
								<h3>Debut de stage :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->debutStage();?> jour(s)</span>  </h3>
								<h3>Fin de stage :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->finStage();?> jour(s)</span>  </h3>
								</div>

								<div class=" mll">
								<h3>Université :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->universite();?></span> </h3>
								<h3>Niveau :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->classe();?></span> </h3>
								<h3>Filière :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->filiaire();?></span> </h3>
								<h3>Note de stage :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->noteStage();?></span>  </h3>
								</div>
							</div>
						</div> <?php } ?>
					</div>
					<div class="" id="memoire">
						<h2>Rechercher un mémoire</h2>
										<form action="" method="post" class="flex-container">
											<input type="email" placeholder="Entrer l'email de l'auteur" name="auteur" required class="w90 input-search fl"><button class="fr " required name="search-memoire" value="search"><i class=" fa fa-search  large-i"></i></button>
										</form>
										<?php if (isset($_POST['search-memoire']) && isset($noMatchMemoire)) {
							echo '<p class="alert-success">Aucune correspondance ! </p>';
						} elseif (isset($_POST['search-memoire']) && !empty($resultMemoire)) {
							echo '<p class="alert-success">Une correspondance ! </p>';
						?>
					
										<div class="search-result">
											<div class="flex-container w90 center ">
												<a href="" ><h4 class="left mrm pts pbs"><?php echo $resultMemoire[0]['theme']; ?></h4></a>
													<a href=<?php echo '"'.$resultMemoire[0]['adrMem'].'"'; ?> class="mtm">Télécharger<i class="mls fa fa-download"></i></a href=<?php echo $resultMemoire[0]['theme']; ?>>
											</div>
										</div> <?php }  ?>
										</div>
					<div class="" id="permissions">
						<h2>Liste de mes permissions</h2>
										<?php 
										if (isset($permissions)) {
											foreach ($permissions as $unPermission) { ?>
											<div class="permission-style flex-container mbm">
												<div class="w90"> <em style="color:green; font-size:1.2em; font-weight:bold; margin-right:5px;">
													<i class="fa fa-star mrs"></i> <?php echo $unPermission['objet']; ?> </em> demandé par 
													<strong style="color:green;"><?php echo $unPermission['email']; ?></strong> du 
													<strong style="color:maroon;"><?php echo $unPermission['dateDepart']; ?></strong> au <strong style="color:maroon;"><?php echo $unPermission['dateRetour']; ?></strong></div> 
													<div class="w10 txtright " style="color:orange"><?php $unPermission['statut'] = (int) $unPermission['statut']; 
														switch ($unPermission['statut']) {
															case '0':
															echo "<form action='' method='post'> 
																	<button name=\"acceptPermission\" 
																	value=\"".$unPermission['id']."\">en cours</button>
																</form>";
															break;
															case '1':
															echo "<form action='' method='post'> 
																	<button name=\"acceptPermission\" 
																	value=\"".$unPermission['id']."\">Refusé</button>
																</form>";
															break;

															case '2':
															echo "<form action='' method='post'> 
																	<button name=\"acceptPermission\" 
																	value=\"".$unPermission['id']."\">Accepté</button>
																</form>";
															break;
														}
														?></div>
													</div>

													<?php } }?>
								</div>
								<div class="" id="suggession">
									<h3>Liste des 10 dernières suggestions</h3>
									<div class="flex-container w90 ">
										<?php 
										foreach ($suggestions as $unSuggestion) {
											?>
											<strong class="mrs"> <?php echo $unSuggestion['prenom'].' '.$unSuggestion['nom']; ?> </strong>
											<span> Le  <?php echo $unSuggestion['dateEnv'].' à '.$unSuggestion['heureSug'][0].'H '.$unSuggestion['heureSug'][1].'min '.' '.$unSuggestion['heureSug'][2].'s '; ?></span>
											<p class="p-style w100 mbm"><?php echo $unSuggestion['description']; ?></p>
											<?php } ?>
										</div>

									</div>

									<div id="inbox">
										<h2 class="">05 derniers messages reçus</h2>
										<div class="w100">
											<?php 
											$messages = $manager->getMessages($_SESSION['email']);
												//$messages = $manager->getMessages('tut@tut');
											if (!empty($messages)) {

												$i=0;
												foreach ($messages as $unMessage) {
													$i++;

													?>
													<div class="mlm">
														<a href="" class="mrs inbox-link<?php echo $i;?>"><?php echo $unMessage['expediteur']; ?><i class="fa fa-eye"></i></a> <em>à 
														<?php echo $unMessage['heureSug'][0].'H '.$unMessage['heureSug'][1].'min '.' '.$unMessage['heureSug'][2].'s ' ?>, le 
														<?php $unMessage['dateEnv']=date('d-m-Y'); echo $unMessage['dateEnv']; ?></em>
														<p class="inbox-inner mls w90 mbm" id="inbox-inner<?php echo $i;?>"><?php echo $unMessage['description']; ?></em>
														</p>
													</div>
													<?php } 
												}
												else{
													echo '<strong style="color:red">Vous n\'avez reçu aucun message !</strong>';

												}?>

											</div>

											<h2 class="">Ecrire un message</h2>
											<?php 

											if (isset($_POST['email']) &&  isset($_POST['description'])) {
												if (!$manager->exist(htmlspecialchars($_POST['email']))) {
													echo '<strong style="color:red">L\'email entrée n\'existe pas !</strong>';
												}

												else{
													$manager->sendMail($_SESSION['email'], $_POST['email'], $_POST['description']);
													echo '<strong style="color:green">Votre message à été bien transmis !</strong>';
												}
											}
											?>
											<form action="#inbox" method="post">
												<input type="email" required name="email" placeholder="Email du destinataire..." class="flex-container left w75" />
												<textarea name="description" required class="flex-container left w75" id="" placeholder="Votre message..." cols="30" rows="10"></textarea>
												<button class="mbm plm prm pts pbs  annule-ml block-event ">Envoyer<i class="mls fa fa-send"></i></button>
											</form>

										</div>
										<div id="profil">
											<h2 class="">Mon profil</h2>
											<div  style="">
												<form action="" method="POST" enctype="multipart/form-data">	
													<input class="input-file-2" id="my-file" name="srcImgProfile" type="file">
													<label tabindex="0" for="my-file" class="input-file-trigger-2"><img class="pp" src=<?php if (!empty($_SESSION['srcImgProfile']) && $_SESSION['srcImgProfile'] != 'img/profiles/') {
														if (isset($nomFichier)) {
															echo '"'.'img/profiles/'.$nomFichier.'"';
														}

														echo '"'.$_SESSION['srcImgProfile'].'"';
													}
													else{
														echo 'img/profiles/profil.png';
													} ?> alt="" title="Modifier votre profil"></label>
													<p class="file-return clear"></p>
													<input type="submit" value="Valider" name="Editer">

												</form> 
											</div>
											<div class="flex-container">
												<div class="side-left-l mrl w100p">
													<h3>Nom :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php  echo $_SESSION['nom'];?></span> </h3>
													<h3>Prenom :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $_SESSION['prenom'];?></span> </h3>
													<h3>Age :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $_SESSION['age'];?></span> </h3>
												</div>

												<div class=" mll">
													<h3>Email :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $_SESSION['email'];?></span>  </h3>
													<h3>Profession :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $_SESSION['profession'];?></span>  </h3>
												</div>
											</div>
										</div>
										<div id="ficheDePresence">
											<h2 class="">Ajouter tuteur</h2>




				<div class="w100  large-w100 medium-w100 small-w100 tiny-w100 bgd-green-light color-w center inscription-title">
		<h1 class="w90 center">Créez un nouveau compte !</h1>
	</div>
	<form action="" method="post" class=" hvr-curl-top-right w100 txtcenter inscription-form">
		<div class="grid-2-small-1-tiny-1">
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

		<div class="grid-2-small-1-tiny-1">
			<div class=" flex-container">
				<input id="mail" required <?php if (isset($_POST['email'])) {
					echo 'value="'.$_POST['email'].'"';
				} ?>  name="email" type="email" placeholder="mail@gmail.com" class="w90">
				<label for="mail" class="right"><i class="fa fa-envelope"></i></label>
			</div>
			<div class=" flex-container">
				<input id="filiaire" <?php if (isset($_POST['filiaire'])) {
					echo 'value="'.$_POST['filiaire'].'"';
				} ?>  name="filiaire" type="text" placeholder="Profession" class="w90">
				<label for="filiaire" class="right"><i class="fa fa-list-alt"></i></label>
			</div>

		</div>

		<div class="grid-2-small-1-tiny-1">

			<div class=" flex-container">
				<input id="mdp" required  name="mdp" type="password" placeholder="Mot de passe" class="w90">
				<label for="mdp" class="right"><i class="fa fa-lock"></i></label>
			</div>
			<div class=" flex-container">
				<input id="mdp2"  required name="mdp2" type="password" placeholder="Ressaisir mot de passe" class="w90">
				<label for="mdp2" class="right"><i class="fa fa-lock"></i></label>
			</div>

		</div>

		<div class="right w25 large-w25 medium-w60 small-w75 tiny-w100 mtm">
			<button class="xl btn-inscription" name="inscription" value="inscription">Créer le compte</button>
		</div>
		<p class="left w20"><span>Tous les champs sont obligatoires</span></p>
	</form>












										</div>
									</div>

								</div>
							</body>
							<?php 	

							require 'global/footer.php';
							if (isset($_GET['deconnexion']) && $_GET['deconnexion']=="reussi") {
								unset($_SESSION['id']);
								unset($_SESSION['nom']);
								unset($_SESSION['prenom']);
								unset($_SESSION['age']);
								unset($_SESSION['profession']);
								unset($_SESSION['srcImgProfile']);
								unset($_SESSION['mdp']);
								unset($_SESSION['email']);
								header('location:index.php');
							}
							?>