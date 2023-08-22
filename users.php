<?php
// Configurazione per la connessione al database MySQL
$servername = "localhost"; // Indirizzo del server MySQL (può essere "localhost" se in locale)
$username = "root"; // Sostituire con il nome utente del database
$password = ""; // Sostituire con la password del database
$dbname = "user_management"; // Nome del database

// Connessione al database MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Gestione degli errori di connessione al database
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Operazione di inserimento utente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Recupera i dati del form inviati tramite metodo POST
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Query SQL per inserire l'utente nel database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    // Esegui la query
    if ($conn->query($sql) === TRUE) {
        // Reindirizza l'utente alla stessa pagina dopo l'inserimento
        header("Location: users.php");
    } else {
        // In caso di errore nell'inserimento dell'utente, mostra un messaggio di errore
        echo "Errore nell'inserimento dell'utente: " . $conn->error;
    }
}

// Operazione di eliminazione utente
if (isset($_GET["delete"])) {
    // Recupera l'ID dell'utente da eliminare dalla query string
    $id = $_GET["delete"];

    // Query SQL per eliminare l'utente dal database
    $sql = "DELETE FROM users WHERE id=$id";

    // Esegui la query
    if ($conn->query($sql) === TRUE) {
        // Reindirizza l'utente alla stessa pagina dopo l'eliminazione
        header("Location: users.php");
    } else {
        // In caso di errore nell'eliminazione dell'utente, mostra un messaggio di errore
        echo "Errore nell'eliminazione dell'utente: " . $conn->error;
    }
}

// Recupera l'elenco degli utenti dal database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management System</title>
    <!-- Includiamo il CSS di Bootstrap per lo stile dell'interfaccia utente -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>User Management System</h2>
        <h3>Aggiungi nuovo utente</h3>
        <form action="users.php" method="post">
            <!-- Form per l'inserimento dei dati dell'utente -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Aggiungi utente</button>
        </form>

        <h3>Elenco utenti</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <!-- Mostra l'elenco degli utenti dal database -->
                    <tr>
                        <td><?php echo $row["username"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td>
                            <!-- Aggiungi un link per eliminare l'utente (utilizzando GET) -->
                            <a href="users.php?delete=<?php echo $row["id"]; ?>" class="btn btn-danger btn-sm">Elimina</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Includiamo i file JavaScript necessari per il funzionamento di Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
