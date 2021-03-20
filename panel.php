<?php include "config.php" ?>
<?php include "mysql/query.php" ?>
<?php

// Comprobación si se has 
if (!isset($_SESSION['loginID'])) {
    header("Location: index.php?banner=Acceso Prohibido");
}
$id = $_SESSION['loginID'];

// OBTENER DATOS PRINCIPALES
$votaciones = getGlobalStates($conn);
$user = getUserState($conn, $id)[0]->fetch_assoc();
$votado = getUserState($conn, $id)[1]->fetch_assoc();
$candidato = getUserState($conn, $id)[2]->fetch_assoc();


if (isset($_GET['retirarC'])) {
    $sql_retirar_candidatura = "DELETE FROM Elecciones.Candidatos WHERE id=$id;";
    $conn->query($sql_retirar_candidatura);
    errorCheck($conn);
    header("Location: panel.php?banner=Candidatura Retirada");
    die();
}
if (isset($_GET['presentarC'])) {
    $sql_presentar_candidatura = "INSERT INTO Elecciones.Candidatos VALUES ($id,0);";
    $conn->query($sql_presentar_candidatura);
    errorCheck($conn);
    header("Location: panel.php?banner=Candidatura Insertada");
    die();
}
if (isset($_GET['voto_a'])) {
    $id_c   =   $_GET['voto_a'];
    $sql_borrar = "DELETE FROM Elecciones.Votaciones WHERE id=$id;";
    $conn->query($sql_borrar);
    errorCheck($conn);
    $sql_votar_a = "INSERT INTO Elecciones.Votaciones (id, Candidato) VALUES($id, $id_c);";
    $conn->query($sql_votar_a);
    errorCheck($conn);
    $sql_sum_c = "UPDATE Elecciones.Candidatos SET Num_Votos=Num_Votos+1 WHERE id=$id_c;";
    $conn->query($sql_sum_c);
    errorCheck($conn);
    if (isset($votado)) {
        $id_anterior_votado = $votado["Candidato"];
        $sql_sum_c = "UPDATE Elecciones.Candidatos SET Num_Votos=Num_Votos-1 WHERE id=$id_anterior_votado;";
        $conn->query($sql_sum_c);
        errorCheck($conn);
        echo ($id_anterior_votado);
    }

    header("Location: panel.php?banner=Votación Realizada");
    die();
}
if (isset($_GET['deleteV'])) {
    print_r($id);
    $sql_borrar = "DELETE FROM Elecciones.Votaciones WHERE id=$id;";
    $conn->query($sql_borrar);
    if (isset($votado)) {
        $id_anterior_votado = $votado["Candidato"];
        $sql_sum_c = "UPDATE Elecciones.Candidatos SET Num_Votos=Num_Votos-1 WHERE id=$id_anterior_votado;";
        $conn->query($sql_sum_c);
        errorCheck($conn);
        echo ($id_anterior_votado);
    }

    header("Location: panel.php?banner=Votación Retirada");
    die();
}





?>


<?php include 'comp/panel_c/header.php'  ?>

<?php include 'comp/panel_c/exit_b.php' ?>

<?php include 'comp/panel_c/welcome_banner.php' ?>



<nav class="flex text-sm mx-auto w-full sm:w-3/4 bg-gray-200 p-2 rounded shadow-sm">
    <?php if (isset($votado)) { ?>
        <li id="sysVote" class="list-none text-sm sm:text-xl cursor-pointer mx-auto z-10"><span class="text-pink-700 bg-gray-200 cursor-pointer font-semibold">CAMBIA O RETIRA VOTO</span> </li>
    <?php } else { ?>
        <li id="sysVote" class="list-none text-sm sm:text-xl cursor-pointer mx-auto z-10"><span class="text-green-700 bg-gray-200 cursor-pointer font-semibold">VOTAR</span> </li>

    <?php } ?>
    <form class="mx-auto" method="get">
        <?php if (isset($candidato)) { ?>

            <li class="list-none text-sm sm:text-xl cursor-pointer mx-auto z-10"><input class="text-indigo-800 bg-gray-200 cursor-pointer font-semibold focus:outline-none focus:border-transparent" type="submit" name="retirarC" value="RETIRAR CANDIDATURA"></li>
        <?php } else { ?>
            <li class="list-none text-sm sm:text-xl cursor-pointer mx-auto z-10"><input class="text-pink-700 bg-gray-200 cursor-pointer font-semibold focus:outline-none focus:border-transparent" type="submit" name="presentarC" value="PRESENTAR CANDIDATURA"></li>

        <?php } ?>
    </form>
