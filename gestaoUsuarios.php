<?php

session_start(); // Inicia uma sessão na página

require_once "Backend/config/Database.php";
require_once "Backend/dao/UsuarioDAO.php";
require_once "Backend/entity/Usuario.php";

$usuarioDAO = new UsuarioDAO();
$is_gestor = isset($_SESSION['token']) ? $usuarioDAO->isGestor($_SESSION['token']) : false;

if (!$is_gestor) {
    header("Location: index.php");
    exit();
}

$usuarioDAO = new UsuarioDAO();
$is_gestor = isset($_SESSION['token']) ? $usuarioDAO->isGestor($_SESSION['token']) : false;


$usuarioDAO = new UsuarioDAO();
$usuarios = $usuarioDAO->getAll();

?>

<?php
    require_once "Frontend/template/header.php";
?>
<br>
<div class="container">
    <h1>Gestão de Usuários</h1>
    
    <a href="login.php" class="btn btn-primary">Adicionar Novo Usuário</a>

    <br><br>
    <div class="table-responsive">
    <table class="table align-middle mb-0 bg-white">
  <thead class="bg-light">
    <tr>
      <th>Name</th>
      <th>Status</th>
      <th>Criado</th>
      <th>Atualizado</th>  
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($usuarios as $usuario) :?>
    <tr>
      <td>
        <div class="d-flex align-items-center">
          <div style="width: 40px; height: 40px; border-radius:50%; background: #efefef; display:flex; justify-content: center; align-items:center">
            <i class="fa-solid fa-user"></i>
          </div>
          <div class="ms-3">
            <p class="fw-bold mb-1"><?php echo $usuario->getNome(); ?></p>
            <p class="text-muted mb-0"><?php echo $usuario->getEmail(); ?></p>
          </div>
        </div>
      </td>
      <td>
        <span class="badge badge-success rounded-pill d-inline">Active</span>
      </td>
      <td>
        <div class="d-flex align-items-center">
        <i class="fa-regular fa-calendar"></i>
          <div class="ms-3">
            <p class="fw-bold mb-1"><?php echo $usuario->getCriado(); ?></p>
          </div>
        </div>
      </td>
      <td>
        <div class="d-flex align-items-center">
        <i class="fa-regular fa-calendar"></i>
          <div class="ms-3">
            <p class="fw-bold mb-1"><?php echo $usuario->getAtualizado() ? $usuario->getAtualizado() : "- -" ; ?></p>
          </div>
        </div>
      </td>
      <td>
        <a href="editUsers.php?usuario_id=<?php echo $usuario->getId(); ?>" class="btn btn-primary"><i class="fa-solid fa-pen"></i></a>
      </td>
    </tr>
    

    <?php endforeach; ?>
  </tbody>
</table>

</div>


</div>




<?php
    require_once "Frontend/template/footer.php";
?>