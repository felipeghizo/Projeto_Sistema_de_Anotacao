<?php
    define('HOST','localhost');	# Host.
	define('DB','anotacoes');	# Nome do Data Base.
	define('USER','root');		
	define('PASS','');		# Senha (passada no segundo parâmetro).
	try{
		$pdo = new PDO('mysql:host='.HOST.';dbname='.DB, USER, PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	// Modo de desenvolvimento.
	}catch(Exception $e){
		echo "<h1> erro crítico </h1>";			
	}			
    
    date_default_timezone_set('America/Sao_Paulo');
    $pdo = new pdo('mysql:host=localhost;dbname=anotacoes', 'root','');

    if(isset($_POST['add'])){
        $titulo = $_POST['Titulo'];
        $note = $_POST['Note'];
        $momento_registro = date('Y-m-d H:i:s');			
        $sql = $pdo->prepare("INSERT INTO `notes` VALUES (null, ?, ?, ?)");		# Por segurança usa_se '?'.
        $sql->execute(array($titulo, $note, $momento_registro));			# As posições dos elementos do vetor substituem os '?'.	
    }
    if(isset($_POST['remove_id'])){
        $remove_id = $_POST['remove_id'];			
        $sql = $pdo->prepare("DELETE FROM `notes` WHERE `id` = ?");		# Por segurança usa_se '?'.
        $sql->execute(array($remove_id));			# As posições dos elementos do vetor substituem os '?'.	
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Notes</title>
</head>
<body>
    <div class="w-50">
        <div class="notas">
            <h1>Suas Notas</h1>
            <div class="notas-tot">
                <?php  
                    $sql = $pdo->prepare("SELECT * FROM `notes`");	
                    $sql->execute();
                    $info = $sql->fetchAll();
                    $i = count($info);
                    $j = 0;
                    for($j; $j < $i; $j++){     
                ?>
                <form class="nota-single" method="POST">
                    <h2><?php print_r($info[$j][1]); ?></h2>
                    <p class="nota-main"><?php print_r($info[$j][2]); ?></p>
                    <p class="data"><?php print_r($info[$j][3]); ?></p>
                    <input type="submit" class="delete" name="remove_id" value="<?php $remove_id = $info[$j][0]; echo $remove_id;?>">
                    <i class="fa-regular fa-trash-can"></i>
                    
                </form>
                <?php };
                if($i==0){?>
                    <div class="void">
                        <h3>Não há notas aqui</h3>
                        <i class="fa-regular fa-face-frown"></i>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="w-50">
        <form class="container-note" method="POST">
            <textarea name="Titulo" placeholder="Título"></textarea>
            <textarea name="Note" placeholder="Minha anotação..."></textarea>
            <input type="submit" name="add" value="+"/>
        </form>
    </div>
<script src="https://kit.fontawesome.com/14f49105d9.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</body>
</html>