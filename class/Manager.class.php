<?php 
/**
* Author : SadTech Lab. 
* Date : 17 aout 2015 à 8h 34
*/
class Manager
{
	protected $db;
	public function __construct($db){
		$this->setDb($db);
	}
	public function setDb(PDO $db){
		$this->db = $db;
	}
	public function addStagiaire(Stagiaire $perso){
		$q = $this->db->prepare('INSERT INTO stagiaire (nom,prenom,age,universite,classe,debutStage,
			finStage,mdp,email,filiaire,statut) 
		VALUES(:nom,:prenom,:age,:universite,:classe,:debutStage,
			:finStage,:mdp,:email,:filiaire,:statut)');
		$q->execute(array(
			'nom'=>$perso->nom(),
			'prenom'=>$perso->prenom(),
			'age'=>$perso->age(),
			'universite'=>$perso->universite(),
			'classe'=>$perso->classe(),
			'debutStage'=>$perso->debutStage(),
			'finStage'=>$perso->finStage(),
			'mdp'=>$perso->mdp(),
			'email'=>$perso->email(),
			'filiaire'=>$perso->filiaire(),
			'statut'=>1
			));
		return $perso = new Stagiaire(array(
			'id'=>(int) $this->db->lastInsertId(),
			'nom'=>$perso->nom(),
			'prenom'=>$perso->prenom(),
			'age'=>$perso->age(),
			'universite'=>$perso->universite(),
			'classe'=>$perso->classe(),
			'debutStage'=>$perso->debutStage(),
			'finStage'=>$perso->finStage(),
			'mdp'=>$perso->mdp(),
			'email'=>$perso->email(),
			'filiaire'=>$perso->filiaire(),
			'statut'=>1
			));
		/*var_dump($perso);*/
	}

	public function addTuteur(Tuteur $perso){
		$q = $this->db->prepare('INSERT INTO tuteur (nom,prenom,age,srcImgProfile,profession,mdp,email) VALUES(:nom,:prenom,:age,:srcImgProfile,:profession,:mdp,:email)');
		$q->execute(array(
			'nom'=>$perso->nom(),
			'prenom'=>$perso->prenom(),
			'age'=>$perso->age(),
			'profession'=>$perso->profession(),
			'mdp'=>$perso->mdp(),
			'email'=>$perso->email(),
			'srcImgProfile'=>''
			));
		//var_dump($perso);
		return $perso = new Tuteur(array(
			'id'=>(int) $this->db->lastInsertId(),
			'nom'=>$perso->nom(),
			'prenom'=>$perso->prenom(),
			'age'=>$perso->age(),
			'profession'=>$perso->profession(),
			'srcImgProfile'=>'',
			'mdp'=>$perso->mdp(),
			'email'=>$perso->email()
			));
	}

	public function getStagiaire($email){
		//------->>> SI LE PARAMETRE EST UN EMAIL
		if (is_string($email)) {
			$q = $this->db->prepare('SELECT * FROM stagiaire WHERE email=:email');
			$q->execute(array(
				'email'=>$email
				));
			$data = $q->fetch(PDO::FETCH_ASSOC);
			return $perso = new Stagiaire(array(
				'id'=>(int) $data['id'],
				'nom'=>$data['nom'],
				'prenom'=>$data['prenom'],
				'age'=>(int) $data['age'],
				'universite'=>$data['universite'],
				'classe'=>(int) $data['classe'],
				'debutStage'=>$data['debutStage'],
				'finStage'=>$data['finStage'],
				'statut'=>(int) $data['statut'],
				'srcImgProfile'=>$data['srcImgProfile'],
				'noteStage'=>$data['noteStage'],
				'mdp'=>$data['mdp'],
				'email'=>$data['email'],
				'filiaire'=>$data['filiaire']
				)); 
		}
	}

	public function getTuteur($email){
		//------->>> SI LE PARAMETRE EST UN EMAIL
		if (is_string($email)) {
			$q = $this->db->prepare('SELECT * FROM tuteur WHERE email=:email');
			$q->execute(array(
				'email'=>$email
				));
			$data = $q->fetch(PDO::FETCH_ASSOC);
			return $perso = new Tuteur(array(
				'id'=>(int) $data['id'],
				'nom'=>$data['nom'],
				'prenom'=>$data['prenom'],
				'age'=>(int) $data['age'],
				'profession'=>$data['profession'],
				'srcImgProfile'=>$data['srcImgProfile'],
				'mdp'=>$data['mdp'],
				'email'=>$data['email']
				)); 
		}
	}

