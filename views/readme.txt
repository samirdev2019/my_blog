<a href=index.php?p=post&id=<?=$post->id?><?= $post->id?></a>
pour une petite amélioration 

<a href=<?php $post->url?>><?= $post->chapo ?></a>
<?php $post->content ?>


j'ai ajouter l'identifiant de l'article dans le lien updatPost dans le fichier
ManagerPosts.twig, maintenant il m'a rester qu'à 
1-appeller la requete getPost,pour récupérer le poste,
2- transferer les données de poste vers le fichier updatePost.twig
3-afficher les info dans le formulaire de modification
4-confirmation des modis
5_controler les données envoyé
6_ si tout est bon, alors mettre à jour l'article puis afficher un message de bon déroulement de 
la mis à jour
, 
puis : passer à la supression d'un article, en récupurant just son id
comme on a fait avec modification,; 

puis voir si tu peux amélioer les interface, plus les template
;procéder à la pagination si possible
; faire tes diagramme UML
; 