</nav>

<?php if (isset($_GET['banner'])) { ?>

    <div class="bg-blue-200 w-3/6 rounded-lg  sm:text-xl text-sm font-bold  uppercase text-center mt-2 mx-auto">
        <?php echo ($_GET['banner']); ?>
        <span id="closeTag" class="relative text-xl right text-red-500 cursor-pointer font-bold">
            X
        </span>

    </div>
<?php } ?>

<div class="mb-2 bg-gray-200 w-100 sm:w-3/4 min-w-max mx-auto rounded-xl pb-1" id="voteSelector">
    <?php if (isset($votado)) { ?>

        <form method="get">
            <span class="absolute  ">

                <input class="text-red-600 ml-4 uppercase text-sm font-semibold bg-gray-200 rounded-2xl cursor-pointer focus:outline-none focus:ring-2  focus:border-transparent" type="submit" name="deleteV" value="Retirar Voto">

            </span>
        </form>
    <?php } ?>
    <h1 class="bg-gray-400 text-white w-max  pr-2 pl-2 mt-1 mx-auto font-bold shadow-inner rounded-2xl uppercase mb-1 text-center">Candidatos
    </h1>
    <form class="bg-white w-full  mx-auto  flex p-2 rounded-xl shadow-inner">
        <?php
        $array_candidatos_nombre = array();
        $array_candidatos_votos  = array();
        $array_candidatos_bg = array();
        $array_candidatos_border  = array();

        foreach ($votaciones as $candidato) {
            $control = intval($candidato[0]);
            if (($control == 999)) {
                array_push($array_candidatos_votos, intval($candidato[1]));
            } else {

                array_push($array_candidatos_votos, intval($candidato[1]));
            }
            $candidato = $candidato[0];
            $candidato_votos = $candidato[1];
            $candidato_data = getUserState($conn, $candidato)[0]->fetch_assoc();
            $c_nombre = $candidato_data['Nombre'];
            if ((($control == 999))) {
                array_push($array_candidatos_nombre, "EN BLANCO");
            } else {
                array_push($array_candidatos_nombre, $c_nombre);
            }
            $c_apellido1 = $candidato_data['Apellido1'];
            $c_apellido2 = $candidato_data['Apellido2'];
            $c_edad = $candidato_data['edad'];
            $c_sexo = $candidato_data['Sexo'];
            $c_foto = $candidato_data['Foto'];
            $c_propuesta = $candidato_data['Propuesta'];

        ?>
            <div class="flex-grow text-center m-1">
                <?php if (isset($votado) and $votado["Candidato"] == $candidato_data['id']) { ?>
                    <h1 class='font-bold text-green-500'><?php echo $c_nombre ?></h1>
                    <div class='tooltip  relative flex-grow'>

                        <input type='submit' style='background: url(img/<?php echo ($c_foto); ?> ); background-size: 100% 100%;' class='focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent sm:w-16 sm:h-16 w-6 h-6 pt-2  rounded-full shadow-2xl cursor-pointer text-transparent' name='voto_a' value='<?php echo $candidato_data['id']; ?>'>
                        </input>
                        <span class='tooltiptext '>
                            <h2 class='text-green-200'>

                                HAS VOTADO POR <?php echo ("$c_nombre $c_apellido1") ?>
                            </h2>
                        </span>
                    </div>

                <?php } elseif (isset($candidato_data['id']) && $candidato_data['id'] < 900) { ?>

                    <h3> <?php echo $c_nombre ?></h3>
                    <div class='tooltip relative flex-grow'>
                        <input type='submit' style='background: url(img/<?php echo $c_foto; ?>); background-size: 100% 100%;' class='focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent sm:w-14 sm:h-14 w-6 h-6 rounded-full shadow-lg cursor-pointer text-transparent ' name='voto_a' value='<?php echo $candidato_data['id'] ?>'>
                        </input>

                        <span class='tooltiptext'>
                            <input type='submit' style='background: url(img/<?php echo $c_foto ?>); background-size: 100% 100%;' class='sm:w-12 sm:h-12 w-6 h-6 rounded-full   shadow-lg cursor-pointer text-transparent bg-white ' name='voto_a' value='<?php echo $candidato_data['id']; ?>'>
                            <br>
                            <?php echo "$c_nombre $c_apellido2 $c_apellido2" ?>

                            <br>
                            <?php echo "$c_edad años, $c_sexo" ?>
                            <br>
                            <span class='text-red-400 uppercase'>
                                <?php echo $c_propuesta ?>
                            </span>
                        </span>
                    </div>

                <?php } else { ?>
                    <?php echo $c_nombre; ?>
                    <div class='tooltip relative flex-grow'><input type='submit' class='focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent sm:w-14 sm:h-14 w-6 h-6 rounded-full border-2 cursor-pointer  text-transparent bg-white' name='voto_a' value='999'>
                        </input>
                        <span class='tooltiptext'>
                            <span class='text-yellow-400 uppercase'>VOTAR EN BLANCO<span>
                                </span>
                    </div>
                <?php

                }



                ?>
            </div>
        <?php

            array_push($array_candidatos_bg, "rgba(" . rand(1, 255) . "," . rand(1, 255) . "," . rand(1, 255) . ",1)");
            array_push($array_candidatos_border, "rgba(" . rand(1, 255) . "," . rand(1, 255) . "," . rand(1, 255) . ",0.2)");
        }
        $_SESSION["candidatos"] = json_encode($array_candidatos_nombre);
        $_SESSION["n_votos"]    = json_encode($array_candidatos_votos);
        $_SESSION["bg_chart"]   = json_encode($array_candidatos_bg);
        $_SESSION["bg_border"]  = json_encode($array_candidatos_border);







        ?>
    </form>
