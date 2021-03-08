<div class="container bg-gray-200 mt-2 min-w-max w-3/4   mx-auto mb-2 p-2 shadow-md rounded-lg text-center">
    <div class=" bg-gray-100 flex-none w-2/3 mx-auto rounded-lg shadow-xl">
        <img class="w-20  p-2 bg-white mx-auto rounded-xl shadow-inner " src="img/<?php echo $foto = $user['Foto'];  ?>" alt="">
        <h1 class="text-center mb-0">HOLA
            <span class="font-semibold ">
                <?php echo $user['Nombre']; ?>
            </span>
        </h1>
    </div>
    <br>
    <?php if (isset($candidato)) { ?>
        <div class="bg-gray-300 w-max mx-auto p-1  text-sm rounded shadow-inner">
            Su candidatura cuenta con
            <span class="font-bold text-sm text-red-700">
                <?php echo ($candidato['Num_Votos']) ?>
            </span>
            votos.
        </div>
    <?php } ?>

    <?php if (isset($votado)) { ?>
        <div class="bg-gray-300 w-max mx-auto  text-sm rounded shadow-inner ">Actualmente has votado por
            <span class="font-bold text-xl text-indigo-600">
                <?php echo getUserState($conn, $votado["Candidato"])[0]->fetch_assoc()['Nombre'] ?>
            </span>
        </div>
    <?php } ?>
</div>