<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require "config.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];

$corpo = json_decode(file_get_contents("php://input"), true) ?? [];

function teste(){
    echo json_encode(["mensagem" => "Back-end do instagram respondendo!"]);
    exit;
}
//Usuário
function createUsuario($con, $dados){
    $stmt = $con->prepare("INSERT INTO Usuarios (nome, username, email, senha) VALUES (?, ?, ?, ?)");
    $stmt->execute([$dados["nome"], $dados["username"], $dados["email"], $dados["senha"]]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Usuário criado com sucesso!", "id" => $con->lastInsertId()]);
}
function readUsuario($con){
    $stmt = $con->query("SELECT * FROM Usuarios");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
function updateUsuario($con, $id, $dados){
    $stmt = $con->prepare("UPDATE Usuarios SET username = ? WHERE id_usuario = ?");
    $stmt->execute([$dados["username"], $id]);
    echo json_encode(["mensagem" => "Usuário atualizado com sucesso!"]);
}
function deleteUsuario($con, $id){
    $stmt = $con->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Usuário excluído com sucesso!"]);
}
//Publicações
function createPubli($con, $dados){
    $stmt = $con->prepare("INSERT INTO Publicacoes (id_usuario, legenda, imagem_url) VALUES (?, ?, ?)");
    $stmt->execute([$dados["id_usuario"], $dados["legenda"], $dados["imagem_url"]]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Publicação criada com sucesso!", "id" => $con->lastInsertId()]);
}
function readPubli($con){
    $stmt = $con->query("SELECT * FROM Publicacoes");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
function updatePubli($con, $id, $dados){
    $stmt = $con->prepare("UPDATE Publicacoes SET legenda = ? WHERE id_publicacao = ?");
    $stmt->execute([$dados["legenda"], $id]);
    echo json_encode(["mensagem" => "Publicação atualizada com sucesso!"]);
}
function deletePubli($con, $id){
    $stmt = $con->prepare("DELETE FROM Curtidas WHERE id_publicacao = ?");
    $stmt->execute([$id]);
    $stmt = $con->prepare("DELETE FROM Publicacoes WHERE id_publicacao = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Publicação excluída com sucesso!"]);
}
//Curtidas
function createCurtida($con, $dados){
    $stmt = $con->prepare("INSERT INTO Curtidas (id_usuario, id_publicacao) VALUES (?, ?)");
    $stmt->execute([$dados["id_usuario"], $dados["id_publicacao"]]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Curtida criada com sucesso!", "id" => $con->lastInsertId()]);
}
function readCurtida($con){
    $stmt = $con->query("SELECT * FROM Curtidas");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
function updateCurtida($con, $id, $dados){
    $stmt = $con->prepare("UPDATE Curtidas SET id_publicacao = ? WHERE id_curtida = ?");
    $stmt->execute([$dados["id_publicacao"], $id]);
    echo json_encode(["mensagem" => "Curtida atualizada com sucesso!"]);
}
function deleteCurtida($con, $id){
    $stmt = $con->prepare("DELETE FROM Curtidas WHERE id_curtida = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Curtida excluída com sucesso!"]);
}
//Rotas
if ($rota == "usuario"){
    switch($metodo){
        case "GET":
            readUsuario($con);
            break;
        case "POST":
            createUsuario($con, $corpo);
            break;
        case "PUT":
            updateUsuario($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteUsuario($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}elseif ($rota == "publicacao"){
    switch($metodo){
        case "GET":
            readPubli($con);
            break;
        case "POST":
            createPubli($con, $corpo);
            break;
        case "PUT":
            updatePubli($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deletePubli($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}elseif ($rota == "curtida"){
    switch($metodo){
        case "GET":
            readCurtida($con);
            break;
        case "POST":
            createCurtida($con, $corpo);
            break;
        case "PUT":
            updateCurtida($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteCurtida($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}
