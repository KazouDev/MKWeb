<?php    
session_start();
require_once "../../utils.php";

$id = buisness_connected_or_redirect();

$query = "SELECT 
sae._utilisateur.nom , sae._utilisateur.prenom , sae._utilisateur.telephone, sae._logement.titre, sae._reservation.id as numero_de_reservation, sae._reservation.date_debut AS Date_Debut, sae._reservation.date_fin AS Date_Fin, sae._reservation.date_annulation
FROM sae._reservation
INNER JOIN sae._logement ON sae._reservation.id_logement = sae._logement.id
INNER JOIN sae._compte_client ON sae._reservation.id_client = sae._compte_client.id
INNER JOIN sae._utilisateur ON sae._compte_client.id = sae._utilisateur.id
WHERE sae._logement.id_proprietaire = $id
ORDER BY Date_Debut DESC";

$results = request($query, false);
$current_date = date("Y-m-d"); // Obtient la date actuelle au format Y-m-d
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/logement.css">
    <link rel="stylesheet" href="../css/mes_reserv.css">
    <title>Mes réservations</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
</head>

<body class="page">
    <div class="wrapper">
        <?php require_once './header.php'; ?>
        <main class="main">
            <div class="main__container reserv">
                <div class="mes__reserv__titre">
                    <div class="test__TEST">
                        <h1>Les réservations</h1>
                        <!-- <img src="../img/filter-3.webp" alt="">
                        <img src="../img/arrows.webp" alt="">-->
                    </div>
                    <!--<a href="#" id="export-reservation-btn"><i class="fa-solid fa-download"></i></a> -->
                </div>
                <?php if (empty($results)) { ?>
                    <div class="mes__reserv__empty">
                        <h4>Vous n'avez pas encore de réservations</h4>
                    </div>
                    <?php } else {
                    foreach ($results as $result) { 
                        $date_debut = $result["date_debut"];
    $date_fin = $result["date_fin"];
    $date_annulation = $result["date_annulation"];

    // Vérifie si la réservation est confirmée (date de début dans le futur)
    if (empty($date_annulation) && ($current_date <= $date_debut)) {
        $status = "À venir";
        $status_class = "green";
    } elseif (!empty($date_annulation)) {
        if ($current_date > $date_debut){
            if ($current_date <= $date_fin){
                $status = "En cours";
                $status_class = "green";
            } else {
                $status = "Passée";
                $status_class = "green"; 
            }
            
        }
    } else {
        if ($current_date > $date_debut){
            if ($current_date <= $date_fin){
                $status = "En cours";
                $status_class = "green";
            } else {
                $status = "Passée";
                $status_class = "green"; 
            }
            
        }
    }
    ?>
                        <div class="card__reserv">
                            <div class="buisness_mes_reserv_line">
                                <div class="buisness_left_big_box">
                                    <div class="buisness_left_box">
                                        <h4><?php echo $result["titre"] ?></h4>
                                        <div class="mes_reserv__numero">
                                            <h4>Client : </h4>
                                            <h5><?php echo $result["nom"] . ' ' . $result["prenom"]; ?></h5>
                                        </div>
                                        <div class="mes_reserv__numero">
                                            <h4>N° de téléphone : </h4>
                                            <h5><?php echo $result["telephone"] ?></h5>
                                        </div>
                                        <div class="mes_reserv__numero">
                                            <h4>Numéro de réservation : </h4>
                                            <h5><?php echo $result["numero_de_reservation"] ?></h5>
                                        </div>
                                    </div>
                                    <h4><?php echo $result["date_debut"] ?> – <?php echo $result["date_fin"] ?></h4>
                                    <p class="<?php echo $status_class ?>"><?php echo $status ?></p>
                                </div>
                                <div>
                                    <!--<i class="fa-regular fa-eye"></i>
                                    <?php if ($status === "À venir") { ?>
                                        <i class="fa-solid fa-trash red"></i>
                                    <?php } ?>-->
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </main>
        <?php require_once './footer.php'; ?>
    </div>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var exportButton = document.getElementById("export-reservation-btn");
            exportButton.addEventListener("click", function(e) {
                e.preventDefault();

                fetch("../exporter_reservation.php?id=<?php echo $id ?>&type=1")
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error("Une erreur s'est produite lors du téléchargement des réservations.");
                        }
                        return response.blob();
                    })
                    .then(function(blob) {
                        var url = window.URL.createObjectURL(blob);
                        var a = document.createElement('a');
                        a.href = url;
                        a.download = 'reservations.csv';
                        document.body.appendChild(a);
                        a.click();

                        window.URL.revokeObjectURL(url);
                    })
                    .catch(function(error) {
                        alert(error.message);
                    });
            });
        });
    </script>
</body>
</html>