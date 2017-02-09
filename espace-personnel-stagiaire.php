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
	
//-------------CREATION D'UN STAGIAIRE------------
	if (!isset($_GET['email']) || !isset($_GET['mdp'])) {
		header('location:/dataManager/');
	}
	elseif (isset($_GET['email']) && isset($_GET['mdp'])){
		$stagiaire = $manager->getStagiaire(htmlspecialchars($_GET['email']));

//-----------UPLOAD D'UNE IMAGE--------------
		if (!empty($_POST['Editer'])) {
			$manager->uploadImg('srcImgProfile');
			$nomFichier = $manager->uploadImg('srcImgProfile');

			$manager->addImgSrc($nomFichier,$_SESSION['email']);

			/*$stagiaire->setSrcImgProfile($nomFichier);*/
			$_SESSION['srcImgProfile'] = 'img/profiles/'.$stagiaire->srcImgProfile();
		}
//-----------UPLOAD D'UN MEMOIRE--------------
		if (!empty($_POST['send-file'])) {
			$manager->uploadMemoire('uploadMemoire');
			$nomFichier = $manager->uploadMemoire('uploadMemoire');
			if (empty($nomFichier)) {
				$uploadError = "Le fichier n'a pas pu être uploadé ! <br/> Verifier la taille de votre 
				fichier ou l'extension du fichier !";
			}
			else{
				$theme=htmlspecialchars($_POST['theme']);
				$auteur=htmlspecialchars($_POST['auteur']);
				$description=htmlspecialchars($_POST['memoire-description']);
				$email=htmlspecialchars($_SESSION['email']);
				$adrMem='memoires/'.htmlspecialchars($nomFichier);

				$manager->addMem($theme,$auteur,$description,$email,$adrMem);
				$uploadSuccess = "L'enregistrement de votre mémoire s'est effectué avec succès !!!";
			}
		}

//DETERMINATION DU NOMBRE DE JOURS RESTANT ET DUREE DE STAGE
		$dateDebut = $stagiaire->debutStage();
		$dateFin = $stagiaire->finStage();
		$dateCourante = date('y-m-d');
		$dateArrayDebut = explode("-", $dateDebut);
		$dateArrayFin = explode("-", $dateFin);
		$dateArrayCourante = explode("-", $dateCourante);
		$jourDebut = (int) $dateArrayDebut[2];
		$moisDebut =  (int)$dateArrayDebut[1];
		$anneeDebut =  (int)$dateArrayDebut[0];
		$jourFin = (int) $dateArrayFin[2];
		$moisFin =  (int)$dateArrayFin[1];
		$anneeFin =  (int)$dateArrayFin[0];
		$jourCourant = (int) $dateArrayCourante[2];
		$moisCourant =  (int)$dateArrayCourante[1];
		$anneeCourant =  (int)$dateArrayCourante[0];
		$anneeCourant = $anneeCourant + 2000;
		/*DUREE DE STAGE */
		$dureeStage = ($anneeFin - $anneeDebut) * 365 + ($moisFin - $moisDebut) * 30 + ($jourFin - $jourDebut);
		/*DETERMINATION DU NOMBRE DE JOURS RESTANT*/
		$dureeRestant = ($anneeFin - $anneeCourant) * 365 + ($moisFin - $moisCourant) * 30 + ($jourFin - $jourCourant);
		/*SI SA PERIODE DE STAGE EST ECOULEE ON LE DESACTIVE*/
		
		if ($dureeRestant==0 && $stagiaire->statut()==1) {
			$manager->disableStagiaire($stagiaire);
		}
		elseif ($dureeRestant>0 && $stagiaire->statut()==0) {
			$manager->enableStagiaire($stagiaire);
		}
	//------------SELECTIONNER TAF-----------------
		$taf = $manager->getTafStag($_GET['email']);
	//------------ADD TAF-----------------
		if (isset($_POST['taf-etat']) && isset($_POST['taf-id']) && isset($_POST['dateFin']) &&
			isset($_POST['heureFin'])) {
			$manager->uploadTaf($_POST['taf-id'],$_POST['dateFin'],$_POST['heureFin']);
		}
// INITIALISATION DES SESSIONS
		$_SESSION['id']=$stagiaire->id();
		$_SESSION['nom']=$stagiaire->nom();
		$_SESSION['prenom']=$stagiaire->prenom();
		$_SESSION['age']=$stagiaire->age();
		$_SESSION['universite']=$stagiaire->universite();
		$_SESSION['classe']=$stagiaire->classe();
		$_SESSION['debutStage']=$stagiaire->debutStage();
		$_SESSION['finStage']=$stagiaire->finStage();
		$_SESSION['statut']=$stagiaire->statut();
		$_SESSION['srcImgProfile']='img/profiles/'.$stagiaire->srcImgProfile();
		$_SESSION['mdp']=$stagiaire->mdp();
		$_SESSION['email']=$stagiaire->email();
		$_SESSION['filiaire']=$stagiaire->filiaire();
		$_SESSION['noteStage']=$stagiaire->noteStage();
		$_SESSION['dureeStage']=$dureeStage;
		$_SESSION['dureeRestant']=$dureeRestant;
		if (isset($_SESSION['statut']) && $_SESSION['statut']==1) {
			$statut= 'activé';
		} 
		elseif (isset($_SESSION['statut']) && $stagiaire->statut()==0) {
			$statut= 'desactivé';
		}
//AJOUT DE SUGESTION
		if (isset($_POST['suggestion']) && !empty($_POST['suggestion'])) {
			$manager->addSuggestion($stagiaire,$_POST['suggestion']); 
		}
//AFFICHAGE DE SUGGESTIONS
		$suggestions = $manager->getSuggestion();

// -------------------------- PERMISSIONS --------------------------
		if (isset($_POST['objet']) && isset($_POST['email']) && isset($_POST['permissions-description']) && isset($_POST['heureDepart']) && isset($_POST['finStage'])) {
			$_POST['permissions-description'] = htmlspecialchars($_POST['permissions-description']);
			$_POST['heureDepart'] = htmlspecialchars($_POST['heureDepart']);
			$_POST['finStage'] = htmlspecialchars($_POST['finStage']);
			$_POST['email'] = htmlspecialchars($_POST['email']);
			$_POST['objet'] = htmlspecialchars($_POST['objet']);
			$manager->addPermission($_POST['objet'],$_POST['permissions-description'],$_POST['heureDepart'],$_POST['finStage'],$_POST['email']);
		}
		$permissions = $manager->getPermissions($_SESSION['email']);
	}
