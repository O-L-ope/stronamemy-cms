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
    
    public function getTitle(){
        return $this->title;
    }
    public function getTimestamp(){
        return $this->timeStamp;
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

    public function getImageUrl() : string{
        return $this->imageUrl;
    }

    static function getLast() : Post {
        global $db;
        $query = $db->prepare("SELECT * FROM post ORDER BY timestamp DESC LIMIT 1");
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $p = new Post($row['id'], $row['filename'], $row['timestamp']);
        return $p; 

    }
    
    static function getPage(int $pageNumber = 1, int $postsPerPage = 10){
        global $db;
        $query = $db->prepare("SELECT * FROM post LIMIT ? OFFSET ?");
        $offset = ($pageNumber-1) * $postsPerPage;
        $query->bind_param('ii', $postsPerPage, $offset);
        $query->execute();
        $result = $query->get_result();
        //$row = $result->fetch_assoc();
        $postsArray = array();
        while($row = $result->fetch_array()) {
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
    $targetFileName =  $uploadDir .$hash . ".webp";
    if(file_exists($targetFileName)){
        die("BŁĄD: podany plik już istnieje");
    }
    $imageString = file_get_contents($tempFilename);
    $gdImage = @imagecreatefromstring($imageString);
    imagewebp($gdImage, $targetFileName);

    //odwołanie do globalnego połączenia
    global $db;

    //$query = $db->prepare("INSERT INTO post VALUES(NULL, ?, ?, ?)");
    $query = $db->prepare("INSERT post (id, timestamp, filename, title) VALUES (NULL, ?, ?, ?)");
    $dbTimeStamp = date("Y-m-d H:i:s");
    $query->bind_param("sss", $dbTimeStamp, $targetFileName, $title);
    if(!$query->execute())
        die("Błąd zapisu");
    }
}

?>