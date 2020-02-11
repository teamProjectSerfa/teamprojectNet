<?php
class CategoryContainers {

    private $con, $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }

    public function showAllCategories() {
        // requete pour afficher tout les categories
        $query = $this->con->prepare("SELECT * FROM categories");
        // executer la requete
        $query->execute();
        // on crée une variable html on on peut afficher les donnes des categories 
        $html = "<div class='previewCategories'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, true, true);
        }
        //  on retourne les données recuperer plus une balise fermante de la div car on l a pas fermé avant
        return $html . "</div>";
    }
    
    public function showTVShowCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                    <h1>TV Shows</h1>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, true, false);
        }

        return $html . "</div>";
    }

    public function showMovieCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                    <h1>Movies</h1>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, false, true);
        }

        return $html . "</div>";
    }
    // fonction pour afficher les simulaires des films ou des séries de méme genre
    public function showCategory($categoryId, $title = null) {
        $query = $this->con->prepare("SELECT * FROM categories WHERE id=:id");
        $query->bindValue(":id", $categoryId);
        $query->execute();
        
        // on crée une variable html ou on peut afficher les donnes des categories 
        $html = "<div class='previewCategories noScroll'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, $title, true, true);
        }
        //  on retourne les données recuperer plus une balise fermante de la div car on l a pas fermé avant
        return $html . "</div>";
    }

    private function getCategoryHtml($sqlData, $title, $tvShows, $movies) {
        $categoryId = $sqlData["id"];

        // on définis les données de titre pour savoir si cela est vrai 
        // s'il n'y a pas de valeur utilise la valeur de "name" comme valeur
        // autrement dit "si aucun titre n'est transmis, utilisez le nom dans la catégorie"
        $title = $title == null ? $sqlData["name"] : $title;

        if($tvShows && $movies) {
            $entities = EntityProvider::getEntities($this->con, $categoryId, 30);
        }
        else if($tvShows) {
            // obtenir des entités de spectacle
            $entities = EntityProvider::getTVShowEntities($this->con, $categoryId, 30);
        }
        else {
            // obtenir des entités de films
            $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 30);
        }

        if(sizeof($entities) == 0) {
            return;
        }

        $entitiesHtml = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);

        // cette boucle intègre l'élément actuel sera dans cette entité variable
        foreach($entities as $entity) {
            // cet objet entité ici est un objet de type entité qui est qu'en plus
            //  nous avons écrit une entité comme celle-ci
            $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
        }
        // return $entitiesHtml . "<br>";   
        return "<div class='category'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>

                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
    }

}
?>