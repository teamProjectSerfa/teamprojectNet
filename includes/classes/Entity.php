<?php
class Entity {

    private $con, $sqlData;

    public function __construct($con, $input) {
        $this->con = $con;

        if(is_array($input)) {
            $this->sqlData = $input;
        }
        else {
            $query = $this->con->prepare("SELECT * FROM entities WHERE id=:id");
            $query->bindValue(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function getId() {
        return $this->sqlData["id"];
    }

    public function getName() {
        return $this->sqlData["name"];
    }

    public function getThumbnail() {
        return $this->sqlData["thumbnail"];
    }

    public function getPreview() {
        return $this->sqlData["preview"];
    }

    public function getCategoryId() {
        return $this->sqlData["categoryId"];
    }

    public function getSeasons() {
        // requete pour reccuperer les données de la BDD
        // traitement des données afficher par saison d'abord et episode
        // pcq'on peut avoir plusier episode dans une saison
        $query = $this->con->prepare("SELECT * FROM videos WHERE entityId=:id
                                    AND isMovie=0 ORDER BY season, episode ASC");
        $query->bindValue(":id", $this->getId());
        $query->execute();

        $seasons = array();
        $videos = array();
        //  initialiser le debut à null pour commancer la saison
        $currentSeason = null;
        // nous bouclons sur chaque ligne renvoyée par cette requête
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            
            // si la saison courrante est pas null c'est à dire deja commencé
            // et la saison courrante est differente de la saison dans le tableau
            if($currentSeason != null && $currentSeason != $row["season"]) {
                $seasons[] = new Season($currentSeason, $videos);
                $videos = array();
            }

            // on envoie la saison en cours
            $currentSeason = $row["season"];
            // envoie de la video en cours dans la saison
            $videos[] = new Video($this->con, $row);

        }


        // la toute dernière chose à faire est de gérer le cas où 
        // nous arrivons à la fin de notre boucle car nous sommes sortis
        if(sizeof($videos) != 0) {
            $seasons[] = new Season($currentSeason, $videos);
        }

        return $seasons;
    }

}
?>