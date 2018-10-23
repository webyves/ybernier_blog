<?php
/***************************************************************** 
file: Manager.php 
Model file
Mother Class for manager class
******************************************************************/
namespace yBernier\Blog\model\manager;

Class Manager {
    
    protected function dbConnect()
    {
        $dbUser = "root";
        $dbUserPwd = "";
        $dbHost = "localhost";
        $dbName = "ybernier_blog";
        
        // $dbUser = "ybernierog83";
        // $dbUserPwd = "kvZ13dlC";
        // $dbHost = "ybernierog83.mysql.db";
        // $dbName = "ybernierog83";

        $db = new \PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbUserPwd);
        return $db;
    }
    
    public function sendMail($tabInfo)
    {
        $fromName = htmlentities($tabInfo['fromFirstname']) . " " . htmlentities($tabInfo['fromLastname']);
        $fromEmail = $tabInfo['fromEmail'];
        $to = htmlentities($tabInfo['toEmail']);
        $messageTxt = nl2br(htmlentities($tabInfo['messageTxt']));
        if (empty($tabInfo['messageHtml'])) {
            $messageHtml = $messageTxt;
        } else {
            $messageHtml = nl2br($tabInfo['messageHtml']); // ATTENTION SECURITY
        }
        if (empty($tabInfo['subject'])) {
            $subject = "Message depuis le formulaire de contact yBernier Blog";
        } else {
            $subject = $tabInfo['subject']; // ATTENTION SECURITY
        }
        $boundary = "-----=".md5(rand());
        
        if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $to)) {
            $ligneStop = "\r\n";
        } else {
            $ligneStop = "\n";
        }

        $header = "From: \"$fromName\"<$fromEmail>".$ligneStop;
        $header .= "Reply-to: \"$fromName\" <$fromEmail>".$ligneStop;
        $header .= "MIME-Version: 1.0".$ligneStop;
        $header .= "Content-Type: multipart/alternative;".$ligneStop." boundary=\"$boundary\"".$ligneStop;

        $message = $ligneStop."--".$boundary.$ligneStop;
        $message.= "Content-Type: text/plain; charset=\"utf-8\"".$ligneStop;
        $message.= "Content-Transfer-Encoding: 8bit".$ligneStop;
        $message.= $ligneStop.$messageTxt.$ligneStop;
        $message.= $ligneStop."--".$boundary.$ligneStop;
        $message .= "Content-Type: text/html; charset=\"utf-8\"".$ligneStop;
        $message .= "Content-Transfer-Encoding: 8bit".$ligneStop;
        $message.= $ligneStop.$messageHtml.$ligneStop;
        $message.= $ligneStop."--".$boundary."--".$ligneStop;
        $message.= $ligneStop."--".$boundary."--".$ligneStop;

        mail($to,$subject,$message,$header);
    }    
    

}