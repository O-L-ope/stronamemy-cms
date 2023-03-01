<?php
class Post {
    private string $title;
    private string $imageUrl;
    private string $timeStamp;

    function __construct(string $title, string $imageUrl, string $timeStamp)
    {
        $this->title = $title;
        $this->imageUrl = $imageUrl;
        $this->timeStamp = $timeStamp;
    }
    
    static function get(int $id) : Post {
        global $db;
        $query = $db->prepare("SELECT * FROM post WHERE id = ?");
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        $resultArray = $result->fetch_assoc();
        return new Post($resultArray['title'], $resultArray['filename'], $resultArray['timestamp']);
    }

    static function getPage(int $pageNumber = 1, int $postsPerPage = 10){
        global $db;
        $query = $db->prepare("SELECT * FROM post LIMIT 10 OFFSET");
        $offset = ($pageNumber-1) * $postsPerPage;
        $query->bind_param('ii', $postsPerPage, $offset);
        $query->execute();
        $result = $query->get_result();
        $postsArray = array();
        while($row - $result->fetch_assoc()) {
            $post = new Post($row['title'],
                             $row['filename'], 
                             $row['timestamp']);
            array_push($postsArray, $post);
        }
        return $postsArray;
    }

    static function upload(string $tempFilename, string $title = ""){
        $uploadDir = "img/";
        $imgInfo = getimagesize($tempFilename);
        if(!is_array($imgInfo)){
            die("BŁĄD: Przekazany plik nie jest obrazem");
        }
    $randomSeed = rand(10000, 99999) . hrtime(true);
    $hash = hash("sha256", $randomSeed);
    $targetFileName = $hash . ".webp";
    if(file_exists($targetFileName)){
        die("BŁĄD: podany plik już istnieje");
    }
    $imageString = file_get_contents($tempFilename);
    $gdImage = @imagecreatefromstring($imageString);
    imagewebp($gdImage, $targetFileName);

    //odwołanie do globalnego połączenia
    global $db;

    $query = $db->prepare("INSERT INTO post VALUES(NULL, ?, ?, ?)");
    $dbTimeStamp = date("Y-m-d H:i:s");
    $query->bind_param("ss", $dbTimeStamp, $targetFileName, $title);
    if(!$query->execute())
        die("Błąd zapisu");
    }
}

?>