// -------------------------- RECHERCHE DE TUTEUR --------------------------
	if (isset($_POST['search-stag'])) {
		$result = $manager->existTuteur($_POST['search-stag']);
		if ($result) {
			$searchContent = $manager->getTuteur($_POST['search-stag']);
		}
		else{
			$noMatch = "Votre recherche n'a pas abouti ! Entrer un email existant";
		}
		//var_dump($result);
	}

	if (isset($_POST['search-memoire']) && isset($_POST['auteur'])) {
		$resultMemoire = $manager->getMemoire($_POST['auteur']);
		if (!empty($resultMemoire)) {
			//var_dump($resultMemoire[0]['theme']);
		}
		else{
			$noMatchMemoire = "L'email entrée ne correspond pas !";
		}
	}

	/*var_dump($_FILES);*/
	?>
	<title>Espace Stagiaire</title>
	<style>
		body{
			background:#eee;
		}
	</style>
</head>
<?php 
if ($statut=="desactivé") {

	?>
	<body <?php echo 'id="blockStagiaire"';?> >
		<?php
	}
	?>
	<div class="espace-perso-stag">
		<div class="top-menu flex-container plm prm pts pbs bgd-green-light">
			<div class="large-w60 medium-w40 small-w100 tiny-w100 large-wa">
				<span>Statut</span> : <strong> <?php echo $statut; ?></strong><span> (<?php echo $_SESSION['dureeRestant'];?> jour(s) restant)</span>
			</div>
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
						<a href="" class="mrs ">Mes Tâches <i class="fa fa-tasks fr"></i></a>
					</li>
					<li class="tuteurs">
						<a href="">Tuteurs<i class="fa fa-user fr"></i></a>
					</li>
					<li class="memoire">
						<a href="">Mémoire<i class="fa fa-book fr"></i></a>
					</li>
					<li class="permissions">
						<a href="">Permissions<i class="fa fa-star fr"></i></a>
					</li>
				</ul>
			</div>

			<div class="side-right ">
				<div class="" id="taches">
					<h2 class="">Mes tâches</h2>
					<div class="grid-2-small-1-tiny-1">
						<?php 
						if (!empty($taf)) {
						foreach ($taf as $unTaf) {?>
						<article class="flex-container art1">
							<strong class="tache-title w60 fl bgd-green-light"><?php echo $unTaf['titre']; ?></strong>
							<form action="" method="post">
							
							<input type="submit" name = "taf-etat"class="w30 fr txtcenter bgd-gray-light" style=<?php $unTaf['etat']=(int) $unTaf['etat'];
							if ($unTaf['etat']==0) {
								echo '"background-color:#ccc"';
							} else{echo '"background-color:#24B724; color:#fff;"';}
							 $unTaf['etat']=(int) $unTaf['etat'];
							if ($unTaf['etat']==0) {
								echo "value='Non résolu'";
							} else{echo "value='Résolu'";}?>/>
								<input type="hidden" name="taf-id" value=<?php echo '"'.$unTaf['id'].'"' ?>>
								<input type="hidden" name="dateFin" value=<?php echo date('d-m-Y'); ?>>
								<input type="hidden" name="heureFin" value=<?php echo date('G:i:s'); ?>>
							</form>
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
									</div>

									<div class="" id="tuteurs">
										<h2>Rechercher un tuteur</h2>
										<form action="" method="post" class="flex-container">
							<input type="search" name="search-stag" required placeholder="Entrer l'email du tuteur" class="w90 input-search fl"><button class="fr"><i class=" fa fa-search  large-i"></i></button>
						</form>
										<?php if (isset($_POST['search-stag']) && isset($noMatch)) {
							echo '<p class="alert-success">Aucune correspondance ! </p>';
						} elseif (isset($_POST['search-stag']) && !empty($searchContent)) {
							echo '<p class="alert-success">Une correspondance ! </p>';
						} ?>
							<?php if (!empty($searchContent)) { ?>

										<div class="flex-container">
												<div class="side-left-l mrl w100p">
													<h3>Nom :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php  echo $searchContent->nom();?></span> </h3>
													<h3>Prenom :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->prenom();?></span> </h3>
												</div>

												<div class=" mll">
													<h3>Email :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->email();?></span>  </h3>
													<h3>Profession :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $searchContent->profession();?></span>  </h3>
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
										<h2>Uploader un rapport</h2>
										<strong style="color:green"><?php if (isset($uploadSuccess)) {
											echo $uploadSuccess;
										} ?></strong>
										<strong style="color:red"><?php if (isset($uploadError)) {
											echo $uploadError;
										} ?></strong>
										<form action="#" method="post" enctype="multipart/form-data">
											<input type="text" name="theme" placeholder="Votre thème" required class="w90 left flex-container theme">
											<input type="hidden" name="auteur" value=<?php echo $_SESSION['email']; ?> class="mts w90 left flex-container auteur">
											<textarea name="memoire-description" class="flex-container left memoire-description" id="" placeholder="Description" cols="30" rows="10"></textarea>
											<div class="input-file-container left">  
												<input class="input-file" id="my-file" type="file" name="uploadMemoire">
												<label tabindex="0" for="my-file" class="input-file-trigger">Sélectionner un fichier...</label>
												<button class="btn btn-move " name="send-file" value="send-file">Envoyer</button>
											</div>

										</form>
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
													<div class="w10 txtright " style="color:orange">(<?php $unPermission['statut'] = (int) $unPermission['statut']; 
														switch ($unPermission['statut']) {
															case '0':
															echo "en cours";
															break;
															case '1':
															echo "Refusée";
															break;

															case '2':
															echo "Accordée";
															break;
														}
														?>)</div>
													</div>

													<?php } }?>


													<h2>Demander une permission</h2>
													<form action="" method="post">
														<input type="text" name="objet" required class="w90 left flex-container objet" placeholder="Motif...">
														<textarea name="permissions-description" class="permissions-description" required class="flex-container left" id="" placeholder="Description..." cols="30" rows="10"></textarea>
														<input type="text" data-beatpicker="true"  name="heureDepart" data-beatpicker-position="['*','*']" data-beatpicker-range="true">
														<input type="hidden" name="email" value=<?php echo '"'.$_SESSION['email'].'"'; ?>>
														<button class="mbm plm prm pts pbs  permissions-btn annule-ml">Envoyer<i class="mls fa fa-send "></i></button>

													</form>
												</div>
												<div class="" id="suggession">
													<h2>Ecrire une suggestion</h2>
													<form action="" method="post">
														<textarea name="suggestion" class="flex-container left w75" id="" placeholder="Votre suggestion..." cols="30" rows="10"></textarea>
														<button class="mbm plm prm pts pbs  annule-ml ">Envoyer<i class="mls fa fa-send"></i></button>
													</form>

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
														<h2 class="">Messages reçus</h2>
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
																		<a href="" class="mrs inbox-link<?php echo $i;?>"><?php echo $unMessage['expediteur']; ?><i class="fa fa-eye"></i></a> <em>à <?php echo $unMessage['heureSug'][0].'H '.$unMessage['heureSug'][1].'min '.' '.$unMessage['heureSug'][2].'s ' ?>, le 
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
																if (!$manager->existTuteur(htmlspecialchars($_POST['email']))) {
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
																	<h3>Durée de stage :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $_SESSION['dureeStage'];?> jour(s)</span>  </h3>
																</div>

																<div class=" mll">
																	<h3>Université :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $_SESSION['universite'];?></span> </h3>
																	<h3>Niveau :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $_SESSION['classe'];?></span> </h3>
																	<h3>Filière :  <span class="" style="color:green; font-style:normal; text-decoration:none;"><?php echo $_SESSION['filiaire'];?></span> </h3>
																</div>
															</div>
														</div>
														<div id="ficheDePresence">
															<h2 class="">Fiche de présence</h2>
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
												unset($_SESSION['dureeStage']);
												unset($_SESSION['dureeRestant']);
												header('location:index.php');
											}
											?>