	public function exist($perso){
		if (is_object($perso)) {
			$q = $this->db->prepare('SELECT * FROM stagiaire WHERE email=:email');
			$q->execute(array(
				'email'=>$perso->email()
				));
		}
		if (is_string($perso)) {
			$q = $this->db->prepare('SELECT * FROM stagiaire WHERE email=:email');
			$q->execute(array(
				'email'=>$perso
				));
		}
		return (bool) $q->fetchcolumn();
	}

	public function existTuteur($perso){
		if (is_object($perso)) {
			$q = $this->db->prepare('SELECT * FROM tuteur WHERE email=:email');
			$q->execute(array(
				'email'=>$perso->email()
				));
		}
		if (is_string($perso)) {
			$q = $this->db->prepare('SELECT * FROM tuteur WHERE email=:email');
			$q->execute(array(
				'email'=>$perso
				));
		}
		return (bool) $q->fetchcolumn();
	}

	public function validPassTut(Tuteur $perso){
		$q = $this->db->prepare('SELECT * FROM tuteur WHERE email=:email AND mdp=:mdp');
		$q->execute(array(
			'email'=>$perso->email(),
			'mdp'=>$perso->mdp()
			));
		return (bool) $q->fetchcolumn();
	}

	public function ValidPassStag(Stagiaire $perso){
		$q = $this->db->prepare('SELECT * FROM stagiaire WHERE email=:email AND mdp=:mdp');
		$q->execute(array(
			'email'=>$perso->email(),
			'mdp'=>$perso->mdp()
			));
		return (bool) $q->fetchcolumn();
	}

	public function  disableStagiaire(Stagiaire $perso){
		$q = $this->db->prepare('UPDATE stagiaire SET statut=:statut WHERE email=:email');
		$q->execute(array(
			'statut'=>0,
			'email'=>$perso->email()
			));
	}