</div>


<div class="">
    <div class=" h-64  w-3/4  mx-auto p-4 shadow-xl rounded-3xl ">
        <canvas id="voteChart"></canvas>
    </div>
</div>
<br>

<?php print_r($votaciones[0][1]) ?>
<?php print_r(getUserState($conn,$votaciones[0][0])[0]->fetch_assoc()["Nombre"]) ?>

<?php 
    $nombre_ganador = getUserState($conn,$votaciones[0][0])[0]->fetch_assoc()["Nombre"];
    $num_votos_ganador = $votaciones[0][1];
    $label = "ACTUAL GANADOR/A ===> $nombre_ganador, CON $num_votos_ganador VOTOS"
?>

<script>
    var ctx = document.getElementById('voteChart').getContext('2d');
    var voteChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: <?php echo $_SESSION['candidatos'] ?>,
            datasets: [{
                barThickness: 12,
                maxBarThickness: 16,

                barPercentaje: 1,
                minBarLength: 1,
                label: '<?php echo $label ?>',
                data: <?php echo $_SESSION['n_votos'] ?>,
                backgroundColor: [
                    'rgba(255, 100, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(100, 159, 64, 0.2)',
                    'rgba(100, 255, 132, 0.2)',
                    'rgba(255, 100, 50, 0.2)',
                    'rgba(1, 1, 1, 0.2)',

                ],
                borderColor: [
                    'rgba(255, 100, 132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(100, 159, 64, 1)',
                    'rgba(100, 255, 132, 1)',
                    'rgba(255, 100, 50, 1)',
                    'rgba(1, 1, 1, 1)',

                ],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Elecciones 2021'
            },
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }],
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0,
                        max: 8 ,
                    }
                }]
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    boxWidth: 80,
                    fontSize: 14,
                    fontFamily: "Fira Code",
                    padding: 10,

                }
            }

        }
    });
</script>

<script>
    const sysVote = document.getElementById("sysVote");
    const voteSelector = document.getElementById("voteSelector");
    const closeTag = document.getElementById("closeTag");
    sysVote.addEventListener("click", () => {
        voteSelector.classList.toggle("hidden");
    })
    closeTag.addEventListener("click", () => {
        closeTag.parentElement.classList.toggle("hidden");
    })
</script>


</body>

</html>