<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Rolador de Dados</title>
		<link href="assets/lib/dist/css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="assets/css/style.css?v=1">
            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    	<script src="https://ajax.googleapis.com/ajax/lib/jquery/1.11.1/jquery.min.js"></script>
        <script src="assets/lib/dist/js/bootstrap.min.js"></script>
</head>
<body>

	<div class="main">

		<div class="container">
			<h2>Nola OcC</h2>
			<form class="form-signin" action="index.php" name="form_rolador" method="POST">
			    <div class="col-sm-12">
					<div class="panel panel-primary">
			            <div class="panel-heading">
			            	<h3 class="panel-title">Rolador d-10</h3>
			            </div>
			            <div class="panel-body">	  

						    <input class="form-control input1" type="text" placeholder="Nome" name="nome">
						    <input class="form-control input2" type="text" placeholder="Numero de Dados" name="dados" required>
						    <input class="form-control input2" type="text" placeholder="Dificuldade (6 padrão)" name="dif">
						    <input class="form-control input2" type="text" placeholder="Motivo da Ação" name="motivo">
						    <input class="btn btn-lg btn-primary btn-block input2" type="submit" value="Rolar" />
							<input class="btn btn-lg btn-primary btn-block input3"type="button" onclick="location.href='index.php'" value="Verificar novos resultados">

						      <div class="checkbox">
							    <label>
							      <input type="checkbox" name="fv"> Uso de Força de Vontade
							    </label>
							  </div>
				  		</div>
			        </div>
		        </div>
			</form>

			<div class="clearfix"></div>


			<div class="col-sm-12">
				<?php 
				$servername = "localhost";
				$dbname = "rolador";
				$username = "root";
				$password = "2615948";

				// // Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);
				// Check connection
				if ($conn->connect_error) {
				    die("Connection failed: " . $conn->connect_error);
				} 
				if ($_POST['dados']):
					//TRATANDO A DIFICULDADE PADRÃO.
					if (!$_POST['dif']):
						$_POST['dif'] = 6;
					endif;
					//TRATANDO A DIFICULDADE PADRÃO.
					if (!$_POST['nome']):
						$_POST['nome'] = "Jogador";
					endif;
					$sucesso = 0;
					$descricao = '';

						 for ($i=1; $i <= $_POST['dados']; $i++):

						 	$jogada[$i] = rand(1, 10);


						  	if ($jogada[$i] >( $_POST['dif'] - 1 )):
						  		if ($jogada[$i] == 10 ):
						  		$sucesso = $sucesso + 2;
						  		else:
						  		$sucesso = $sucesso + 1;
						  		endif;
							elseif ($jogada[$i]== 1):
							 	$sucesso = $sucesso - 1;
							endif;


						 	if ($i == 1):
						 		$descricao .= "$jogada[$i]";
						 	elseif ($i == $_POST['dados']):
						 		$descricao .= " e $jogada[$i]";
						 	else:
						 		$descricao .= ", $jogada[$i]";	
							endif;



						 endfor;



	


				    	if ($_POST['fv'] == true) {
						 	$usaFv = 1;
						 }


				$dadosRolados = $i-1;
				 $sql = "INSERT INTO Dados (id, nome, dif, numero, descricao, sucessos, fv, status, motivo) 
				 VALUES ('', '".$_POST['nome']."', '".$_POST['dif']."', '$dadosRolados', '$descricao','$sucesso', '$usaFv', '$status', '".$_POST['motivo']."')";

				 if ($conn->query($sql) === TRUE) {
				     echo "Rolado com sucesso.";
				 } else {
				     echo "Error: " . $sql . "<br>" . $conn->error;
				 }
			endif;

				$sql = "SELECT * FROM Dados ORDER BY id DESC";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
				    // output data of each row
				    while($row = $result->fetch_assoc()) {
				    	$usaFv = '';
				    	if ($row['fv'] == 1) {
						 	$usaFv = '<strong>(com gasto de força de vontade</strong>)';
						 }
				      //  echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

					if ($row["sucessos"] > 0):
						$status = "success";
					elseif($row["sucessos"] == 0):
						$status = "warning";
					elseif($row["sucessos"] < 0):
						$status = "danger";
					endif; 
				?>

				<div class="alert alert-<?php echo @$status; ?>"  role="alert">
				<strong><?php echo $row["nome"]; ?> rolou <?php echo $row["numero"]; ?> dados:</strong> <?php echo $row["descricao"]; ?> - Você teve uma rolagem com <strong><?php echo $row["sucessos"]; ?></strong> sucessos,  dificuldade <strong><?php echo $row["dif"]; ?></strong>. <strong>Motivo:</strong> <?php echo $row["motivo"]; ?> <?php echo $usaFv; ?>
				</div>
				<?php
				    }
				} else {
				    echo "0 results";
				}
				$conn->close();
					
				?>		 	




				




			</div>
		</div>
	</div>
</body>
</html> 