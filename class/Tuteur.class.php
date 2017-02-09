<?php 
/**
* Author : SadTech Lab. 
* Date : 10 aout 2015 à 12h 11
*/
class Tuteur {
	/*CONSTRUCTEUR*/
	public function __construct(array $donnees){
		$this->hydrate($donnees);
	}

	/*ATTRIBUTS*/
	protected $id,
	$nom,
	$prenom,
	$age,
	$profession,
	$srcImgProfile,
	$mdp,
	$email;

	/*CONSTANTES*/
	const DESACTIVE = 0,
		  ACTIVE = 1,
		  PERMISSION_ACCORDEE = 2,
		  PERMISSION_REFUSEE = 3;
	/*METHODES*/

	public function hydrate(array $donnees){
		foreach ($donnees as $key => $value) {
			$method = 'set'.ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
	}
	/*GETTERS*/
	public function id(){ return $this->id;}
	public function nom(){ return $this->nom;}
	public function prenom(){ return $this->prenom;}
	public function age(){ return $this->age;}
	public function profession(){ return $this->profession;}
	public function srcImgProfile(){ return $this->srcImgProfile;}
	public function mdp(){ return $this->mdp;}
	public function email(){ return $this->email;}

	/*SETTERS*/
	public function setId($id){
		if (is_int($id) && $id>=0) {
			$this->id = $id;
		}
	}
	public function setNom($nom){
		if (is_string($nom)) {
			$this->nom = $nom;
		}
	}
	public function setPrenom($prenom){
		if (is_string($prenom)) {
			$this->prenom = $prenom;
		}
	}
	public function setAge($age){
		if (is_int($age) && $age>=14) {
			$this->age = $age;
		}
	}
	public function setProfession($profession){
		if (is_string($profession)) {
			$this->profession = $profession;
		}
	}
	public function setSrcImgProfile($srcImgProfile){
		if (is_string($srcImgProfile)) {
			$this->srcImgProfile = $srcImgProfile;
		}
	}
	public function setMdp($mdp){
		if (is_string($mdp)) {
			$this->mdp = $mdp;
		}
	}
	public function setEmail($email){
		if (is_string($email)) {
			$this->email = $email;
		}
	}
	
	/*============== AUTRE METHODES ==============*/
	
}