	public function enableStagiaire(Stagiaire $perso){
		$q = $this->db->prepare('UPDATE stagiaire SET statut=:statut WHERE email=:email');
		$q->execute(array(
			'statut'=>1,
			'email'=>$perso->email()
			));
	}
	public function addSuggestion(Stagiaire $perso,$desc){
		$q = $this->db->prepare('INSERT INTO suggestions (nom,prenom,dateEnv,description) 
			VALUES(:nom,:prenom,NOW(),:description)');
		$q->execute(array(
			'nom'=>$perso->nom(),
			'prenom'=>$perso->prenom(),
			'description'=>$desc
			));
	}

	public function getSuggestion(){
		//------->>> SI LE PARAMETRE EST UN EMAIL
			$q = $this->db->query('SELECT * FROM suggestions ORDER BY id DESC LIMIT 0,10');
			//$data = $q->fetch(PDO::FETCH_ASSOC);
			while ($data = $q->fetch()) {
			$dateSug = $data['dateEnv'];
			$dateSug = date('d/m/Y H:i:s');
			$dateSug = explode(" ", $data['dateEnv']);
			$dateSug = $dateSug[0];
			$heureSug = explode(" ", $data['dateEnv']);
			$heureSug = $heureSug[1];
			$heureSug = explode(":", $heureSug);

			$datas[] = array(
				'nom'=> $data['nom'],
				'prenom'=>$data['prenom'],
				'dateEnv'=>$dateSug,
				'heureSug'=>$heureSug,
				'description'=>$data['description']
				); 
		
		}
			return $datas;
	}
	public function countSuggestion(){
		$q = $this->db->query('SELECT COUNT(*) FROM suggestions');
		return $q->fetchcolumn();
	}

	public function countMessage($perso){
		$q = $this->db->prepare('SELECT COUNT(*) FROM mail WHERE recepteur=:recepteur');
		$q->execute(array('recepteur'=>$perso));
		$data = $q->fetchcolumn();
		if (empty($data)) {
			return 0;
		}
		return $data;
	}

	public function sendMail($expediteur, $recepteur,$description){
		$q = $this->db->prepare('INSERT INTO mail (expediteur,recepteur,description,dateEnv) 
			VALUES(:expediteur,:recepteur,:description,NOW())');
		$q->execute(array(
			'expediteur'=>$expediteur,
			'recepteur'=>$recepteur,
			'description'=>$description
			));
	}

	public function getMessages($recepteur){
		//------->>> SI LE PARAMETRE EST UN EMAIL
			$q = $this->db->prepare('SELECT * FROM mail WHERE recepteur=:recepteur ORDER BY id DESC LIMIT 0,5');
			$q->execute(array('recepteur'=>$recepteur));
			while ($data = $q->fetch()) {
				$dateSug = $data['dateEnv'];
				$dateSug = date('d/m/Y H:i:s');
				$dateSug = explode(" ", $data['dateEnv']);
				$dateSug = $dateSug[0];
				$heureSug = explode(" ", $data['dateEnv']);
				$heureSug = $heureSug[1];
				$heureSug = explode(":", $heureSug);

				$datas[] = array(
					'expediteur'=> $data['expediteur'],
					'recepteur'=> $data['recepteur'],
					'dateEnv'=>$dateSug,
					'heureSug'=>$heureSug,
					'description'=>$data['description']
				); 
		}
		if (!empty($datas)) {
			return $datas;
		}
	}

	public function uploadImg($monFichier){
		$monFichier = (string) $monFichier;
		$monFichier = htmlspecialchars($monFichier);
		// VERIFICATION QUE LE FICHIER A BIEN ETE ENVOYE
		if (isset($_FILES[$monFichier]) && $_FILES[$monFichier]['error'] == 0) {
			if ($_FILES[$monFichier]['size'] < 1000000) {
				$srcFichier = pathinfo($_FILES[$monFichier]['name']);
				$extensionUpload = $srcFichier['extension'];
				$extension_autorisees = array('jpg','jpeg','gif','png');
				if (in_array($extensionUpload, $extension_autorisees)) {
					move_uploaded_file($_FILES[$monFichier]['tmp_name'], 'img/profiles/'.basename($_FILES[$monFichier]['name']));
					return basename($_FILES[$monFichier]['name']);
				}
			}
			else{
				return "img/profiles/profil.png";
			}
		}
	}
	public function addImgSrc($nomFichier,$email){
		 	$q = $this->db->prepare('UPDATE stagiaire SET srcImgProfile=:srcImgProfile
			WHERE email=:email');
			$q->execute(array(
			'srcImgProfile'=>$nomFichier,
			'email'=>$email
			));
	}

	public function addImgSrcTut($nomFichier,$email){
		 	$q = $this->db->prepare('UPDATE tuteur SET srcImgProfile=:srcImgProfile
			WHERE email=:email');
			$q->execute(array(
			'srcImgProfile'=>$nomFichier,
			'email'=>$email
			));
	}

	public function addPermission($objet,$motif,$dateDepart,$dateRetour,$email){
		$q = $this->db->prepare('INSERT INTO permissions (objet,motif,dateDepart,dateRetour,statut,email) 
			VALUES(:objet,:motif,:dateDepart,:dateRetour,:statut,:email)');
		$q->execute(array(
			'objet'=>$objet,
			'motif'=>$motif,
			'email'=>$email,
			'dateDepart'=>$dateDepart,
			'dateRetour'=>$dateRetour,
			'statut'=>0
			));
	}
	public function ValidPermission($id){
		 	$q = $this->db->prepare('UPDATE permissions SET statut=:statut
			WHERE id=:id');
			$q->execute(array(
			'statut'=>2,
			'id'=>(int) $id
			));
	}

		public function getPermissions($email){
		//------->>> SI LE PARAMETRE EST UN EMAIL
			$q = $this->db->prepare('SELECT * FROM permissions WHERE email=:email ORDER BY id DESC');
			$q->execute(array('email'=>$email));
			while ($data = $q->fetch()) {
				$datas[] = array(
					'id'=> $data['id'],
					'objet'=> $data['objet'],
					'email'=> $data['email'],
					'motif'=> $data['motif'],
					'dateDepart'=>$data['dateDepart'],
					'dateRetour'=>$data['dateRetour'],
					'statut'=>$data['statut']
				); 
				//var_dump($datas);
		}
		if (!empty($datas)) {
			return $datas;
		}
	}
			public function getAllPermissions(){
		//------->>> SI LE PARAMETRE EST UN EMAIL
			$q = $this->db->query('SELECT * FROM permissions  ORDER BY id DESC');
			while ($data = $q->fetch()) {
				$datas[] = array(
					'id'=> $data['id'],
					'objet'=> $data['objet'],
					'email'=> $data['email'],
					'motif'=> $data['motif'],
					'dateDepart'=>$data['dateDepart'],
					'dateRetour'=>$data['dateRetour'],
					'statut'=>$data['statut']
				); 
		}
		if (!empty($datas)) {
			return $datas;
		}
	}
	public function uploadMemoire($monFichier){
		$monFichier = (string) $monFichier;
		$monFichier = htmlspecialchars($monFichier);
		// VERIFICATION QUE LE FICHIER A BIEN ETE ENVOYE
		if (isset($_FILES[$monFichier]) && $_FILES[$monFichier]['error'] == 0) {
			if ($_FILES[$monFichier]['size'] < 3000000) {
				$srcFichier = pathinfo($_FILES[$monFichier]['name']);
				$extensionUpload = $srcFichier['extension'];
				$extension_autorisees = array('doc','docx','pdf','pptx','ppt');
				if (in_array($extensionUpload, $extension_autorisees)) {
					move_uploaded_file($_FILES[$monFichier]['tmp_name'], 'memoires/'.basename($_FILES[$monFichier]['name']));
					return basename($_FILES[$monFichier]['name']);
				}
			}
			else{
				return "memoires/";
			}
		}
	}
	public function addMem($theme,$auteur,$description,$email,$adrMem){
		 	$q = $this->db->prepare('INSERT INTO memoires (theme,auteur,description,email,adrMem)
			VALUES(:theme,:auteur,:description,:email,:adrMem)');
			$q->execute(array(
			'theme'=>$theme,
			'auteur'=>$auteur,
			'description'=>$description,
			'email'=>$email,
			'adrMem'=>$adrMem
			));
	}
	public function addTaf($titre,$heureDebut,$dateDebut,$auteur,$sujet,$description){
		$q = $this->db->prepare('INSERT INTO taches (titre,etat,heureDebut,dateDebut,auteur,sujet,description) 
			VALUES(:titre,:etat,:heureDebut,:dateDebut,:auteur,:sujet,:description)');
		$q->execute(array(
			'titre'=>$titre,
			'etat'=>0,
			'heureDebut'=>$heureDebut,
			'dateDebut'=>$dateDebut,
			'auteur'=>$auteur,
			'sujet'=>$sujet,
			'description'=>$description
			));
	}

	public function uploadTaf($id,$dateFin,$heureFin){
		$q = $this->db->prepare('UPDATE taches SET etat=:etat,dateFin=:dateFin,heureFin=:heureFin WHERE id=:id');
		$q->execute(array(
			'id'=>(int) $id,
			'etat'=>1,
			'dateFin'=>$dateFin,
			'heureFin'=>$heureFin
			));
	}
	public function getTafStag($sujet){
			$q = $this->db->prepare('SELECT * FROM taches WHERE sujet=:sujet ORDER BY id');
			$q->execute(array('sujet'=>$sujet));
			while ($data = $q->fetch()) {
				$datas[] = array(
					'id'=> $data['id'],
					'titre'=> $data['titre'],
					'sujet'=> $data['sujet'],
					'etat'=> $data['etat'],
					'heureDebut'=> $data['heureDebut'],
					'heureFin'=> $data['heureFin'],
					'dateDebut'=> $data['dateDebut'],
					'dateFin'=> $data['dateFin'],
					'auteur'=> $data['auteur'],
					'description'=> $data['description']
				); 
		}
		if (!empty($datas)) {
			return $datas;
		}
	}

public function getTafTut($auteur){
			$q = $this->db->prepare('SELECT * FROM taches WHERE auteur=:auteur ORDER BY id');
			$q->execute(array('auteur'=>$auteur));
			while ($data = $q->fetch()) {
				$datas[] = array(
					'titre'=> $data['titre'],
					'sujet'=> $data['sujet'],
					'etat'=> $data['etat'],
					'heureDebut'=> $data['heureDebut'],
					'heureFin'=> $data['heureFin'],
					'dateDebut'=> $data['dateDebut'],
					'dateFin'=> $data['dateFin'],
					'auteur'=> $data['auteur'],
					'description'=> $data['description']
				); 
		}
		if (!empty($datas)) {
			return $datas;
		}
	}

	public function getMemoire($auteur){
			$q = $this->db->prepare('SELECT * FROM memoires WHERE auteur=:auteur ORDER BY id');
			$q->execute(array('auteur'=>$auteur));
			while ($data = $q->fetch()) {
				$datas[] = array(
					'id'=> $data['id'],
					'theme'=> $data['theme'],
					'auteur'=> $data['auteur'],
					'description'=> $data['description'],
					'adrMem'=> $data['adrMem']
				); 
		}
		if (!empty($datas)) {
			return $datas;
		}
	}
}

?>
