<?php
function login($user,$pass){
    echo($user);
    include_once('connection.php');
    $logindata = $conn->prepare ("SELECT * FROM Libusers WHERE username=:user ;");
    $logindata->bindParam(":user",$user);
    $logindata->execute();
    if($logindata->rowCount()==0){
            $_SESSION['message']=('username not recognised');
            header('location: login.php');}
    else{
        while ($row = $logindata->fetch(PDO::FETCH_ASSOC)){
            echo('hi');
            if(password_verify($pass,$row['Password'])){
                echo('login successful');
                header('Location: portal.php');}
            else{
                $_SESSION['message']=('login failed');
                header('Location: login.php');}
        }}

}

function Newuser($username,$passwd,$forename,$surname,$email,$Perms){
    include_once('connection.php');
    $newuser = $conn->prepare("INSERT INTO Libusers (username, passwd, forename, surname, Perms, email)
    VALUES (:username,:pass,:forename, :surname, :perms, :email);");
    $newuser->bindParam(':username',$username);
    $newuser->bindParam(':pass',$passwd);
    $newuser->bindParam(':forename',$forename);
    $newuser->bindParam(':surname',$surname);
    $newuser->bindParam(':perms',$Perms);
    $newuser->bindParam(':email',$email);
    $newuser-> execute();
    $newuser-> closeCursor();

    echo('new user proflie created successfully');

}


function DBinstall($confirmation,$testdata){
    include_once('connection.php');
    if ($confirmation == 'confirmation'){
        $Initialisation = $conn->prepare("
            DROP TABLE IF EXISTS Libusers;
            DROP TABLE IF EXISTS LoanedBooks;
            DROP TABLE IF EXISTS bookcatalogue;
            DROP TABLE IF EXISTS categorycode;

            CREATE Table Libusers(
                PersonID INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(20) NOT NULL,
                passwd VARCHAR(20) NOT NULL,
                forename VARCHAR(20) NOT NULL,
                surname VARCHAR(20) NOT NULL,
                perms INT(1) NOT NULL,
                email VARCHAR(20),
                strikes INT(1));

            CREATE TABLE LoanedBooks(
                PersonID INT(8),
                BookNo VARCHAR(8),
                DueDate DATE,
                BookStatus bit);

            CREATE TABLE bookcatalogue(
                bookno INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(20) NOT NULL,
                ISBN VARCHAR(13) NOT NULL,
                author VARCHAR(20) NOT NULL,
                Copies INT(2),
                category INT(2) NOT NULL);
            
            CREATE TABLE categorycode(
                categoryNo INT(2) UNSIGNED PRIMARY KEY,
                categoryName VARCHAR(20) NOT NULL);
            ");
        $Initialisation->execute();
        $Initialisation->closeCursor();
        echo("database initialised successfully");
            }
        if ($testdata=='true'){
            $userdata= $conn->prepare ("INSERT INTO Libusers   (username,passwd,forename,surname,perms,email,strikes)
                                VALUES          ('david123','1234','David','Lees',1,'davidlees@gmail.com',0),
                                                ('sneed178','1234','Ned','Sekula',3,'sneed178@gmail.com',0),
                                                ('librarian','1234','libby','Ravenhill',2,'libby@gmail.com',0)");
            $bookdata = $conn->prepare("INSERT INTO bookcatalogue  (title,ISBN,author,category)
                        VALUES                      ('foundation','9780008117498','Asimov',1),
                                                    ('second foundation','9780008117498','Asimov',1),
                                                    ('foundation and empire','9780345309006','Asimov',1)");
            $category =$conn->prepare ("INSERT INTO categorycode (categoryNo, categoryName)
                                        VALUES      (1,'scifi')");
            $userdata->execute();
            $userdata->closeCursor();
            $bookdata->execute();
            $bookdata->closeCursor();
            $category->execute();
            $category->closeCursor();
            echo("Database reebooted succesfully. Testdata has been added.");
        }
    else{
        echo("Confirmation not recieved.");
    }
}

function search($keyphrase,$kptype){
    include_once('connection.php');
    $keyphrase .= '%';
    $search = $conn->prepare('SELECT * FROM bookcatalogue WHERE :kptype like :keyphrase');
    $search->bindParam(':kptype',$kptype);
    $search->bindParam(':keyphrase',$keyphrase);

}

function countrows(){
    include_once('connection.php');
    $countrows = $conn->prepare('SELECT COUNT(*) FROM BookCatalogue');
    $_SESSION['totalcount']=$countrows->execute();
    echo($_SESSION['totalcount']);
    if ($_SESSION['totalcount']>=30){
        $_SESSION['limit']=$_SESSION['totalcount'];
    }
    else{
    $_SESSION['limit']='30';
    }
}

<<<<<<< Updated upstream

function imgsource($imgtitle){
    $imgtitle = str_replace(' ','-',$imgtitle);
    $temppath="images\\".$imgtitle.".jpg";
    if(file_exists($temppath)){
        return $temppath;
=======
function loadbookdata(){
    include_once('connection.php');
    $fetchcommand = $conn->prepare("SELECT * FROM bookcatalogue");
    $result=$fetchcommand->execute();

    $output=[];
    if ($result->num_rows > 0){
        while ($row=$result-> fetch_assoc()){
            $temp=array();
            echo $temp;
        }
    }
    }
    echo $output;
    $fetchcommand-> closeCursor();
    return($output);
}
function findimg($imgtitle){
    $imgpath=($_SESSION['imgsrcpath'].$imgtitle.'.jpg');
    if (file_exists($imgopath)){}
    else{
        $imgpath="C:\xampp\htdocs\Library-System\Images\noimage.jpg";
>>>>>>> Stashed changes
    }
    else{
        return("images\\noimage.jpg");
    };
}
?>