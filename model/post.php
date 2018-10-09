<?php
/***************************************************************** 
file: post.php 
model for post
******************************************************************/


/* get list of post */
function getPosts($nbPosts = 5, $idState = 1)
{
    // return table with title date and small content
    $db = dbConnect();
    $reqPostsList = 'SELECT 
                id_post, 
                title, 
                content, 
                DATE_FORMAT(date, \'%d/%m/%Y à %Hh%imin%ss\') as date_fr,
                image_top,
                id_cat,
                id_user
            FROM posts 
            WHERE id_state = :id_state 
            ORDER BY date DESC 
            LIMIT 0, :limit';
    $req = $db->prepare($reqPostsList);
    $req->bindValue('limit', $nbPosts, PDO::PARAM_INT);
    $req->bindValue('id_state', $idState, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetchall();
    return $res;
                
}

/* get content of post */
function getPost($idPost)
{
    // return full content 
}

/* Set posts information bloc of functions */
function setPostCat($idPost, $idCat)
{
    
}

function setPostImage($idPost, $image)
{
    
}

function setPostContent($idPost, $content)
{
    
}

function setPostState($idPost, $idState)
{
    
}

function setPostTitle($idPost, $title)
{
    
}

function setPostUser($idPost, $idUser)
{
    
}

function setPostDate($idPost)
{
    
}

