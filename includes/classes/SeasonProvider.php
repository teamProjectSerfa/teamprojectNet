<?php
class SeasonProvider {
    private $con, $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }

    public function create($entity) {
        $seasons = $entity->getSeasons();
        
        // conditions pour traiter les données au cas ou si la video il fait pas partie d'une série
        if(sizeof($seasons) == 0) {
            return;
        }

        $seasonsHtml = "";

        // recuperer le nombre des episodes de chaque série
        foreach($seasons as $season) {
            $seasonNumber = $season->getSeasonNumber();

            $videosHtml = "";
            foreach($season->getVideos() as $video) {
                $videosHtml .= $this->createVideoSquare($video);
            }


            $seasonsHtml .= "<div class='season'>
                                    <h3>Season $seasonNumber</h3>
                                    <div class='videos'>
                                        $videosHtml
                                    </div>
                                </div>";
        }

        return $seasonsHtml;
    }

    //  crée une fonction pour afficheer les videos et cette fonction prend un paramettre $video
    private function createVideoSquare($video) {
        $id = $video->getId();
        $thumbnail = $video->getThumbnail();
        $name = $video->getTitle();
        $description = $video->getDescription();
        $episodeNumber = $video->getEpisodeNumber();

        return "<a href='watch.php?id=$id'>
                    <div class='episodeContainer'>
                        <div class='contents'>

                            <img src='$thumbnail'>

                            <div class='videoInfo'>
                                <h4>$episodeNumber. $name</h4>
                                <span>$description</span>
                            </div>

                        </div>
                    </div>
                </a>";
    }
}
?>