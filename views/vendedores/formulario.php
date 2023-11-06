<fieldset>
  <legend>Informacion General</legend>

  <label for="nombre">Nombre</label>
  <input type="text" id="titulo" name="vendedor[nombre]" placeholder="Nombre Vendedor (a)" 
  value="<?php echo s($vendedor->nombre); ?>">

  <label for="apellido">Apellido</label>
  <input type="text" id="titulo" name="vendedor[apellido]" placeholder="Apellido Vendedor (a)" 
  value="<?php echo s($vendedor->apellido); ?>">

</fieldset>

<fieldset>
    <legend>Informacion Extra</legend>

    <label for="telefono">Telefono</label>
    <input type="text" id="titulo" name="vendedor[telefonol]" placeholder="Telefono Vendedor (a)" 
    value="<?php echo s($vendedor->telefonol); ?>">


</fieldset>