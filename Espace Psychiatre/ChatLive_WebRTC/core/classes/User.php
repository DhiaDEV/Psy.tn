<?php
namespace MyApp;
use PDO ;
    class User{
        public $db , $userID , $sessionID;

        public function __construct()
        {
            $db = new \MyApp\DB;
            $this->db = $db->connect();
            $this->userID = $this->ID();
            $this->sessionID = $this->getSessionID();

        }

        public function ID(){
            if($this->isLoggedIn()){
                return $_SESSION['userID'];
            }
        }

        public function getSessionID(){
            return session_id();
        }

        // public function emailExist($email){
        //     $stmt = $this->db->prepare("SELECT * FROM compte_utilisateurs WHERE Email = :Email");
        //     $stmt->bindParam(":Email", $email, PDO::PARAM_STR);
        //     $stmt->execute();
        //     $user = $stmt->fetch(PDO::FETCH_OBJ);

        //     if(!empty($user)){
        //         return $user ;
        //     }else{
        //         return false;
        //     }

        // }
        // public function hash ($password){
        //     return password_hash($password, PASSWORD_DEFAULT);
        // }
        public function redirect($location){
            header("Location:".BASE_URL.$location);
        }
        public function userData($userID= ''){
            $userID = ((!empty($userID)) ? $userID : $this->userID);
            $stmt = $this->db->prepare("SELECT * FROM compte_utilisateurs WHERE userID = :userID");
            $stmt->bindParam(":userID", $userID, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }

        public function isLoggedIn(){
            return ((isset($_SESSION['userID'])) ? true : false);
        }

        public function logout(){
            $_SESSION = array();
            session_destroy();
            session_regenerate_id();
            $header= header('Location: index.php');
            $this->$header;
        }
    
        public function getUser($search_query = ''){
            $today = date('Y-m-d');
            $sql = "SELECT u.* FROM compte_utilisateurs u JOIN rendez_vous r ON u.userID = r.IDPatient WHERE r.IDPsy = :IDPsy AND Confirmer=1 AND r.Date = :today";
            
            if(!empty($search_query)){
                $search_params = preg_split("/[\s,]+/", $search_query);
                $sql .= " AND (";
                foreach($search_params as $i => $param){
                    if($i > 0){
                        $sql .= " OR ";
                    }
                    $sql .= "u.Nom LIKE :param{$i} OR u.Prenom LIKE :param{$i}";
                }
                $sql .= ")";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":IDPsy" , $this->userID , PDO::PARAM_INT);
            $stmt->bindParam(":today" , $today , PDO::PARAM_STR);
            
            if(!empty($search_query)){
                foreach($search_params as $i => $param){
                    $search_param = '%'.$param.'%';
                    $stmt->bindParam(":param{$i}", $search_param);
                }
            }
            
            $stmt->execute();
            $patients= $stmt->fetchAll(PDO::FETCH_OBJ);
            
            foreach($patients as $user){

            echo'<li class="select-none transition hover:bg-green-50 p-4 cursor-pointer select-none">
            <a href="'.BASE_URL.$user->username.'">
                <div class="user-box flex items-center flex-wrap">
                <div class="flex-shrink-0 user-img w-14 h-14 rounded-full border overflow-hidden">
                <img class="w-full h-full" src="\Psy.tn\Espace Patients\img/'.$user->PhotoProfile. '">
                </div>
                <div class="user-name ml-2">
                    <div><span class="flex font-medium">'.$user->Prenom . ' ' . $user->Nom.'</span></div>
                    <div></div>
                </div>
                </div>
            </a>
        </li>';
        }
    }
    public function getUserByUsername($username){
        $stmt = $this->db->prepare("SELECT * FROM compte_utilisateurs WHERE username = :username");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateSession(){
        $stmt = $this->db->prepare("UPDATE compte_utilisateurs SET sessionID = :sessionID WHERE userID = :userID");
        $stmt->bindParam(":sessionID", $this->sessionID , PDO::PARAM_STR);
        $stmt->bindParam(":userID", $this->userID , PDO::PARAM_INT);
        $stmt->execute();
    }
    public function getUserBySession($sessionID){
        $stmt = $this->db->prepare("SELECT * FROM compte_utilisateurs WHERE sessionID = :sessionID");
        $stmt->bindParam(":sessionID", $sessionID, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);

    }
 
    public function updateConnection($connectionID , $userID){
        $stmt = $this->db->prepare("UPDATE compte_utilisateurs SET connectionID = :connectionID WHERE userID = :userID");
        $stmt->bindParam(":connectionID", $connectionID , PDO::PARAM_STR);
        $stmt->bindParam(":userID", $userID , PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>