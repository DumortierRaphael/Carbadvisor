<?php
require_once("lib/db_parms.php");
   
Class DataLayer{
    private $connexion;
    public function __construct(){

            $this->connexion = new PDO(
                       DB_DSN, DB_USER, DB_PASSWORD,
                       [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                       ]
                     );

    }
    /**
     * Return data of a user
     * Param : pseudo of the user
     */
    function getUser($pseudo){
      $sql = <<<EOD
      select * FROM user
      where pseudo = :pseudo
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':pseudo',$pseudo);
      $stmt->execute();
      return $stmt->fetch();
    }

    /**
     * Return data of a station
     * Param : id of a station
     * 
     */
    function getStation($idStation){
      $sql = <<<EOD
      SELECT * FROM stations where id = :id
EOD;
      $stmt= $this->connexion->prepare($sql);
      $stmt->bindValue(':id',$idStation);
      $stmt->execute();
      return $stmt->fetch();

    }
    /**
     *  
     */




    /*
    *  Récupère une liste des coureurs ordonnée par equipe puis par nom
     *  résultat : liste (table) de coureurs. Chaque élément est une table associative (clés 'equipe', 'nom' et 'dossard')
     *
   function getTableQ1c(){
        $sql = <<<EOD
        select
        equipe, dossard, nom
        from coureurs
        order by equipe, nom
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        $res = [];
        while ($coureur = $stmt->fetch()) {
            $res[]= $coureur;
        }
        return $res;
        // ou return $stmt->fetchAll();
    }

    
    /* Récupère les informations de base sur l'équipe passée en paramètre
     * paramètre : nom d'une équipe
     * résultat : table associative (clés 'nom', 'couleur' et 'directeur')
     *   ou false si l'équipe n'existe pas
     *
    function getInfoEquipe($equipe){
        $sql = <<<EOD
        select
        nom, couleur, directeur
        from equipes
        where nom = :equipe
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':equipe', $equipe);
        $stmt->execute();
        return $stmt->fetch();
        }
        
        
    /* Récupère la liste des coureurs de l'équipe passée en paramètre
     * paramètre : nom d'une équipe
     * résultat : liste (table) de coureurs. Chauqe élément est une table associative (clés 'nom' et 'dossard')
     *   ou false si l'équipe n'existe pas
     *
    function getMembers($equipe){
        $sql = <<<EOD
       select
          nom, dossard
          from coureurs
          where equipe = :equipe
          order by dossard
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':equipe', $equipe);
        $stmt->execute();
        $res = [];
        while ($coureur = $stmt->fetch()) {
            $res[]= $coureur;
        }
        return $res;
        // ou return $stmt->fetchAll();
    }
    
    
    /* Récupère la liste des équipes
     * résultat : liste (table) d'équipes'. Chauqe élément est une table associative
     * (clés : ensemble des attributs de la table, dont 'nom', couleur' et 'directeur')
     *
    function getEquipes(){
        $sql =  "select * from equipes";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    
   /* Récupère la liste de étapes
     * résultat : liste (table) des étapes
     *
    function getEtapes(){
        $sql =  "select * from etapes order by numero";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* Récupère la liste des coureurs
     * résultat : liste (table) des coureurs
     *
    function getCoureurs($equipe = NULL){
        $sql  = "select * from coureurs";
        if (!is_null($equipe))
          $sql .= " where equipe=:equipe";
        $sql .= " order by dossard";
        $stmt = $this->connexion->prepare($sql);
        if (!is_null($equipe))
          $stmt->bindValue(":equipe",$equipe);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* Ajoute une étape
     * argument : nom de l'étape
     * résultat : booléen indiquant si l'opération s'est bien déroulée
     *
    function addEtape($nom){
        $sql =  "insert into etapes(nom) values (:nom)";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':nom',$nom);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

    /* Enregistre un chrono
     * arguments : étape (numero), dossard, temps
     * résultat : booléen indiquant si l'opération s'est bien déroulée
     *
    function addChrono($etape, $dossard, $chrono = NULL){
      $sql = <<<EOD
       insert into
         releves (etape, dossard, chrono)
         values (:etape,:dossard,:chrono)
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':etape',$etape);
        $stmt->bindValue(':dossard',$dossard);
        $stmt->bindValue(':chrono',$chrono);
        $stmt->execute();
        return $stmt->rowCount() == 1;
   }

    /* Efface tous les relevés
     *
    function resetReleves(){
        $stmt = $this->connexion->query("truncate table releves");
    }

    /* Efface toutes les étapes
     *
    function resetEtapes(){
      $stmt = $this->connexion->query("truncate table etapes restart identity cascade");
    }

    function getStats(){
      $avecNomV2 = <<<EOD
       select nom as "étape", etape as "numéro", min(chrono) as "temps mini",  max(chrono) as "temps maxi",  avg(chrono) as "temps moyen", count(*) as "coureurs au départ", count(chrono) as "coureurs arrivés"
         from releves
         join etapes on etape = etapes.numero
         group by etape, nom
         order by etape
EOD;

        $stmt = $this->connexion->prepare($avecNomV2);
        $stmt->execute();
        return $stmt->fetchAll();

    }
    
    function getArrivee($etape){
      $sql = <<<EOD
select coureurs.dossard, coureurs.nom as coureur, releves.chrono, equipes.nom as équipe , equipes.couleur as maillot 
 from coureurs
 join releves on releves.dossard = coureurs.dossard
 join equipes on equipes.nom = coureurs.equipe  
 where etape = :etape and chrono is not null
 order by chrono
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':etape',$etape);
      $stmt->execute();
      return $stmt->fetchAll();
    }


*/
  }
?>
