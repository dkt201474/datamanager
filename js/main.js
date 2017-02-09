$(function(){
	var	$taches = $("#taches"),
	 	$tuteurs = $("#tuteurs"),
		$memoire = $("#memoire"),
		$permissions = $("#permissions"),
		$suggession = $("#suggession"),
		$inbox = $("#inbox"),
		$profil = $("#profil"),
		$ficheDePresence = $("#ficheDePresence");
	
	var $tachesLink = $('.side-left .taches'),
		$tuteursLink = $('.side-left .tuteurs'),
		$memoireLink = $('.side-left .memoire'),
		$permissionsLink = $('.side-left .permissions'),
		$suggessionLink = $('.top-menu .mesSuggession'),
		$inboxLink = $('.top-menu .inboxLink'),
		$profilLink = $('.top-menu .profilLink'),
		$ficheDePresenceLink = $('.ficheDePresenceLink');
	function closeAllDiv(){
			$taches.hide('slow');
			$tuteurs.hide('slow');
			$memoire.hide('slow');
			$permissions.hide('slow');
			$suggession.hide('slow');
			$inbox.hide('slow');
			$profil.hide('slow');
			$ficheDePresence.hide('slow');
	}
	function removeAllActive (){
			if ($tachesLink.hasClass('active') || $tuteursLink.hasClass('active') || 
				$memoireLink.hasClass('active') || $permissionsLink.hasClass('active') || $('.ficheDePresenceLink').hasClass('active')) {
			$('.taches').removeClass('active');
			$('.tuteurs').removeClass('active');
			$('.memoire').removeClass('active');
			$('.permissions').removeClass('active');
			$('.ficheDePresenceLink').removeClass('active');
		}
	}
	closeAllDiv();
	$inbox.show('slow');
	$tachesLink.click(function(){
		closeAllDiv();
		$taches.show('slow');
		removeAllActive();
		$tachesLink.addClass('active');
		return false;
	});
	$tuteursLink.click(function(){
		closeAllDiv();
		$tuteurs.show('slow');
		removeAllActive();
		$tuteursLink.addClass('active');
		return false;
	});
	$memoireLink.click(function(){
		closeAllDiv();
		$memoire.show('slow');
		removeAllActive();
		$memoireLink.addClass('active');
		return false;
	});
	$permissionsLink.click(function(){
		closeAllDiv();
		$permissions.show('slow');
		removeAllActive();
		$permissionsLink.addClass('active');
		return false;
	});
	$suggessionLink.click(function(){
		closeAllDiv();
		$suggession.show('slow');
		removeAllActive();
		return false;
	});
	$inboxLink.click(function(){
		closeAllDiv();
		$inbox.show('slow');
		removeAllActive();
		return false;
	});
	$profilLink.click(function(){
		closeAllDiv();
		$profil.show('slow');
		removeAllActive();
		return false;
	});
	$ficheDePresenceLink.click(function(){
		closeAllDiv();
		$ficheDePresence.show('slow');
		removeAllActive();
		$ficheDePresenceLink.addClass('active');
		return false;
	});

	$('.block-event').click(function(){
		if ($email.val().length ==0) {
			$email.css({
				"border-color" : "red",
				"color":"red"
			});
			$email.addClass("animate-error");
			alert("Le champs "+$email.attr("name")+" ne doit pas être vide !");
			return false;
		}
		closeAllDiv();
		$inbox.show();
	});

	$('#inbox-inner1').hide('fast');
	$('#inbox-inner2').hide('fast');
	$('#inbox-inner3').hide('fast');
	$('#inbox-inner4').hide('fast');
	$('#inbox-inner5').hide('fast');
	$('.inbox-link1').click(function(){
		$('#inbox-inner1').fadeToggle('slow');
		return false;
	});

	$('.inbox-link2').click(function(){
		$('#inbox-inner2').fadeToggle('slow');
		return false;
	});

	$('.inbox-link3').click(function(){
		$('#inbox-inner3').fadeToggle('slow');
		return false;
	});

	$('.inbox-link4').click(function(){
		$('#inbox-inner4').fadeToggle('slow');
		return false;
	});

	$('.inbox-link5').click(function(){
		$('#inbox-inner5').fadeToggle('slow');
		return false;
	});

	$('.art1 h4 a').click(function(){
		$('.art1 .more-content').fadeToggle('slow');
		$('.art1 h4 i').toggleClass('fa-plus');
		$('.art1 h4 i').toggleClass('fa-minus');
		return false;
	});
	$('.art2 h4 a').click(function(){
		$('.art2 .more-content').fadeToggle('slow');
		$('.art2 h4 i').toggleClass('fa-plus');
		$('.art2 h4 i').toggleClass('fa-minus');
		return false;
	});


	/*MESSAGES RECU*/


	/*AJOUT DU NAME POUR LE 2e INPUT DU DATEPICKER*/
	$('.beatpicker-input-to').attr('name','finStage');

	/*CONTROLE DU FORMULAIRE*/
	/*NOMMENCLATURE DES OBJETS INPUT*/
	var $nom=$("input[name='nom']"),
		$prenom=$("input[name='prenom']"),
		$universite=$("input[name='universite']"),
		$email=$("input[name='email']"),
		$filiaire=$("input[name='filiaire']"),
		$debutStage=$("input[name='debutStage']"),
		$mdp=$("input[name='mdp']"),
		$mdp2=$("input[name='mdp2']"),
		$finStage=$("input[name='finStage']");
		$inscription=$(".btn-inscription");
		$connexion=$(".connexion");
/*-----------------CONTROLE INSCRIPTION---------------------*/	
	$inscription.click(function(){
		if ($nom.val().length ==0) {
			$nom.css({
				"border-color" : "red",
				"color":"red"
			});
			$nom.addClass("animate-error");
			alert("Le champs "+$nom.attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$nom.css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($prenom.val().length ==0) {
			$prenom.css({
				"border-color" : "red",
				"color":"red"
			});
			$prenom.addClass("animate-error");
			alert("Le champs "+$prenom.attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$prenom.css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($universite.val().length ==0) {
			$universite.css({
				"border-color" : "red",
				"color":"red"
			});
			$universite.addClass("animate-error");
			alert("Le champs "+$universite.attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$universite.css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($email.val().length ==0) {
			$email.css({
				"border-color" : "red",
				"color":"red"
			});
			$email.addClass("animate-error");
			alert("Le champs "+$email.attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$email.css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($filiaire.val().length ==0) {
			$filiaire.css({
				"border-color" : "red",
				"color":"red"
			});
			$email.addClass("animate-error");
			alert("Le champs "+$filiaire.attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$filiaire.css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($mdp.val().length <= 5 || $mdp2.val().length <= 5) {
			$mdp.css({
				"border-color" : "red",
				"color":"red"
			});
			$mdp2.css({
				"border-color" : "red",
				"color":"red"
			});
			$mdp.addClass("animate-error");
			$mdp2.addClass("animate-error");
			alert("Le champs de mot de passe doivent dépasser 5 caractères !");
			return false;
		}

		if ($mdp.val() != $mdp2.val()) {
			$mdp2.css({
				"border-color" : "red",
				"color":"red"
			});
			$mdp2.addClass("animate-error");
			alert("Votre mot de passe doit être le même pour le champs de confirmation de mot de passe !");
			return false;
		}
		else{
			$mdp2.css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($debutStage.val().length ==0) {
			$debutStage.css({
				"border-color" : "red",
				"color":"red"
			});
			$debutStage.addClass("animate-error");
			alert("Le champs "+$debutStage.attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$debutStage.css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($finStage.val().length ==0) {
			$finStage.css({
				"border-color" : "red",
				"color":"red"
			});
			$finStage.addClass("animate-error");
			alert("Le champs "+$finStage.attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$finStage.css({
				"border-color" : "green",
				"color":"green"
			});
		}

	});

	$connexion.click(function(){
	if ($email.val().length ==0) {
		$email.css({
			"border-color" : "red",
			"color":"red"
		});
		$email.addClass("animate-error")
		alert("Le champs "+$email.attr("name")+" ne doit pas être vide !");
		return false;
		;		}
		else{
			$email.css({
				"border-color" : "green",
				"color":"green"
			});
		}

		if ($mdp.val().length <= 5) {
			$mdp.css({
				"border-color" : "red",
				"color":"red"
			});
			$mdp.addClass("animate-error");
			alert("Le champs mot de passe doit dépasser 5 caractères !");
			return false;
		}
	});

/*----------------CONTROLE PERMISSIONS-----------------*/
	$(".permissions-btn").click(function(){
		if ($(".objet").val().length ==0) {
			$(".objet").css({
				"border-color" : "red",
				"color":"red"
			});
			$(".objet").addClass("animate-error");
			alert("Le champs "+$(".objet").attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$(".objet").css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($(".permissions-description").val().length ==0) {
			$(".permissions-description").css({
				"border-color" : "red",
				"color":"red"
			});
			$(".permissions-description").addClass("animate-error");
			alert("Le champs "+$(".permissions-description").attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$(".permissions-description").css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($("input[name=heureDepart]").val().length ==0) {
			$("input[name=heureDepart]").css({
				"border-color" : "red",
				"color":"red"
			});
			$("input[name=heureDepart]").addClass("animate-error");
			alert("Le champs "+$("input[name=heureDepart]").attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$("input[name=heureDepart]").css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($finStage.val().length ==0) {
			$finStage.css({
				"border-color" : "red",
				"color":"red"
			});
			$finStage.addClass("animate-error");
			alert("Le champs "+$finStage.attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$finStage.css({
				"border-color" : "green",
				"color":"green"
			});
		}
	});
	$('#blockStagiaire').hover(function(){
		alert("Vous avez été désactivé !");
	});

/*----------------CONTROLE UPLOAD MEMOIRE-----------------*/
	$(".btn-move").click(function(){
		if ($(".theme").val().length ==0) {
			$(".theme").css({
				"border-color" : "red",
				"color":"red"
			});
			$(".theme").addClass("animate-error");
			alert("Le champs "+$(".theme").attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$(".theme").css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($(".auteur").val().length ==0) {
			$(".auteur").css({
				"border-color" : "red",
				"color":"red"
			});
			$(".auteur").addClass("animate-error");
			alert("Le champs "+$(".auteur").attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$(".auteur").css({
				"border-color" : "green",
				"color":"green"
			});
		}
		if ($(".memoire-description").val().length ==0) {
			$(".memoire-description").css({
				"border-color" : "red",
				"color":"red"
			});
			$(".memoire-description").addClass("animate-error");
			alert("Le champs "+$(".memoire-description").attr("name")+" ne doit pas être vide !");
			return false;
		}
		else{
			$(".memoire-description").css({
				"border-color" : "green",
				"color":"green"
			});
		}
			if ($(".input-file").val().length ==0) {
			$(".input-file").css({
				"border-color" : "red",
				"color":"red"
			});
			$(".input-file").addClass("animate-error");
			alert("Veuillez d'abord sélectionner le fichier à envoyer !");
			return false;
		}
		else{
			$(".input-file").css({
				"border-color" : "green",
				"color":"green"
			});
		}
	});
/*----------------CONTROLE FORM TACHE-----------------*/
	$(".tache-btn").click(function(){
			if ($(".sujet").val().length ==0) {
				$(".sujet").css({
					"border-color" : "red",
					"color":"red"
				});
				$(".sujet").addClass("animate-error");
				alert("Le champs "+$(".sujet").attr("name")+" ne doit pas être vide !");
				return false;
			}
			else{
				$(".sujet").css({
					"border-color" : "green",
					"color":"green"
				});
			}
			if ($(".titre").val().length ==0) {
				$(".titre").css({
					"border-color" : "red",
					"color":"red"
				});
				$(".titre").addClass("animate-error");
				alert("Le champs "+$(".titre").attr("name")+" ne doit pas être vide !");
				return false;
			}
			else{
				$(".titre").css({
					"border-color" : "green",
					"color":"green"
				});
			}
			if ($("#tache-description").val().length ==0) {
				$("#tache-description").css({
					"border-color" : "red",
					"color":"red"
				});
				$("#tache-description").addClass("animate-error");
				alert("Le champs "+$("#tache-description").attr("name")+" ne doit pas être vide !");
				return false;
			}
			else{
				$(".tache-description").css({
					"border-color" : "green",
					"color":"green"
				});
			}
		});


$('#blockStagiaire').hover(function(){
			alert("Vous avez été désactivé !");
		});

function refreshPage(){
$.ajax({type: "GET", url:"/dataManager/espace-personnel-stagiaire.php",
		 data:"action=refresh"}); 
setTimeout('refreshPage()',5);
}
refreshPage();

function refreshPageTuteur(){
$.ajax({type: "GET", url:"/dataManager/espace-personnel-tuteur.php",
		 data:"action=refresh"}); 
setTimeout('refreshPage()',5);
}

});

/*-----------STYLE POUR LE BOUTON FILE------------------*/
document.querySelector("html").classList.add('js');
var fileInput = document.querySelector( ".input-file" ),  
button = document.querySelector( ".input-file-trigger" ),
the_return = document.querySelector(".file-return");
button.addEventListener( "keydown", function( event ) {  
	if ( event.keyCode == 13 || event.keyCode == 32 ) {  
		fileInput.focus();  
	}  
});
button.addEventListener( "click", function( event ) {
	fileInput.focus();
	return false;
});  
fileInput.addEventListener( "change", function( event ) {  
	the_return.innerHTML = this.value;  
});  

